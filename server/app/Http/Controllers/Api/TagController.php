<?php

namespace App\Http\Controllers\Api;

use App\Models\{Tag, Media, Transaction};
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    private $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->tag->newQuery()->orderBy('name');

        if (isset(request()->type)) {
            $tagIds = \DB::table('taggables')
                ->distinct()
                ->select('tag_id')
                ->where(
                    'taggable_type',
                    request()->type === 'media' ? Media::class : Transaction::class
                )
                ->pluck('tag_id');

            $query->whereIn('id', $tagIds);
        }

        return TagResource::collection($query->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\TagRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function create(TagRequest $request)
    {
        $tag = $this->tag->create($request->all());

        return new TagResource($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\TagRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TagRequest $request, int $id)
    {
        $tag = $this->tag->findOrFail($id);
        $tag->update($request->all());

        return new TagResource($tag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if ($this->tag->destroy($id)) {
            return response()->json(null, 204);
        }

        return response()->json(null, 404);
    }
}
