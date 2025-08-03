<?php

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reddit-posts', function (Request $request) {
    // try to get the decoded posts array from cache for up to 1 hour
    // if it's not in cache yet, read the JSON file and decode it
    $allPosts = Cache::remember('reddit_posts', 60 * 60, function () {
        $json = Storage::disk('public')->get('reddit_posts.json');
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    });

    $perPage = 9;

    // what page the user is on (defaults to 1)
    $page    = $request->query('page', 1);

    // calculate where to start slicing the array
    $offset  = ($page - 1) * $perPage;

    // pull just the subset of posts for this page
    $items = array_slice($allPosts, $offset, $perPage, true);

    // wrap that slice in a LengthAwarePaginator so we can use ->links()
    $paginator = new LengthAwarePaginator(
        $items,
        count($allPosts),
        $perPage,
        $page,
        ['path' => url('/reddit-posts')]
    );

    // render the Blade view, passing in our paginator instance
    return view('reddit.posts', ['posts' => $paginator]);
});
