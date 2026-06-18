<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function image(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file'      => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'directory' => ['required', 'string', 'in:stores,products'],
        ]);

        $path = $request->file('file')->store($validated['directory'], 'public');

        return response()->json([
            'path' => $path,
            'url'  => asset("storage/{$path}"),
        ]);
    }
}
