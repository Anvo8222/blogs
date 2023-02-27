<?php

namespace App\Http\Controllers\Api\V1\Home;

use App\Http\Controllers\Controller;
use App\Models\V1\Client\PostModel;
use Illuminate\Http\Request;

class HomePostController extends Controller
{
  //
  public function slider()
  {
    $posts = PostModel::select('id', 'slug', 'image', 'name', 'is_done', 'type')
      ->withCount('categories as categories_count')
      ->withCount('chapters as chapters_count')
      ->orderBy('categories_count', 'desc')
      ->orderBy('chapters_count', 'desc')
      ->take(10)
      ->get();
    return response()->json([
      'data' => $posts,
      'status' => 'success',
    ], 200);
  }
  public function top()
  {
    $posts = PostModel::orderBy('view', 'desc')
      ->take(10)
      ->get(['id', 'slug', 'view', 'image', 'name']);
    return response()->json([
      'data' => $posts,
      'status' => 'success',
    ], 200);
  }
  public function new()
  {
    $posts = PostModel::orderBy('created_at', 'desc')
      ->take(10)
      ->get(['id', 'slug', 'view', 'image', 'name', 'created_at']);
    return response()->json([
      'data' => $posts,
      'status' => 'success',
    ], 200);
  }
  public function random()
  {
    $posts = PostModel::inRandomOrder()
      ->take(10)
      ->get(['id', 'slug', 'view', 'image', 'name']);
    return response()->json([
      'data' => $posts,
      'status' => 'success',
    ], 200);
  }
  public function list()
  {
    $posts = PostModel::take(10)
      ->get(['id', 'slug', 'view', 'image', 'name']);
    return response()->json([
      'data' => $posts,
      'status' => 'success',
    ], 200);
  }

  public function shortBlog()
  {
    $posts = PostModel::whereHas('categories', function ($query) {
      $query->where('name', 'LIKE', '%blog%');
    }, '=', 1)->get(['id', 'slug', 'view', 'image', 'name']);
    return response()->json([
      'data' => $posts,
      'status' => 'success',
    ], 200);
  }
}
