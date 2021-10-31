<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Tag;
class BlogController extends Controller
{
    public function index(){
        $blogs = Blog::join("blogcategories","blogcategories.id","=","blogs.blogcategory_id")
        ->get(array("blogs.*","blogcategories.name_en","blogcategories.name_ar"));
        return response()->json(["blogs" =>$blogs]);

    }

    public function store(Request $request)
    {
     
        $blog = new Blog;
        $blog->blogcategory_id  = $request->category_id;
        $blog->title_en  = $request->title_en;
        $blog->title_ar  = $request->title_ar;
        $blog->description_en  = $request->description_en;
        $blog->description_ar  = $request->description_ar;
        $blog->focuskeyphrases_en  = $request->focuskeyphrases_en;
        $blog->focuskeyphrases_ar  = $request->focuskeyphrases_ar;
        $blog->seotitle_en  = $request->seotitle_en;
        $blog->seotitle_ar  = $request->seotitle_ar;
        $blog->slug_en  = $request->slug_en;
        $blog->slug_ar  = $request->slug_aren;
        $blog->metadescription_en  = $request->metadescription_en;
        $blog->metadescription_ar  = $request->metadescription_ar;
        if($request->get('image'))
        {
           $image = $request->get('image');
           $name = $request->alttext_en.'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
           \Image::make($request->get('image'))->save(public_path('uploads/blogs/').$name);
           $blog->image=$name;
         }

         if($request->get('image_ar'))
         {
            $image_ar = $request->get('image_ar');
            $name_ar = $request->alttext_ar.'.' . explode('/', explode(':', substr($image_ar, 0, strpos($image_ar, ';')))[1])[1];
            \Image::make($request->get('image_ar'))->save(public_path('uploads/blogs/').$name_ar);
            $blog->image_ar=$name_ar;
          }

         $blog->alttext_en  = $request->alttext_en;
         $blog->alttext_ar  = $request->alttext_ar;

        if($blog->save()){
            $tags_en = $request->tags_en;
            $tags_ar = $request->tags_ar;
            //return $tags;
            for($i=0;$i< count($tags_en); $i++){
                $tag=new Tag;
                $tag->blog_id=$blog->id;
                $tag->lang=1;
                $tag->tag=$tags_en[$i]["tag"];
                $tag->save();
            }

            for($i=0;$i< count($tags_ar); $i++){
                $tag_ar=new Tag;
                $tag_ar->blog_id=$blog->id;
                $tag_ar->lang=2;
                $tag_ar->tag=$tags_ar[$i]["tag"];
                $tag_ar->save();
            }
              
            return response()->json(["success" =>true,"msg" =>"successfully added"]);

            
            //      $tag->save();
            }

        

     


    }

    public function edit($id){
        
        $blog = Blog::join("blogcategories","blogcategories.id","=","blogs.blogcategory_id")
        ->where("blogs.id",$id)
        ->first(array("blogs.*","blogcategories.id as category_id","blogcategories.name_en","blogcategories.name_ar"));
        $tags = Tag::where("blog_id",$id)->get();
        return response()->json(["blog"=>$blog,"tags"=>$tags]);
    }

    public function update(Request $request,$id)
    {
        $old_tags = Tag::where("blog_id",$id)->get();
        foreach($old_tags as $old_tag)
        {
           $old_tag->delete();
        }

 

        $blog =  Blog::where("id",$id)->first();
        $blog->blogcategory_id  = $request->category_id;
        $blog->title_en  = $request->title_en;
        $blog->title_ar  = $request->title_ar;
        $blog->description_en  = $request->description_en;
        $blog->description_ar  = $request->description_ar;
        $blog->focuskeyphrases_en  = $request->focuskeyphrases_en;
        $blog->focuskeyphrases_ar  = $request->focuskeyphrases_ar;
        $blog->seotitle_en  = $request->seotitle_en;
        $blog->seotitle_ar  = $request->seotitle_ar;
        $blog->slug_en  = $request->slug_en;
        $blog->slug_ar  = $request->slug_aren;
        $blog->metadescription_en  = $request->metadescription_en;
        $blog->metadescription_ar  = $request->metadescription_ar;
        if($request->get('image'))
        {
           $image = $request->get('image');
           $name = $request->alttext_en.'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
           \Image::make($request->get('image'))->save(public_path('uploads/blogs/').$name);
           $blog->image=$name;
         }

         if($request->get('image_ar'))
         {
            $image_ar = $request->get('image_ar');
            $name_ar = $request->alttext_ar.'.' . explode('/', explode(':', substr($image_ar, 0, strpos($image_ar, ';')))[1])[1];
            \Image::make($request->get('image_ar'))->save(public_path('uploads/blogs/').$name_ar);
            $blog->image_ar=$name_ar;
          }
          $blog->alttext_en  = $request->alttext_en;
          $blog->alttext_ar  = $request->alttext_ar;

        if($blog->update()){
            $tags_en = $request->tags_en;
            $tags_ar = $request->tags_ar;
            for($i=0;$i< count($tags_en); $i++){
                $tag=new Tag;
                $tag->blog_id=$blog->id;
                $tag->lang=1;
                $tag->tag=$tags_en[$i]["tag"];
                $tag->save();
            }

            for($i=0;$i< count($tags_ar); $i++){
                $tag_ar=new Tag;
                $tag_ar->blog_id=$blog->id;
                $tag_ar->lang=2;
                $tag_ar->tag=$tags_ar[$i]["tag"];
                $tag_ar->save();
            }

            return response()->json(["success" =>true,"msg" =>"successfully updated"]);

        }

    }

    public function delete($id){
        $blog = Blog::where("id",$id)->first();
        if($blog){
            $old_tags = Tag::where("blog_id",$id)->get();
            foreach($old_tags as $old_tag)
            {
               $old_tag->delete();
            }
            $blog->delete();
            
        return response()->json(["success"=>true,"msg"=>"successfully deleted"]);

        }else{
            return response()->json(["success"=>false,"msg"=>"No blog to delete"]);

        }
    }
}
