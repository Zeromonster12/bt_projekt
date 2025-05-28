<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ImageUploadController
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png|max:20480',
        ]);

        try {
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            $fileName = time() . '_' . Str::random(10) . '.' . $extension;
            $path = $file->storeAs('uploads', $fileName, 'public');

            return response()->json([
                'location' => url('storage/' . $path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Image upload failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadpfp(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:jpg,jpeg,png|max:20480',
            ]);
            
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            $fileName = time() . '_' . Str::random(10) . '.' . $extension;
            $path = $file->storeAs('profilePictures', $fileName, 'public');

            $user = $request->user();
            $user->pfp = 'storage/' . $path;
            $user->save();

            return response()->json([
                'location' => url('storage/' . $path)
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Unsupported file type.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Profile picture upload failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
