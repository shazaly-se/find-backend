<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogCategory;
use DB;
class BlogsCategoriesController extends Controller
{
    public function category(){
        $blogcategories = BlogCategory::join("blogs","blogs.blogcategory_id","=","blogcategories.id")
        ->select('blogcategories.id',"blogcategories.name_en","blogcategories.name_ar", DB::raw('COUNT(blogs.blogcategory_id) as count_of_blogs'))
        ->groupBy('blogcategories.id',"blogcategories.name_en","blogcategories.name_ar")
        ->get();
        return response()->json(["blogcategories" => $blogcategories]);
    }
}
