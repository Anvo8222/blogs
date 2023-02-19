<?php

namespace App\Http\Controllers\Api\V1\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\V1\Client\CategoryModel;
use App\Http\Requests\Backend\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = CategoryModel::orderByDesc("id")->get(["id", "name", "description", "slug"]);
        return response()->json([
            'message' => 'Lấy danh mục thành công!',
            'data' => $categories
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
    public function store(CategoryRequest $request)
    {
        $category = new CategoryModel();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->slug = $request->slug;
        if ($category->save()) {
            return response()->json([
                'message' => 'Thêm danh mục thành công!',
                'data' => $category
            ], 201);
        } else {
            return response()->json([
                'message' => 'Lỗi, Vui lòng thử lại!'
            ], 500);
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = CategoryModel::findOrFail($id);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->slug = $request->slug;

        if ($category->update()) {
            return response()->json([
                'message' => 'Cập nhật danh mục thành công!',
                'data' => $category
            ], 201);
        } else {
            return response()->json([
                'message' => 'Lỗi, Vui lòng thử lại!',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = CategoryModel::findOrFail($id);
        if ($category->delete()) {
            return response()->json([
                'message' => 'Xoá danh mục thành công!'
            ], 201);
        } else {
            return response()->json([
                'message' => 'Lỗi, Vui lòng thử lại!',
            ], 500);
        }
    }
}
