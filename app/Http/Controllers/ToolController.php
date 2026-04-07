<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ToolController extends Controller
{
    public function index()
    {
        $tools = Tool::with('category')->latest()->paginate(10);
        return view('admin.tools.index', compact('tools'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.tools.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_alat'   => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stok'        => 'required|integer|min:0',
            'gambar'      => 'nullable|image|max:10240',
            'deskripsi'   => 'nullable|string'
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('tools', 'public');
        }

        $tool = Tool::create($validated);

        ActivityLog::record('Tambah Alat', 'Menambahkan alat: ' . $tool->nama_alat);

        return redirect()->route('tools.index')
            ->with('success', 'Alat berhasil ditambahkan.');
    }

    public function edit(Tool $tool)
    {
        $categories = Category::all();
        return view('admin.tools.edit', compact('tool', 'categories'));
    }

    public function update(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'nama_alat'   => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stok'        => 'required|integer|min:0',
            'gambar'      => 'nullable|image|max:10240',
            'deskripsi'   => 'nullable|string'
        ]);

        // Handle update gambar
        if ($request->hasFile('gambar')) {

            // Hapus gambar lama
            if ($tool->gambar && Storage::disk('public')->exists($tool->gambar)) {
                Storage::disk('public')->delete($tool->gambar);
            }

            $validated['gambar'] = $request->file('gambar')->store('tools', 'public');
        }

        $tool->update($validated);

        ActivityLog::record('Update Alat', 'Update alat: ' . $tool->nama_alat);

        return redirect()->route('tools.index')
            ->with('success', 'Data alat diperbarui.');
    }

    public function destroy(Tool $tool)
    {
        if ($tool->gambar && Storage::disk('public')->exists($tool->gambar)) {
            Storage::disk('public')->delete($tool->gambar);
        }

        $namaAlat = $tool->nama_alat;
        $tool->delete();

        ActivityLog::record('Hapus Alat', 'Menghapus alat: ' . $namaAlat);

        return redirect()->route('tools.index')
            ->with('success', 'Alat berhasil dihapus.');
    }
}
