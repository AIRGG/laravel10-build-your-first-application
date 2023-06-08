<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostTipe;
use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    //
    public function index(Request $request)
    {
        # code...
        $data = Post::all();
        $dataPostTipe = PostTipe::all();
        return view('posts', ['data' => $data, 'dataPostTipe' => $dataPostTipe]);
    }

    public function prosesGetAll(Request $request)
    {
        # code...
        $dataPost = Post::all();
        $dataPost = Post::select('posts.*', 'posts_tipe.kode_tipe', 'posts_tipe.nama_tipe')
                        ->leftJoin('posts_tipe', 'posts.id_tipe', '=', 'posts_tipe.id')
                        ->get();
        return  $dataPost;
    }
    public function prosesAdd(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tipe'     => 'required',
            'title'     => 'required|min:5',
            'content'   => 'required|min:10'
        ]);

        if ($validator->fails()) {
          return response()->json(['errors'=>$validator->errors()], 422);
        }

        // -- image
        $filename = '';
        if($request->image->getSize() > 0) {
            $filename = $request->image->hashName();
            $request->image->move(public_path('posts'), $filename);
        }

        $tblPosts = new Post();
        $tblPosts->id_tipe = $request->tipe;
        $tblPosts->title = $request->title;
        $tblPosts->content = $request->content;
        $tblPosts->image = $filename;
        $tblPosts->save();

        return response()->json(['message'=> 'Success Add']);
    }
    public function prosesEdit(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'image'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tipe'     => 'required',
            'title'     => 'required|min:5',
            'content'   => 'required|min:10',
            'fileold'     => 'required',
        ]);

        if ($validator->fails()) {
          return response()->json(['errors'=>$validator->errors()], 422);
        }

        // -- image
        $filename = $request->fileold;
        if($request->image && $request->image->getSize() > 0) {
            $filename = $request->image->hashName();
            $request->image->move(public_path('posts'), $filename);
        }

        $tblPosts = Post::find($request->oldid);
        $tblPosts->id_tipe = $request->tipe;
        $tblPosts->title = $request->title;
        $tblPosts->content = $request->content;
        $tblPosts->image = $filename;
        $tblPosts->save();

        return response()->json(['message'=> 'Success Edit']);
    }
    public function prosesDelete(Request $request)
    {
        $form_oldid = $request->oldid;
        $tblPosts = Post::find($form_oldid);
        $tblPosts->delete();

        return response()->json(['message'=> 'Success Delete']);
    }
}
