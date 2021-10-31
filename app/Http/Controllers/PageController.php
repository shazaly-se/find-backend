<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Pagefeature;
class PageController extends Controller
{

    public function index(){
        $pages = Page::get(array("pages.*"));
        return response()->json(["pages" =>$pages]);

    }


    public function store(Request $request)
    {
     
        $page = new Page;
        $page->purpose  = $request->purpose;
        $page->location  = $request->location;
        $page->propertytype  = $request->propertytype;
        $page->price_from  = $request->price_from;
        $page->price_to  = $request->price_to;
        $page->bath_room  = $request->bath_room;
        $page->bed_room  = $request->bed_room;
        $page->rent_frequence  = $request->rent_frequence;
        $page->description_en  = $request->description_en;
        $page->description_ar  = $request->description_ar;
        $page->keyphrases_en  = $request->focuskeyphrases_en;
        $page->keyphrases_ar  = $request->focuskeyphrases_ar;
        $page->seotitle_en  = $request->seotitle_en;
        $page->seotitle_ar  = $request->seotitle_ar;
        $page->meta_en  = $request->metadescription_en;
        $page->meta_ar  = $request->metadescription_ar;
        if($request->get('image'))
        {
           $image = $request->get('image');
           $name =  $request->alttext_en.'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
           \Image::make($request->get('image'))->save(public_path('uploads/pages/').$name);
           $page->image=$name;
         }

         if($request->get('image_ar'))
         {
            $image_ar = $request->get('image_ar');
            $name_ar = $request->alttext_ar.'.' . explode('/', explode(':', substr($image_ar, 0, strpos($image_ar, ';')))[1])[1];
            \Image::make($request->get('image_ar'))->save(public_path('uploads/pages/').$name_ar);
            $page->image_ar=$name_ar;
          }

         $page->alt_text  = $request->alttext_en;
         $page->alt_text_ar  = $request->alttext_ar;

        if($page->save()){

            $selectedfeature = $request->get('selectedfeature');
            for($i=0;$i< count($selectedfeature); $i++){
                $pagefeature=new Pagefeature;
                $pagefeature->page_id=$page->id;
                $pagefeature->feature_id=$selectedfeature[$i]["value"];
                 $pagefeature->save();
            }
    
              
            return response()->json(["success" =>true,"msg" =>"successfully added"]);

            
            //      $tag->save();
            }
    }

    public function edit($id){
        
        $page = Page::where("pages.id",$id)
        ->join("propertytypes","propertytypes.id","=","pages.propertytype")
        ->first(array("pages.*","propertytypes.typeName_en","propertytypes.typeName_ar"));
         $pagefeatures_en = Pagefeature::join("features","features.id","=","pagefeatures.feature_id")
         ->where("page_id",$id)->get(array("features.id as value","features.feature_en as label"));

         $pagefeatures_ar = Pagefeature::join("features","features.id","=","pagefeatures.feature_id")
         ->where("page_id",$id)->get(array("features.id as value","features.feature_ar as label"));

      

        return response()->json(["page"=>$page,"pagefeatures_en" =>$pagefeatures_en,"pagefeatures_ar" =>$pagefeatures_ar]);
    }

    public function update(Request $request , $id){
       // return $request->get('image');
        $old_pagefeature = Pagefeature::where("page_id",$id)->get();
        foreach($old_pagefeature as $old_pagfet)
        {
           $old_pagfet->delete();
        }

        $page =  Page::where("id",$id)->first();
        $page->purpose  = $request->purpose;
        $page->location  = $request->location;
        $page->propertytype  = $request->propertytype;
        $page->price_from  = $request->price_from;
        $page->price_to  = $request->price_to;
        $page->bath_room  = $request->bath_room;
        $page->bed_room  = $request->bed_room;
        $page->rent_frequence  = $request->rent_frequence;
        $page->description_en  = $request->description_en;
        $page->description_ar  = $request->description_ar;
        $page->keyphrases_en  = $request->focuskeyphrases_en;
        $page->keyphrases_ar  = $request->focuskeyphrases_ar;
        $page->seotitle_en  = $request->seotitle_en;
        $page->seotitle_ar  = $request->seotitle_ar;
        $page->meta_en  = $request->metadescription_en;
        $page->meta_ar  = $request->metadescription_ar;

        if($request->get('image'))
        {
        
           $image = $request->get('image');
           $name =  $request->alttext_en.'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
           \Image::make($request->get('image'))->save(public_path('uploads/pages/').$name);
           $page->image=$name;
         }

         if($request->get('image_ar'))
         {
            $image_ar = $request->get('image_ar');
            $name_ar = $request->alttext_ar.'.' . explode('/', explode(':', substr($image_ar, 0, strpos($image_ar, ';')))[1])[1];
            \Image::make($request->get('image_ar'))->save(public_path('uploads/pages/').$name_ar);
            $page->image_ar=$name_ar;
          }

         $page->alt_text  = $request->alttext_en;
         $page->alt_text_ar  = $request->alttext_ar;

        if($page->update()){



            $selectedfeature = $request->get('selectedfeature');
            for($i=0;$i< count($selectedfeature); $i++){
                $pagefeature=new Pagefeature;
                $pagefeature->page_id=$page->id;
                $pagefeature->feature_id=$selectedfeature[$i]["value"];
                 $pagefeature->save();
            }
    
              
            return response()->json(["success" =>true,"msg" =>"successfully added"]);

            
            //      $tag->save();
            }
    }

    public function delete($id) {
        $old_pagefeature = Pagefeature::where("page_id",$id)->get();
        foreach($old_pagefeature as $old_pagfet)
        {
           $old_pagfet->delete();
        }

      $page = Page::where('id',$id)->first();
      $page->delete();
      return response()->json(["success" =>true,"msg" =>"successfully deleted"]);
    }
}
