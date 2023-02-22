<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use App\Models\V1\Backend\UserModel;
use Illuminate\Http\Request;
use App\Models\V1\Client\ChapterModel;
use App\Models\V1\Client\PostModel;
use App\Http\Requests\Client\Post\ChapterRequest;
use Illuminate\Database\QueryException;
use Str;

class ChapterController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
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
  private function getUserIdFromToken($token)
  {
    $userId = UserModel::whereHas('session_users', function ($query) use ($token) {
      $query->where('session_users.token', '=', $token);
    })->first(['id'])->id;
    return $userId;
  }
  public function checkPostIsOfUser(Request $request, $id)
  {
    $token = $request->bearerToken();
    $userId = $this->getUserIdFromToken($token);
    $postOfUser = PostModel::where("user_id", $userId)->where('id', $id)->first(["id"]);
    if ($postOfUser) {
      return response()->json([], 204);
    } else {
      return response()->json([
        'message' => "Bạn không có quyền truy cập vào bài viết này!"
      ], 403);
    }
  }
  public function checkChapterIsOfUser(Request $request, $id)
  {
    $token = $request->bearerToken();
    $userId = $this->getUserIdFromToken($token);
    $chapter = ChapterModel::whereHas("posts", function ($query) use ($userId) {
      $query->where('posts.user_id', $userId);
    })
      ->where('id', $id)
      ->first();
    if ($chapter) {
      return response()->json([], 204);
    } else {
      return response()->json([
        'message' => "Bạn không có quyền truy cập vào chương này!"
      ], 403);
    }
  }
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ChapterRequest $request, $id)
  {
    $token = $request->bearerToken();

    // $userId = UserModel::whereHas('session_users', function ($query) use ($token) {
    //   $query->where('session_users.token', '=', $token);
    // })->first(['id'])->id;
    $userId = $this->getUserIdFromToken($token);

    $postOfUser = PostModel::where("user_id", $userId)->where('id', $id)->first(["id", "name"]);

    if ($postOfUser) {
      //check chapter title has in chapter of post
      $chapter = ChapterModel::whereHas('posts', function ($query) use ($postOfUser) {
        $query->where('posts.id', $postOfUser->id);
      })->where('title', $request->title)->first(["id"]);
      if ($chapter) {
        return response()->json([
          'status' => 'error',
          'message' => "Một bài viết không thể có nhiều chương trùng tiêu đề, Vui lòng nhập lại!"
        ], 400);
      } else {
        $chapter = new ChapterModel();
        $chapter->chapter_number = (int)$request->chapterNumber;
        $chapter->title = $request->title;
        $chapter->content = $request->content;
        $chapter->slug_chapter = Str::slug($request->title, "-") . "-" . $postOfUser->id;
        $chapter->post_id = $postOfUser->id;
        if ($chapter->save()) {
          return response()->json([
            'data' => $chapter,
            'status' => 'success',
            'message' => "Thêm chương cho " . $postOfUser->name . " thành công"
          ], 200);
        } else {
          return response()->json([
            'status' => 'error',
            'message' => "Thêm chương cho " . $postOfUser->name . "không thành công"
          ], 400);
        }
      }
    } else {
      return response()->json([
        'status' => 'error',
        'message' => "Bạn không có quyền thêm chương cho bài viết này!"
      ], 401);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request, $id)
  {
    // show list chapter of post
    $token = $request->bearerToken();
    // $userId = UserModel::whereHas('session_users', function ($query) use ($token) {
    //   $query->where('session_users.token', '=', $token);
    // })->first(['id'])->id;
    $userId = $this->getUserIdFromToken($token);

    $chapterPost = PostModel::with([
      "chapters"
    ])->where("user_id", $userId)
      ->where("id", $id)
      ->first(["id", "name"]);

    if ($chapterPost) {
      return response()->json([
        'data' => $chapterPost,
        'status' => 'success',
      ], 200);
    } else {
      return response()->json([
        'status' => 'error',
        'message' => 'Bạn không có quyền xem chapter của bài viết này!',
      ], 400);
    }
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

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(ChapterRequest $request, $id)
  {
    $token = $request->bearerToken();
    // $userId = UserModel::whereHas('session_users', function ($query) use ($token) {
    //   $query->where('session_users.token', '=', $token);
    // })->first(['id'])->id;
    $userId = $this->getUserIdFromToken($token);
    $chapter = ChapterModel::whereHas("posts", function ($query) use ($userId) {
      $query->where('posts.user_id', $userId);
    })
      ->where('id', $id)
      ->first();
    if ($chapter) {
      $chapter->chapter_number = $request->chapterNumber;
      $chapter->title = $request->title;
      $chapter->content = $request->content;
      $chapter->slug_chapter = Str::slug($request->title, "-") . "-" . $chapter->posts->id;
      try {
        if ($chapter->update()) {
          return response()->json([
            'data' => $chapter,
            'status' => 'success',
            'message' => "cập nhật chương cho " . $chapter->posts->name . " thành công"
          ], 200);
        }
      } catch (QueryException $e) {
        return response()->json([
          'status' => 'error',
          'message' => "cập nhật không thành công, vui lòng kiểm tra lại một số lỗi sau: Một bài viết không thể có nhiều chương trùng tiêu đề...)"
        ], 400);
      }
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
    // $userId = UserModel::whereHas('session_users', function ($query) use ($token) {
    //   $query->where('session_users.token', '=', $token);
    // })->first(['id'])->id;
    $userId = $this->getUserIdFromToken($token);

    $chapter = ChapterModel::whereHas("posts", function ($query) use ($userId) {
      $query->where('posts.user_id', $userId);
    })
      ->where('id', $id)
      ->first();
    if ($chapter) {
      $deletePost = $chapter->delete();
      if ($deletePost > 0) {
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
}
