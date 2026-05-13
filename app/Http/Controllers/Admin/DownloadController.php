<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DownloadController extends Controller
{
    /**
     * Display a listing of downloads.
     */
    public function index(Request $request)
    {
        $query = Download::query();

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $downloads = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.downloads.index', compact('downloads'));
    }

    /**
     * Show the form for creating a new download.
     */
    public function create()
    {
        return view('admin.downloads.create');
    }

    /**
     * Store a newly created download.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:51200', // Max 50MB
            'is_published' => 'boolean',
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $extension = $file->getClientOriginalExtension();

        // Generate unique filename
        $newFileName = Str::uuid() . '.' . $extension;

        // Store in public storage
        $filePath = $file->storeAs('downloads', $newFileName, 'public');

        $download = Download::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(5),
            'description' => $request->description,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => $extension,
            'file_size' => $fileSize,
            'downloads_count' => 0,
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->boolean('is_published') ? now() : null,
        ]);

        return redirect()
            ->route('admin.downloads.index')
            ->with('success', 'Download berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified download.
     */
    public function edit(Download $download)
    {
        return view('admin.downloads.edit', compact('download'));
    }

    /**
     * Update the specified download.
     */
    public function update(Request $request, Download $download)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:51200',
            'is_published' => 'boolean',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'is_published' => $request->boolean('is_published'),
        ];

        // If publishing for the first time, set published_at
        if ($request->boolean('is_published') && !$download->is_published) {
            $data['published_at'] = now();
        }

        // If unpublishing
        if (!$request->boolean('is_published') && $download->is_published) {
            $data['published_at'] = null;
        }

        // Handle new file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($download->file_path && Storage::disk('public')->exists($download->file_path)) {
                Storage::disk('public')->delete($download->file_path);
            }

            $file = $request->file('file');
            $fileSize = $file->getSize();
            $extension = $file->getClientOriginalExtension();

            $newFileName = Str::uuid() . '.' . $extension;
            $filePath = $file->storeAs('downloads', $newFileName, 'public');

            $data['file_name'] = $file->getClientOriginalName();
            $data['file_path'] = $filePath;
            $data['file_type'] = $extension;
            $data['file_size'] = $fileSize;
        }

        $download->update($data);

        return redirect()
            ->route('admin.downloads.index')
            ->with('success', 'Download berhasil diperbarui.');
    }

    /**
     * Remove the specified download.
     */
    public function destroy(Download $download)
    {
        // Delete file
        if ($download->file_path && Storage::disk('public')->exists($download->file_path)) {
            Storage::disk('public')->delete($download->file_path);
        }

        $download->delete();

        return redirect()
            ->route('admin.downloads.index')
            ->with('success', 'Download berhasil dihapus.');
    }
}
