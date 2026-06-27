<?php

namespace App\Http\Controllers;

use App\Models\Library\ExtentType;

use Illuminate\Http\Request;

class ExtentTypeController extends Controller
{
    public function index()
    {
        return view('library.extenttypes.index');
    }

    public function all()
    {
        $extentTypes = ExtentType::all();
        return response()->json(compact('extentTypes'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'extentType.name' => 'required|string|max:255',
        ]);

        $extentType = ExtentType::create([
            'name' => $data['extentType']['name'],
        ]);

        return response()->json(['added' => $extentType]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $extentType = ExtentType::where('id', $id)->first();

        if ($extentType) {
            $extentType->delete();
            return response()->json([], 204);
        }

        return response()->json(['error' => 'Extent type not found'], 404);
    }
}
