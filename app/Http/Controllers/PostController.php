<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Session;
use Purifier;
use Image;
use Storage;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $posts = Post::orderBy('id', 'desc')->paginate(2);
        return view('posts.index')->withPosts($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate the data
        $this->validate($request, array(
            'title' => 'required|max:255',
            'slug' => 'required|alpha_dash|min:5|max:255',
            'body' => 'required',
            'featured_image' => 'image|max:10000|mimes:jpeg,png,jpg'
        ));


        //store
        $post = new Post;

        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->body = Purifier::clean($request->body);
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $image_resize = Image::make($image->getRealPath());
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/'. $filename);
            Image::make($image)->pixelate(12)->resize(300,200)->save($location);
            $post->image = $filename;
$location = public_path('images/'.$filename);
try {
    \Tinify\setKey("MK7-7qz_HWRYsFpseSd2wNx3rrqkc8bL");
    $source = \Tinify\fromFile($location);
    $source->toFile($location);
} catch(\Tinify\AccountException $e) {
    return redirect('images/create')->with('error', $e->getMessage());
} catch(\Tinify\ClientException $e) {
    return redirect('images/create')->with('error', $e->getMessage());
} catch(\Tinify\ServerException $e) {
    return redirect('images/create')->with('error', $e->getMessage());
} catch(\Tinify\ConnectionException $e) {
    return redirect('images/create')->with('error', $e->getMessage());
} catch(Exception $e) {
    return redirect('images/create')->with('error', $e->getMessage());
}


        }
        $post->save();
        Session::flash('success', 'The post saved successfully !!');


        //redirect
        return redirect()->route('posts.show', $post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->withPost($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        return view('posts.edit')->withPost($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

         $this->validate($request, array(
            'title' => 'required|max:255',
            'slug' => "required|alpha_dash|min:5|max:255|unique:posts,slug,$id",
            'body' => 'required',
            'featured_image' => 'image'
        ));

        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->slug = $request->input('slug');
        $post->body = Purifier::clean($request->input('body'));
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/'. $filename);
            Image::make($image)->resize(800,400)->save($location);
            $oldFilename = $post->image;
            $post->image = $filename;
            Storage::delete($oldFilename);
        }

        $post->save();
        Session::flash('success','successfully saved');
        return redirect()->route('posts.show', $post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $post = Post::find($id);
       Storage::delete($post->image);
       $post->delete();
       Session::flash('success', 'Post successfully deleted');
       return redirect()->route('posts.index'); 
    }
}
