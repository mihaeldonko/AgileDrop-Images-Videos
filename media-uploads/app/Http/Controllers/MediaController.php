<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media' => 'required|file|mimes:jpg,jpeg,png,mp4,mkv,avi|max:20480',
        ]);

        $file = $request->file('media');
        $filePath = $file->store('media', 'public');
        $fileType = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();

        $media = Media::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_size' => $fileSize,
        ]);


        return response()->json([
            'message' => 'File uploaded successfully!',
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'file_path' => asset('storage/' . $filePath),
        ], 201);
    }
}
