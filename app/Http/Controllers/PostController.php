<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::user();
        $response = [
            "msg" => 'this is all Posts',
            "code" => '200',
            "data" => $user->posts
        ];
        $code = 200;
    return response($response,$code);

        // return $user->posts;

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
        //
        $fields = $request->validate([
            'title'=>'required|max:255',
            'body'=>'required',
            'pinned'=>'required',
            'photo'=>['required',File::image()]
        ]);
        $image = $request->file('photo')->store('storage/postsCover');

        $post = Post::create([
            'title' => $fields['title'],
            'body' => $fields['body'],
            'pinned' => $fields['pinned'],
            'cover_image_url' => $image,
            'user_id'=>Auth::id()
        ]);
        $post->tags()->attach($request['tag']);

        If($post){
            $response = [
                "msg" => 'Post created successfuly',
                "code" => '201',
                "data" => $post
            ];
            $code = 201;
        }else{
            $response = [
                "msg" => 'Post is not created',
                "code" => '500',
                "data" => 'NO data'
            ];
            $code = 500;
        }

        return response($response,$code);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::findOrFail($id);

        If($post){
            $response = [
                "msg" => 'Post returned successfuly',
                "code" => '201',
                "data" => $post
            ];
            $code = 201;
        }else{
            $response = [
                "msg" => 'Post is not created',
                "code" => '500',
                "data" => 'NO data'
            ];
            $code = 500;
        }
        return response($response,$code);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //   //
        $post = Post::findOrFail($id);
        return $request;
        $request->validate([
            'title'=>'required|max:255',
            'body'=>'required',
            'pinned'=>'required',
        ]);

        If($post){
            if($request->hasFile('photo')){
                Storage::delete([$post->cover_image_url]);
                $image = $request->file('photo')->store('storage/postsCover');
                $post->update([
                    'title' => $request['title'],
                    'body' => $request['body'],
                    'pinned' => $request['pinned'],
                    'cover_image_url' => $image,
                ]);
            }else{
                $post->update([
                    'title' => $request['title'],
                    'body' => $request['body'],
                    'pinned' => $request['pinned'],
                ]);
                $post->tags()->attach($request['tag']);

            }
            $response = [
                "msg" => 'Post returned successfuly',
                "code" => '201',
                "data" => $post
            ];
            $code = 201;

        }else{
            $response = [
                "msg" => 'Post is not created',
                "code" => '500',
                "data" => 'NO data'
            ];
            $code = 500;
        }
        return response($response,$code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
        $post->delete();
        $response = [
            "msg" => 'post deleted softly',
            "code" => '201',
            "data" => 'NO data'
        ];
        return response($response,201);
    }
    public function deletedPosts(){

        $user = Auth::user();
        $posts = Post::where('user_id',$user->id)->onlyTrashed()->get();

         return Response($posts);

    }
    public function restorPost(){

        $user = Auth::user();
        $post = Post::where('user_id',$user->id)->onlyTrashed()->restore();
        return "done";

    }
}
