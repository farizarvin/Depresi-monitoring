<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Serve user images from private storage
     * 
     * @param string $path - Can be 'default' or 'id/{userId}/{filename}'
     * @return \Illuminate\Http\Response
     */
    public function serveUserImage($path)
    {
        // Handle "default" case
        if ($path === 'default') {
            $filePath = 'app/data/images/users/default.png';
        } else {
            $filePath = 'app/data/images/users/' . $path;
        }
        
        // Check if file exists
        if (!Storage::disk('private')->exists($filePath)) {
            \Log::warning('File not found', [
                'requested_path' => $path,
                'full_path' => $filePath
            ]);
            abort(404, 'Image not found');
        }
        
        try {
            // Use Laravel's file response for proper binary handling
            $fullPath = Storage::disk('private')->path($filePath);
            
            \Log::info('File served successfully', [
                'path' => $path,
                'full_path' => $fullPath,
                'exists' => file_exists($fullPath)
            ]);
            
            return response()->file($fullPath, [
                'Content-Type' => Storage::disk('private')->mimeType($filePath),
                'Cache-Control' => 'public, max-age=3600'
            ]);
                
        } catch (\Exception $e) {
            \Log::error('Error serving file', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            abort(500, 'Error serving image');
        }
    }
}
