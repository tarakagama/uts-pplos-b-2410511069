<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FieldController extends Controller
{
    // 1. GET ALL 
    public function index(Request $request)
    {
        $query = Field::with(['category', 'facilities', 'schedules']);

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        return response()->json($query->paginate($request->get('per_page', 10)), 200);
    }

    // 2. CREATE
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price_per_hour' => 'required|integer|min:0',
            'description' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $field = Field::create($request->all());
        return response()->json($field, 201);
    }

    // 3. SHOW
    public function show($id)
    {
        $field = Field::with(['category', 'facilities', 'schedules'])->find($id);
        
        if (!$field) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        
        return response()->json($field, 200);
    }

    // 4. UPDATE
    public function update(Request $request, $id)
    {
        $field = Field::find($id);
        if (!$field) return response()->json(['message' => 'Not Found'], 404);

        $field->update($request->all());
        return response()->json($field, 200);
    }

    // 5. DELETE
    public function destroy($id)
    {
        $field = Field::find($id);
        if (!$field) return response()->json(['message' => 'Not Found'], 404);

        $field->delete();
        return response()->json(null, 204);
    }
}