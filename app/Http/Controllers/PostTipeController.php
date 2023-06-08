<?php

namespace App\Http\Controllers;

use App\Models\PostTipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostTipeController extends Controller
{
    //
    public function index(Request $request)
    {
        $data = PostTipe::all();
        return view('posts_tipe', ['data' => $data]);
    }

    public function prosesGetAll(Request $request)
    {
        # code...
        $dataPost = PostTipe::all();
        return  $dataPost;
    }

    public function prosesAdd(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'kode_posts_tipe'     => 'required|min:2',
            'nama_posts_tipe'   => 'required|min:5'
        ]);

        if ($validator->fails()) {
          return response()->json(['errors'=>$validator->errors()], 422);
        }

        $tblPostsTipe = new PostTipe();
        $tblPostsTipe->kode_tipe = $request->kode_posts_tipe;
        $tblPostsTipe->nama_tipe = $request->nama_posts_tipe;
        $tblPostsTipe->save();

        return response()->json(['message'=> 'Success Add']);
    }

    public function prosesEdit(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'kode_posts_tipe'     => 'required|min:2',
            'nama_posts_tipe'   => 'required|min:5'
        ]);

        if ($validator->fails()) {
          return response()->json(['errors'=>$validator->errors()], 422);
        }

        $tblPostsTipe = PostTipe::find($request->oldid);
        $tblPostsTipe->kode_tipe = $request->kode_posts_tipe;
        $tblPostsTipe->nama_tipe = $request->nama_posts_tipe;
        $tblPostsTipe->save();

        return response()->json(['message'=> 'Success Edit']);
    }

    public function prosesDelete(Request $request)
    {
        $tblPostsTipe = PostTipe::find($request->oldid);
        $tblPostsTipe->delete();

        return response()->json(['message'=> 'Success Delete']);
    }

}
