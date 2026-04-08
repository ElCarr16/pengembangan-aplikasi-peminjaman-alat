<?php

namespace App\Http\Controllers;

use App\Models\tool;
use App\Models\category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Storage;

class ToolController extends Controller
{
    //menampilkan data alat
    public function index()
    {
        $tools = tool::with('category')->paginate(10);
        return view('admin.tools.index', compact('tools'));
    }
    //menampilkan form tambah alat
    public function store(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ]);
        //menghandle upload gambar jika ada
        $gambarPath = null;
        if ($request->hasFile('gambar')) 
        {
            // simpan di folder: storage/app/public/tools
            $gambarPath = $request->file('gambar')->store('tools', 'public');
        }
        // simpan ke basis data
        $tool = tool::create([
            'nama_alat' => $request->nama_alat,
            'category_id' => $request->category_id,
            'stok' => $request->stok,
            'gambar' => $gambarPath,
            'deskripsi' => $request->deskripsi,
        ]);
        // catat aktivitas
        ActivityLog::record('create', "admin menambahkan alat baru: $tool->nama_alat");
        return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil ditambahkan');
    }
    //menampilkan form edit alat
    public function edit(tool $tool)
    {
        $categories = category::all();
        return view('admin.tools.edit', compact('tool', 'categories'));
    }
    //mengupdate data alat
    public function update(Request $request, tool $tool)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ]);
        $data = $request->except(['gambar']);
        //menghandle upload gambar jika ada
        if ($request->hasFile('gambar'))
        {
            storage::disk('public')->delete($tool->gambar);
            //simpan gambar baru
            $data['gambar'] = $request->file('gambar')->store('tools', 'public');
        }
        $tool->update($data);
        ActivityLog::record('update alat','memperbarui data alat:' . $tool->nama_alat);
        return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil diperbarui');
    }    
    // menghapus data alat
    public function destroy(tool $tool)
    {
        // hapus gambar jika ada
        if ($tool->gambar && Storage::disk('public')->exists($tool->gambar))
        {
            $nama_alat =$tool->nama_alat;
            $tool->delete();

            activityLog::record('delete alat', "menghapus data alat: $nama_alat");
            return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil dihapus');
        }
    }
}