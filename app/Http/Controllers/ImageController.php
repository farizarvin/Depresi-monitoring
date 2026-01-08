<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ImageController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth', only : ['webGet']),
            // new Middleware('auth:sanctum', only : ['apiGet']),
        ];
    }
   
    public function webGet($mime,$type,$id_col,$id,$filepath)
    {
        $user=Auth::guard('web')->user();
        $path="app/data/$mime/$type/$id";

        if($user->get($id_col)!=$id && $user->role!='admin')
        {
            $path="app/data/$mime/$type";
            $file=Storage::disk('private')->path("$path/default.png");
            return response()->file($file)->setStatusCode(403, 'Unauthorized access');
        }

        $file=Storage::disk('private')->exists("$path/$filepath");
        if($file==false)
        {
            $path="app/data/$mime/$type";
            $file=Storage::disk('private')->path("$path/default.png");
            return response()->file($file)->setStatusCode(200, "Default $mime");
        }
        $file=Storage::disk('private')->path("$path/$filepath");
        // dd($file);
        return response()->file($file)->setStatusCode(200, "Requested image");
    }
    public function webDefault($mime,$type)
    {
        $path="app/data/$mime/$type";
        $file=Storage::disk('private')->path("$path/default.png");
        return response()->file($file)->setStatusCode(200, "Default $mime");
    }
}
