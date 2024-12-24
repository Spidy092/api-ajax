<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Data['posts'] = Post::all();

        return response()->json([
            "status"=> true,
            "message"=> "user created succesfully",
           'data' => $Data,
        ], 200);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'description'=> 'required',
                    'image'=> 'required|mimes:png,jpg,jped,gif'
                ]
                );

                if($validateUser->fails()){
                    return response()->json([
                        'status'=> false,
                        'message' => "validation Error ",
                        "errors"=> $validateUser->errors()->all()
                    ],401);
                };

                $img = $request->file("image");
                $ext = $img->extension();
                $imageName = time().".".$ext;
                $img->move(public_path()."/uploads", $imageName);

                $post = Post::create([
                    'title' => $request->title,
                    'description'=> $request->description,
                    'image'=> $imageName,
                ]);

                return response()->json([
                    "status"=> true,
                    "message"=> "post created succesfully",
                    'post' => $post,
                ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $post = Post::select([
                'id',
                'title',
                'description',
                'image',
            ])->where(['id'=> $id])->get();
    
            return response()->json([
                "status"=> true,
                "message"=> "user fetched succesfully",
                'post' => $post,
            ], 200);

        } catch (\Exception $e) {   
            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'description'=> 'required',
                    'image'=> 'required|mimes:png,jpg,jped,gif'
                ]
                );

                if($validateUser->fails()){
                    return response()->json([
                        'status'=> false,
                        'message' => "validation Error ",
                        "errors"=> $validateUser->errors()->all()
                    ],401);
                };

                $post =Post::select('id', 'image')->get();
                if($request->image != ""){
                    $path = public_path().'/uploads';

                    if($post->image != '' && $post->image != null){
                        $old_file = $path.$post->image;
                        if(file_exists($old_file)){
                            unlink($old_file);
                        }
                    }

                    $img = $request->file("image");
                $ext = $img->extension();
                $imageName = time().".".$ext;
                $img->move(public_path()."/uploads", $imageName);

                }else {
                    $imageName = $post->img;
                }

                $img = $request->file("image");
                $ext = $img->extension();
                $imageName = time().".".$ext;
                $img->move(public_path()."/uploads", $imageName);

                $post = Post::where(["id"== $id])->update([
                    'title' => $request->title,
                    'description'=> $request->description,
                    'image'=> $imageName,
                ]);

                return response()->json([
                    "status"=> true,
                    "message"=> "post updated succesfully",
                    'post' => $post,
                ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    { 
        $imagepath = Post::select('image')->where('id', $id)->get();
        $filepath = public_path().'/uploads'.$imagepath[0]['image'];


        $post = Post::where('id', $id)->delete();

        return response()->json([
            'status'=> true,
            'message'=> 'Post deleted Successfully!',
            'post'=> $post,
        ],200);
    }
}
