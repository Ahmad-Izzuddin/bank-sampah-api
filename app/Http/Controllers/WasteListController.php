<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WasteList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WasteListController extends Controller
{
    public function index()
    {
        Gate::authorize('admin');
        return response()->json(WasteList::all());
    }

    public function store(Request $request)
    {
        Gate::authorize('admin');

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'unit'             => 'required|string|max:50',
            'points_per_unit'  => 'required|integer|min:0',
        ]);

        $item = WasteList::create($data);
        return response()->json($item, 201);
    }

    public function show($id)
    {
        Gate::authorize('admin');
        return response()->json(WasteList::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('admin');
        $item = WasteList::findOrFail($id);

        $data = $request->validate([
            'name'             => 'sometimes|required|string|max:255',
            'unit'             => 'sometimes|required|string|max:50',
            'points_per_unit'  => 'sometimes|required|integer|min:0',
        ]);

        $item->update($data);
        return response()->json($item);
    }

    public function destroy($id)
    {
        Gate::authorize('admin');
        WasteList::findOrFail($id)->delete();
        return response()->json([
            'message' => 'Delete accomplished',
            'id'      => $id,
        ], 200);
    }
}
