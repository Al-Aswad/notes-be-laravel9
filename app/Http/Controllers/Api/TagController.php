<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return ResponseFormatter::success($tags, 'tags retrieved');
    }

    public function create(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image',
        ]);
        //save image
        if ($request->file('avatar')) {
            $avatar = $request->file('avatar');
            $date = date('Ymds');
            $avatarUrl = $date . $avatar->getClientOriginalName();
            $avatar->storeAs('public/tags/', $avatarUrl);


            $tag = Tag::create([
                'name' => $request->name,
                'avatar' => $avatarUrl,
            ]);

            return ResponseFormatter::success($tag, 'tag created');
        }

        $tag = Tag::create([
            'name' => $request->name,
        ]);


        return ResponseFormatter::success($tag, 'tag created');
    }

    public function show($id)
    {
        $tag = Tag::find($id);

        if ($tag) {
            return ResponseFormatter::success($tag, 'Tag found');
        }
        return ResponseFormatter::error('Tag not found', null, 404);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'avatar' => 'nullable|image',
        ]);

        $tag = Tag::find($id);

        if ($tag) {

            if($request->name){
                $tag->name = $request->name;
            }

            if ($request->avatar) {
                try {
                    Storage::move('public/tags/' . $tag->avatar, 'public/tags/deleted/' . $tag->avatar);
                    $avatar = $request->file('avatar');
                    $date = date('Ymds');
                    $avatarUrl = $date . $avatar->getClientOriginalName();
                    $avatar->storeAs('public/tags/', $avatarUrl);

                    $tag->avatar = $avatarUrl;
                } catch (\Throwable $th) {
                    abort(500, $th->getMessage());
                }
            }

            $tag->save();
            return ResponseFormatter::success($tag, 'Tag updated');
        }
        return ResponseFormatter::error('Tag not found', null, 404);
    }

    public function delete($id)
    {
        $tag = Tag::find($id);
        if ($tag) {
            $tag->delete();
            return ResponseFormatter::success($tag, 'Tag deleted');
        }
        return ResponseFormatter::error('Tag not found', null, 404);
    }
}
