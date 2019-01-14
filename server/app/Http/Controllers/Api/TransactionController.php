<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\{TransactionRequest, MediaRequest};
use App\Http\Resources\{TransactionResource, MediaResource};

class TransactionController extends Controller
{
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = QueryBuilder::for(Transaction::class)
            ->defaultSort('-id')
            ->allowedFilters('paid_at', 'payment_method', 'company_id', 'person_id')
            ->allowedIncludes('person', 'company', 'category');

        return TransactionResource::collection($query->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $query = $this->transaction->newQuery();

        if (request()->has('include')) {
            $query->with(...explode(',', request()->include));
        }

        return new TransactionResource($query->where('id', $id)->firstOrFail());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\TransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function create(TransactionRequest $request)
    {
        $transaction = $this->transaction->create($request->except('tags'));
        $transaction->tags()->attach($request->tags);

        return new TransactionResource($transaction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\TransactionRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionRequest $request, int $id)
    {
        $transaction = $this->transaction->findOrFail($id);
        $transaction->update($request->except('tags'));
        $transaction->tags()->sync($request->tags);

        return new TransactionResource($transaction);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $transaction = $this->transaction->findOrFail($id);
        $transaction->clearMediaCollection('transactions');
        $transaction->delete();

        return response()->json(null, 204);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\MediaRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createFile(MediaRequest $request, int $id)
    {
        $transaction = $this->transaction->findOrFail($id);
        $media = $transaction->addMedia($request->file)->toMediaCollection('transactions');
        $media->tags()->sync($request->tag_id);

        return new MediaResource($media);
    }

    /**
     * Remove the specified media resource from storage.
     *
     * @param  int  $id
     * @param  int  $fileId
     * @return \Illuminate\Http\Response
     */
    public function destroyFile(int $id, int $fileId)
    {
        $this->transaction->findOrFail($id)->media()->findOrFail($fileId)->delete();

        return response()->json(null, 204);
    }

    /**
     * Sync tags with the specified media resource from storage.
     *
     * @param  int  $id
     * @param  int  $fileId
     * @return \Illuminate\Http\Response
     */
    public function syncFileTags(int $id, int $fileId, Request $request)
    {
        $this->transaction
            ->findOrFail($id)
            ->media()
            ->findOrFail($fileId)
            ->tags()
            ->sync($request->tags);

        return response()->json(null, 204);
    }
}
