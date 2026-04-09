<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // menampilkan daftar pengguna
    public function index(request $request)
    {
        // fitur pencarian
        $query = User::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%$request->search%")
                ->orWhere('email', 'like', "%$request->search%");
        }
        $users = $query->paginate(10);
        return view('admin.users.index', compact('users'));
    }
    // menampilkan form tambah pengguna
    public function create()
    {
        return view('admin.users.create');
    }
    // menyimpan pengguna baru
    public function store(request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // FIXED: hapus $userId karena ini user BARU
            'password' => 'required|string|min:6', // Tambahkan validasi password
            'role' => 'required|in:admin,petugas,peminjam',
            'tanggal_lahir' => 'required_if:role,peminjam|nullable|date',
            'alamat'        => 'required_if:role,peminjam|nullable|string|min:10',
            'nomor_telepon' => 'required_if:role,peminjam|nullable|regex:/^(\+62|62|0)8[1-9][0-9]{6,11}$/',
            'kota'          => 'required_if:role,peminjam|nullable',
            'provinsi'      => 'required_if:role,peminjam|nullable',
            'kode_pos'      => 'required_if:role,peminjam|nullable|numeric',
        ]);

        // Masukkan semua kolom ke dalam create
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'nomor_telepon' => $request->nomor_telepon,
        ]);

        ActivityLog::record('create', "admin menambahkan pengguna baru: $user->name");
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan');
    }
    // edit pengguna
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // validasi email unik kecuali untuk pengguna yang sedang diedit
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,petugas,peminjam',
        ]);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];
        // jika password diisi, update password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        activityLog::record('update', "admin mengupdate pengguna: $user->name");
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui');
    }

    // Menampilkan halaman form edit profil
    public function editProfile()
    {
        return view('peminjam.profile.edit', [
            'user' => Auth::user()
        ]);
    }

    // Menyimpan perubahan data profil
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|min:10',
            'kota' => 'required|string',
            'provinsi' => 'required|string',
            'kode_pos' => 'required|numeric',
            'nomor_telepon' => ['required', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,11}$/'],
        ]);

        $user->update($request->all());

        return redirect()->route('dashboard')->with('success', 'Profil berhasil diperbarui!');
    }

    // hapus pengguna
    public function destroy(user $user)
    {
        // mencegah admin menghapus akunnya sendiri yang sedang login
        if ($user->id == Auth::id()) {
            return back()->withErrors(['error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login']);
        }
        $nama = $user->name;
        $user->delete();
        activityLog::record('delete', "admin menghapus pengguna: $nama");
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus');
    }
}
