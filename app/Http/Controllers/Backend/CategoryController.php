<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::orderBy('created_at','desc')->paginate(10); // Fetch all categories
        return view('backend.categories.index', compact('categories')); // Return to the categories list view
    }
    
    /**
     * Store a newly created category.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:150|unique:categories',
            'base_price' => 'required|numeric',
            'description' => 'required|string',
            'color_code' => 'required|string|max:10',
        ]);

        $category = Category::create([
            'category_name' => $request->category_name,
            'base_price' => $request->base_price,
            'description' => $request->description,
            'color_code' => $request->color_code,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.',
            'category' => $category,
        ]);
    }
}
