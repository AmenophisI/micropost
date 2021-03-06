<?php

namespace App\Http\Controllers;

class FavoritesController extends Controller
{
    // お気に入り登録するアクション
    public function store($id)
    {
        \Auth::user()->favorite($id);

        return back();
    }

    // お気に入り解除するsクション
    public function destroy($id)
    {
        \Auth::user()->unfavorite($id);

        return back();
    }
}
