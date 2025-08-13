<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try
        {
            $categories = Category::get();
            return response()->json(['status'=>count($categories)>0, 'data'=>$categories]);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessag()],500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'category_name' => 'required|string|max:50|unique:categories',
                'status' => 'required|in:Active,Inactive',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'The given data was invalid', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $category = new Category();
            $category->category_name = $request->category_name;
            $category->status = $request->status;
            $category->save();
            return response()->json(['status'=>true, 'category_id'=>intval($category->id), 'message'=>'Successfully a category has been added']);

        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessag()],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return response()->json(['status'=>true, 'category'=>$category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'category_name' => 'required|string|max:50|unique:categories,category_name,' . $category->id,
                'status' => 'required|in:Active,Inactive',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'The given data was invalid', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $category->category_name = $request->category_name;
            $category->status = $request->status;
            $category->update();
            return response()->json(['status'=>true, 'category_id'=>intval($category->id), 'message'=>'Successfully the category has been updated']);

        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessag()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try
        {
            $category->delete($category);
            return response()->json(['status'=>true, 'message'=>"Successfully the category has been deleted"]);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessag()],500);
        }
    }
}
