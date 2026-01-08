<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Dash21Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = \App\Models\Siswa\Dash21::with('siswa')->get();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id',
            'depression_score' => 'required|integer',
        ]);

        $dash21 = new \App\Models\Siswa\Dash21();
        $dash21->id_siswa = $request->id_siswa;
        // Use the custom setter to handle is_depressed logic
        $dash21->setIsDepressed($request->depression_score);

        return response()->json(['message' => 'Data stored successfully', 'data' => $dash21], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $dash21 = \App\Models\Siswa\Dash21::with('siswa')->findOrFail($id);
        return response()->json($dash21);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_siswa' => 'sometimes|exists:siswa,id',
            'depression_score' => 'sometimes|integer',
        ]);

        $dash21 = \App\Models\Siswa\Dash21::findOrFail($id);
        
        if ($request->has('id_siswa')) {
            $dash21->id_siswa = $request->id_siswa;
        }

        if ($request->has('depression_score')) {
            // Recalculate is_depressed if score changes
            $dash21->setIsDepressed($request->depression_score);
        } else {
            $dash21->save();
        }

        return response()->json(['message' => 'Data updated successfully', 'data' => $dash21]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dash21 = \App\Models\Siswa\Dash21::findOrFail($id);
        $dash21->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }
}
