<?php

namespace App\Http\Controllers;

use App\Models\category;
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
        return view('admin.categories.create');
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
    public function destroy(Category $category)
    {
        // cel apakah kategori ini masih dipakai di table tools
        // kita menggunakan method tools() yang sudah didefinisikan di model Category untuk mengecek relasi
        if ($category->tools()->count() > 0) 
        {
            return back()->withErrors(['error' => 'kategori tidak bisa dihapus kaarena masih memiliki data alat. Hapus atau pindahkan alat ke kategaori lain terlebih dahulu!']);
        }
        $nama = $category->nama_kategori;
        $category->delete();
        ActivityLog::record('Hapus Kategori', "Menghapus kategori: $nama");
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}