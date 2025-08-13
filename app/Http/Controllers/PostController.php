<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
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
            $posts = Post::with('category')->latest()->get();
            return response()->json(['status'=>count($posts)>0, 'data'=>$posts]);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
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
                'category_id' => 'required|integer|exists:categories,id',
                'title' => 'required|string|max:50|unique:posts',
                'status' => 'required|in:Pending,Wait,Active',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'The given data was invalid', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $post = new Post();
            $post->title = $request->title;
            $post->category_id = $request->category_id;
            $post->description = $request->description;
            $post->status = $request->status;
            $post->save();
            return response()->json(['status'=>true, 'post_id'=>intval($post->id), 'message'=>'Successfully a post has been added']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return response()->json(['status'=>true, 'data'=>$post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|integer|exists:categories,id',
                'title' => 'required|string|max:50|unique:posts,title,' . $post->id,
                'status' => 'required|in:Pending,Wait,Active',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'The given data was invalid', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $post->title = $request->title;
            $post->category_id = $request->category_id;
            $post->description = $request->description;
            $post->status = $request->status;
            $post->update();

            return response()->json(['status'=>true, 'post_id'=>intval($post->id), 'message'=>'Successfully the post has been updated']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        try
        {
            $post->delete();
            return response()->json(['status'=>true, 'message'=>"Successfully the post has been deleted"]);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
}
