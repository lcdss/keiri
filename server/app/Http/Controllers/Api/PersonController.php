<?php

namespace App\Http\Controllers\Api;

use App\Models\Person;
use App\Http\Requests\PersonRequest;
use App\Http\Resources\PersonResource;

class PersonController extends Controller
{
    private $person;

    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PersonResource::collection($this->person->orderBy('name')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\PersonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function create(PersonRequest $request)
    {
        $person = $this->person->create($request->all());

        return new PersonResource($person);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\PersonRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PersonRequest $request, int $id)
    {
        $person = $this->person->findOrFail($id);
        $person->update($request->all());

        return new PersonResource($person);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if ($this->person->destroy($id)) {
            return response()->json(null, 204);
        }

        return response()->json(null, 404);
    }
}
