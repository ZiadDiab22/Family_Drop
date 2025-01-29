<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function addLink(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'value' => 'required',
        ]);

        link::create($validatedData);
        $data = link::get();

        return response([
            'status' => true,
            'message' => 'Added Successfully',
            'data' => $data
        ]);
    }

    public function deleteLink($id)
    {
        if (!(link::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        link::where('id', $id)->delete();

        $data = link::get();

        return response([
            'status' => true,
            'message' => 'deleted successfully',
            'data' => $data,
        ], 200);
    }

    public function editLink(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        if (!(link::where('id', $request->id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $link = link::find($request->id);

        if ($request->has('name')) $link->name = $request->name;
        if ($request->has('value')) $link->value = $request->value;

        $link->save();

        $data = link::get();

        return response([
            'status' => true,
            'message' => 'edited Successfully',
            'data' => $data
        ]);
    }

    public function showLinks()
    {
        $data = link::get();
        return response([
            'status' => true,
            'data' => $data
        ]);
    }
}
