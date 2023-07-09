<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Post;

use Illuminate\Http\Request;

class StatsController extends Controller
{
    //
    public function stats(){
        $usersCount = User::count();
        $postsCount = Post::count();
        $usersWithNoPostsCount = User::doesntHave('posts')->count();


        return response()->json([
            'usersCount' => $usersCount,
            'postsCount' => $postsCount,
            'usersWithNoPostsCount' => $usersWithNoPostsCount,
        ]);
    }
}
