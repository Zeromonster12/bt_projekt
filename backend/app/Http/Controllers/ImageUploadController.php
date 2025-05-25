<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json([
                'location' => ''
            ], 400);
        }

        $file = $request->file('file');
        $allowed = ['jpg', 'jpeg', 'png'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowed)) {
            return response()->json([
                'message' => 'Only JPG and PNG files are allowed'
            ], 415);
        }

        $fileName = time() . '_' . Str::random(10) . '.' . $extension;
        $path = $file->storeAs('uploads', $fileName, 'public');

        return response()->json([
            'location' => url('storage/' . $path)
        ]);
    }

    public function uploadpfp(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json([
                'message' => 'No file uploaded'
            ], 400);
        }
    
        $file = $request->file('file');
        $allowed = ['jpg', 'jpeg', 'png'];
        $extension = strtolower($file->getClientOriginalExtension());
    
        if (!in_array($extension, $allowed)) {
            return response()->json([
                'message' => 'Only JPG and PNG files are allowed'
            ], 415);
        }
    
        $fileName = time() . '_' . Str::random(10) . '.' . $extension;
        $path = $file->storeAs('profilePictures', $fileName, 'public');
    
        $user = $request->user();
        $user->pfp = 'storage/' . $path;
        $user->save();
    
        return response()->json([
            'location' => url('storage/' . $path)
        ]);
    }
}
