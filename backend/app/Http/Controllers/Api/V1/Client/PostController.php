<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\V1\Client\PostModel;
use App\Http\Requests\Client\Post\PostRequest;
use Illuminate\Support\Str;
use App\Models\V1\Backend\UserModel;

class PostController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    // $post = PostModel::with('categories')->paginate(2);
    $posts = PostModel::with([
      'categories' => function ($query) {
        $query->select('categories.id', 'categories.name');
      }
    ])->where('posts.status', 'active')->paginate(10, ['id', 'name', 'image', 'description', 'status']);

    return response()->json([
      'status-code' => 200,
      'data' => $posts
    ], 200);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  // PostRequest
  public function store(PostRequest $request)
  {
    $token = $request->bearerToken();
    $post = new PostModel();
    $user = UserModel::whereHas('session_users', function ($query) use ($token) {
      $query->where('session_users.token', '=', $token);
    })->first();

    // dd($request->all());

    if ($user) {
      if ($request->hasFile('image')) {
        $image_name = time() . $request->image->getClientOriginalName();
        $pathActive = public_path('upload/backend/posts');
        if (!file_exists($pathActive)) {
          mkdir($pathActive, 0777, true);
        }
        $post->image = $image_name;
      }
      $post->name = $request->name;
      $post->author = $request->nameAuthor;
      $post->type = (int)$request->type;
      $post->view = 0;
      $post->description = $request->description;
      $post->is_done = (int)$request->is_done;
      $post->user_id = $user->id;
      $post->slug = Str::slug($request->name, "-");
      $file = $request->image;

      if ($post->save()) {
        if ($request->hasFile('image')) {
          $file = $request->image;
          $file->move($pathActive, $image_name);
        }
        $post->categories()->attach($request->categories);
        $post->categories = $request->categories;
        return response()->json([
          'data' => $post,
          'status' => 'success',
          'message' => 'Tạo bài viết thành công!',
        ], 200);
      } else {
        return response()->json([
          'status' => 'error',
          'message' => 'Tạo bài viết không thành công!',
        ], 401);
      }
    } else {
      return response()->json([
        'status' => 'error',
        'message' => 'Không hợp lệ!',
      ], 401);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  public function getListPostUser(Request $request)
  {
    //token
    $token = $request->bearerToken();
    $userId = UserModel::whereHas('session_users', function ($query) use ($token) {
      $query->where('session_users.token', '=', $token);
    })->first(['id'])->id;
    $posts = PostModel::with([
      'categories' => function ($query) {
        $query->select('categories.id');
      }
    ])
      ->where('user_id', $userId)
      ->withCount('chapters')
      ->get(['id', 'name', 'author', 'image', 'type', 'description', 'is_done', 'slug', 'user_id']);

    return response()->json([
      'data' => $posts,
      'status' => 'success',
    ], 200);
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
    $token = $request->bearerToken();
    $userId = UserModel::whereHas('session_users', function ($query) use ($token) {
      $query->where('session_users.token', '=', $token);
    })->first(['id'])->id;
    $post = PostModel::where('user_id', $userId)->where('id', $id)
      ->first(['id', 'name', 'author', 'image', 'type', 'description', 'is_done', 'slug', 'user_id']);
    if ($userId) {
    }
    if ($post) {
      if ($request->hasFile('image')) {
        $image_name = time() . $request->image->getClientOriginalName();
        $pathActive = public_path('upload/backend/posts');
        if (!file_exists($pathActive)) {
          mkdir($pathActive, 0777, true);
        }
        $getImageInPost = $post->image;
        $post->image = $image_name;
      }
      $post->name = $request->name;
      $post->author = $request->nameAuthor;
      $post->type = (int)$request->type;
      $post->description = $request->description;
      $post->is_done = (int)$request->is_done;
      $post->slug = Str::slug($request->name, "-");
      $file = $request->image;

      if ($post->update()) {
        if ($request->hasFile('image')) {
          $file = $request->image;
          $file->move($pathActive, $image_name);
          if (file_exists($pathActive . "/" . $getImageInPost)) {
            unlink($pathActive . "/" . $getImageInPost);
          }
        }
        $post->categories()->sync($request->categories);
        return response()->json([
          'data' => $post,
          'status' => 'success',
          'message' => 'Cập nhật bài viết thành công!',
        ], 200);
      } else {
        return response()->json([
          'status' => 'error',
          'message' => 'Cập nhật bài viết thất bại!',
        ], 401);
      }
    } else {
      return response()->json([
        'status' => 'error',
        'message' => 'Không hợp lệ!',
      ], 401);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, $id)
  {
    $token = $request->bearerToken();
    $user = UserModel::whereHas('session_users', function ($query) use ($token) {
      $query->where('session_users.token', '=', $token);
    })->first(["id"]);
    $post = PostModel::where("id", $id)->where("user_id", $user->id)->first(['id', 'name', 'image']);
    $postImage = $post->image;

    $deletePost = $post->delete();
    if ($deletePost > 0) {
      $pathActive = public_path('upload/backend/posts');
      if (file_exists($pathActive . "/" . $postImage)) {
        unlink($pathActive . "/" . $postImage);
      }
      return response()->json([
        'message' => "Xoá thành công!"
      ], 201);
    } else {
      return response()->json([
        'message' => "Xoá thất bại!"
      ], 400);
    }
  }
}
