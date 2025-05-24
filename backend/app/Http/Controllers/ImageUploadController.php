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
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        $path = $file->storeAs('uploads', $fileName, 'public');
        
        return response()->json([
            'location' => url('storage/' . $path)
        ]);
    }
}
