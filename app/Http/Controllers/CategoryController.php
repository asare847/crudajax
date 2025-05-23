<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use DataTables;
class CategoryController extends Controller
{
    
    public function index(Request $request){
        $categories = Category::select('id','name','type')->latest();
        if($request->ajax()){
            return DataTables::of($categories)
            ->addColumn('action',function($row){
               return '<a href="javascript:void(0)" class="btn btn-sm btn-info editButton" data-id="'.$row->id.'">edit</a>
                      <a href="javascript:void(0)" class="btn btn-sm btn-danger delButton" data-id="'.$row->id.' ">delete</a>
                      
                      '
               ;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
    public function create(){
        return view('categories.create');
    }

    public function createPoint(){
        return view('point.create');
    }

    public function store(Request $request){
        if($request->category_id !== null){
            $category = Category::find($request->category_id);
            if (! $category) {
              abort(404);  
            }
            $category->update([
                'name'=> $request->name,
                'type'=> $request->type
            ]);
            return response()->json([
                'success'=> 'Category Updated Successfully'
            ],201);
        }else{
            $request->validate([
                'name'=> 'required',
                'type'=> 'required',
            ]);
    
            
            Category::create([
                'name'=> $request->name,
                'type'=> $request->type
            ]);
            return response()->json([
                'success'=> 'Category Saved Successfully'
            ],201);
        }
      
        
    }
    
    public function edit($id){
        $category = Category::find($id);
        if(! $category){
            abort(404);
        }
        return $category;
    }

    public function destroy($id){
        $category = Category::find($id);
        if (! $category) {
            abort(404);
        }
         $category->delete();
         return response()->json([
            'success'=> 'Category Deleted'
         ],201);
    }

   
}
