<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ActivityLog; //untuk mencatat log aktivitas
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // menampilkan daftar kategori
    public function index()
    {
        // ambil data kategori + hitung jumlah alat didalamnya (tools_count)
        $categories = category::withCount('tools')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }
    // menampilkan form tambah kategori
    public function create()
    {
        $categories = category::all();

        return view('admin.categories.create', compact('categories'));
    }
    // menyimpan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:categories,nama_kategori',
        ]);

        category::create($request->only('nama_kategori'));

        // catat log aktivitas
        ActivityLog::record('Tambah Kategori', 'Menambahkan kategori baru: ' . $request->nama_kategori);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }
    // menampilkan form edit kategori
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }
    // update kategori
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori,' . $category->id
        ]);

        // Ambil nama lama
        $oldName = $category->nama_kategori;

        // Update data
        $category->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        // Log aktivitas
        ActivityLog::record(
            'Update Kategori',
            "Mengubah kategori $oldName menjadi " . $request->nama_kategori
        );

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diupdate');
    }
    // hapus kategori

    public function destroy(Request $request, Category $category)
    {
        $action = $request->input('delete_action');
        $namaKategori = $category->nama_kategori;

        // Jika kategori memiliki alat
        if ($category->tools()->exists()) {

            if ($action == 'move') {
                $request->validate(['new_category_id' => 'required|exists:categories,id']);

                // Pindahkan alat ke kategori baru
                $category->tools()->update(['category_id' => $request->new_category_id]);
                $logMsg = "Menghapus kategori $namaKategori dan memindahkan alatnya ke kategori ID: " . $request->new_category_id;
            } elseif ($action == 'delete_all') {
                // Hapus semua alat yang terkait
                $category->tools()->delete();
                $logMsg = "Menghapus kategori $namaKategori beserta semua alat di dalamnya.";
            } else {
                // Jika tidak ada aksi yang dipilih (mencegah penghapusan tidak sengaja)
                return redirect()->back()->with('error', 'Silahkan pilih tindakan untuk alat yang tersisa.');
            }
        }

        // Hapus kategori inti
        $category->delete();
        ActivityLog::record('Hapus Kategori', $logMsg ?? "Menghapus kategori: $namaKategori");

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
