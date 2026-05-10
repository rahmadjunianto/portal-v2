<?php

namespace App\Http\Controllers;

use App\Models\Download;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    /**
     * Display a listing of downloads
     */
    public function index()
    {
        $downloads = Download::query()
            ->orderBy('published_at', 'desc')
            ->paginate(20);

        return view('download', compact('downloads'));
    }
}
