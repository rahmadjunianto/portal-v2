<?php

namespace App\Http\Controllers;

use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    /**
     * Display a listing of downloads
     */
    public function index()
    {
        $downloads = Download::query()
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return view('download', compact('downloads'));
    }

    /**
     * Download a file
     */
    public function download($id)
    {
        $download = Download::findOrFail($id);

        if (!$download->is_published || !$download->file_path) {
            abort(404, 'File tidak tersedia atau belum dipublikasikan.');
        }

        // Get full path - public disk stores files in public/storage/
        $fullPath = public_path('storage/' . $download->file_path);

        if (!file_exists($fullPath)) {
            abort(404, 'File tidak ditemukan di server. File mungkin telah dihapus atau belum diupload dengan benar.');
        }

        // Increment download count
        $download->increment('downloads_count');

        return response()->download($fullPath, $download->file_name);
    }
}
