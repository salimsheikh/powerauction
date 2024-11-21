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
}
