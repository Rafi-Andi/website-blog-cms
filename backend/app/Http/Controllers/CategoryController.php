<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
 
    public function index(Request $request)
    {
        // if($request->user()->role !== 'admin'){
        //     return response()->json([
        //         'message' => 'Unauthorized.',
        //     ], 403);
        // }
        $categories = Category::select('id', 'name', 'slug')->get();

        return response()->json([
            'data' => $categories,
        ], 200);
    }

    public function store(Request $request)
    {
        if($request->user()->role !== 'admin'){
            return response()->json([
                'message' => 'Unauthorized.',
            ], 403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories', // Menangani error 422 'Already Exists' [cite: 97-104]
        ]);

        $category = Category::create($validated);

        return response()->json([
            'message' => 'New catecory has been created.',
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'created_at' => $category->created_at->toIso8601String(),
            ],
        ], 201);
    }

  
    public function update(Request $request, string $id)
    {
        if($request->user()->role !== 'admin'){
            return response()->json([
                'message' => 'Unauthorized.',
            ], 403);
        }
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'category not found'], 404);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id),
            ], 
        ]);

        $category->update($validated);

        return response()->json(['message' => 'Category has been updated'], 200);
    }


    public function destroy(Request $request, string $id)
    {
        if($request->user()->role !== 'admin'){
            return response()->json([
                'message' => 'Unauthorized.',
            ], 403);
        }
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category has been deleted.'], 200);
    }
    
    
}