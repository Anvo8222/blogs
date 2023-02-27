<?php

namespace App\Http\Controllers\Api\V1\Home;

use App\Http\Controllers\Controller;
use App\Models\V1\Client\ChapterModel;
use App\Models\V1\Client\CommentModel;
use App\Models\V1\Client\PostModel;
use Illuminate\Http\Request;

class ViewPostController extends Controller
{
  public function viewDetailPost($slug)
  {
    $post = PostModel::where("slug", "LIKE", "%" . $slug . "%")->first();
    $comments = CommentModel::where("post_id", $post->id)->paginate(10);
    $chapters = ChapterModel::where("post_id", $post->id)->paginate(2);

    // làm 3 api, 1 cái api lấy comment, 1 api lấy chapter, 
    return response()->json([
      'data' => $post,
      'chapters' => $chapters,
      'comment' => $comments,
      'status' => 'success',
    ], 200);
  }
}
