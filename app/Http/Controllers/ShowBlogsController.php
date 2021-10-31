<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Tag;

class ShowBlogsController extends Controller
{
    public function index(Request $request){
        $blogs = Blog::join("blogcategories","blogcategories.id","=","blogs.blogcategory_id")
        ->where(function ($query) use($request){
            if($request->id > 0){ $query->where('blogcategories.id', $request->id); }
            })
        ->get(array("blogs.*","blogcategories.name_en","blogcategories.name_ar"));

        // ->select("blogs.id","blogs.blogcategory_id","blogs.title_en","blogs.title_ar","blogs.description_en","blogs.description_ar","blogs.focuskeyphrases_en",

        // "blogs.focuskeyphrases_ar","blogs.seotitle_en","blogs.seotitle_ar","blogs.slug_en","blogs.slug_ar","blogs.metadescription_en","blogs.metadescription_ar",
        // "blogs.image","blogs.image_ar", "blogs.alttext_en","blogs.alttext_ar",
        // "blogcategories.name_en","blogcategories.name_ar",'DATE(blogs.created_at)')->get();

        return response()->json(["blogs" =>$blogs]);

    }

    public function blogById($id){
        $blog = Blog::join("blogcategories","blogcategories.id","=","blogs.blogcategory_id")
        ->where("blogs.id",$id)
        ->first(array("blogs.*","blogcategories.name_en","blogcategories.name_ar"));
        return response()->json(["blog"=>$blog]);

    }
}
