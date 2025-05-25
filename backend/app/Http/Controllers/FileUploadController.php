<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    /**
     * Handle file upload requests
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        
        $filename = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;
        $path = $file->storeAs('files', $filename, 'public');
        
        return response()->json([
            'location' => route('file.download', ['filename' => $filename]),
            'name' => $originalName,
            'size' => $file->getSize(),
            'type' => $file->getMimeType()
        ]);
    }
    
    /**
     * Download a file
     *
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($filename)
    {
        $path = storage_path('app/public/files/' . $filename);
        
        if (!file_exists($path)) {
            abort(404);
        }
        
        $originalName = str_replace(['_' . time(), '_' . strstr($filename, '_', false)], '', $filename);
        $originalName = Str::slug($originalName) . '.' . pathinfo($filename, PATHINFO_EXTENSION);
        
        return response()->download($path, $originalName);
    }
}
