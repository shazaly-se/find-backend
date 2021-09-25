<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogCategory;

class BlogCategoriesController extends Controller
{
    public function index(){
        $blogcategories = BlogCategory::all();
        return response()->json(["blogcategories" => $blogcategories]);
    }
}
