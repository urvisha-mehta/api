<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Post::all(); //paginate pending
        return response()->json([
            'status' => true,
            'message' => 'All Post',
            'data' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request) //changes complete
    {
        $img = $request->image;
        $ext = $img->getClientOriginalExtension();
        $imageName = time() . '.' . $ext;
        $img->move(public_path() . '/uploads', $imageName);

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Post Created Successfully',
            'post' => $post
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Post::findOrFail($id);  //changes done

        return response()->json([
            'status' => true,
            'message' => 'Your Single Post',
            'data' => $data
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, string $id) //changes complete
    {
        $post = Post::select('id', 'image')->get();

        if ($request->image != '') {
            $path = public_path() . '/uploads';
            if ($post[0]->image != '' && $post[0]->image != null) {
                $old_file = $path . $post[0]->image;
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }
            $img = $request->image;
            $ext = $img->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            $img->move(public_path() . '/uploads', $imageName);
        } else {
            $imageName = $post->image;
        }

        $post = Post::findOrFail($id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Post Updated Successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) //changes complete
    {
        $post =  Post::findOrFail($id);

        $path = public_path("/uploads/") . $post->image;
        if (file_exists($path)) {
            @unlink($path);
        }
        $post->delete();

        return response()->json([
            'status' => true,
            'message' => 'Your Post Has Been Removed',
        ], 200);
    }
}
