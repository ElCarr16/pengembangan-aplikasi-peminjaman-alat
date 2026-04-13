<?php

namespace App\Http\Controllers;

use App\Models\Tool; // Gunakan PascalCase
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // S Kapital

class ToolController extends Controller
{
    public function index()
    {
        // membuat tiap data yang ditampilkan menjadi setiap 10 data per 1 halaman
        $tools = Tool::with('category')->paginate(10);
        return view('admin.tools.index', compact('tools'));
    }

    // menambah alat
    public function create()
    {
        $categories = Category::all();
        return view('admin.tools.create', compact('categories'));
    }
    // menyimpan alat
    public function store(Request $request)
    {
        $request->validate([
            'nama_alat'   => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stok'        => 'required|integer|min:0',
            'harga_perhari' => 'required|integer|min:0',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'deskripsi'   => 'nullable|string',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('tools', 'public');
        }

        $tool = Tool::create([
            'nama_alat'   => $request->nama_alat,
            'category_id' => $request->category_id,
            'stok'        => $request->stok,
            'harga_perhari' => $request->harga_perhari,
            'gambar'      => $gambarPath,
            'deskripsi'   => $request->deskripsi,
        ]);

        ActivityLog::record('create', "Admin menambahkan alat baru: {$tool->nama_alat}");

        return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil ditambahkan');
    }

    // mwnampilkan views update data
    public function edit(Tool $tool)
    {
        $categories = Category::all();
        return view('admin.tools.edit', compact('tool', 'categories'));
    }

    public function update(Request $request, Tool $tool)
    {
        $request->validate([
            'nama_alat'   => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stok'        => 'required|integer|min:0',
            'harga_perhari' => 'required|integer|min:0',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'deskripsi'   => 'nullable|string',
        ]);

        $data = $request->except(['gambar']);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($tool->gambar) {
                Storage::disk('public')->delete($tool->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('tools', 'public');
        }

        $tool->update($data);

        ActivityLog::record('update alat', "Memperbarui data alat: {$tool->nama_alat}");

        return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil diperbarui');
    }

    public function destroy(Tool $tool)
    {
        $nama_alat = $tool->nama_alat;

        // Hapus file dari storage jika ada
        if ($tool->gambar && Storage::disk('public')->exists($tool->gambar)) {
            Storage::disk('public')->delete($tool->gambar);
        }

        // Hapus data dari database (di luar IF gambar agar tetap terhapus)
        $tool->delete();

        ActivityLog::record('delete alat', "Menghapus data alat: {$nama_alat}");

        return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil dihapus');
    }

    // menampilkan halaman detail alat sebalum meminjam alat
    public function show($id)
    {
        $tool = Tool::findOrFail($id);
        return view('peminjam.tools.show', compact('tool'));
    }
}
