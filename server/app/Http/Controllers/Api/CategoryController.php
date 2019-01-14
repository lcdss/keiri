<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CategoryResource::collection(
            $this->category->whereIsRoot()->orderBy('name')->get()
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = $this->category->with('parent', 'children')->findOrFail($id);

        return new CategoryResource($category);
    }

    /**
     * Display the transactions tree structure.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tree()
    {
        return CategoryResource::collection(
            $this->category->orderBy('name')->get()->toTree()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function create(CategoryRequest $request)
    {
        $category = $this->category->create($request->all());

        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\CategoryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, int $id)
    {
        $category = $this->category->findOrFail($id);
        $category->update($request->all());

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if ($this->category->destroy($id)) {
            return response()->json(null, 204);
        }

        return response()->json(null, 404);
    }
}
