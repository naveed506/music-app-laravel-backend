<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Illuminate\Database\Eloquent\Builder;

class TestController extends Controller
{
    public function index()
    {


        //Use of Has 
        // $users = User::with('posts')->get();
        // dd($users);


        //Use of whereHas
        // $users = User::get();
        // $new = User::whereHas('posts', function ($p) {
        //     $p->where('active', 'active');
        // })->get();




        $users = User::with('posts')->whereHas('posts', function (Builder $query) {
            $query->where('active', '=', 1);
        })->get();
        dd($users);

        //$users = User::withCount('posts')->get();

        dd($users);
    }
}
