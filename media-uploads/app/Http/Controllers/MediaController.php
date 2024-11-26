<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

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
            'user_id' => auth()->id(),
        ]);


        return response()->json([
            'message' => 'File uploaded successfully!',
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'file_path' => asset('storage/' . $filePath),
        ], 201);
    }

    public function showMedia(){
        $mediaFiles = Media::all()->toArray();

        return view('media', ['mediaFiles' => $mediaFiles]);
    }

    public function showYourMedia(){
        $user = Auth::user();
        $mediaFiles = Media::all()->where('user_id', $user->id)->toArray();

        return view('mymedia', ['mediaFiles' => $mediaFiles]);
    }
}
