<?php

namespace App\Http\Controllers;

use App\Models\RecipesCatagory;
use App\Http\Controllers\Controller;
use App\Http\Resources\RecipesCatagoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class RecipesCatagoryController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $rec = QueryBuilder::for(RecipesCatagory::class)
        ->allowedFilters([
            AllowedFilter::exact('name'),
        ])
        ->paginate()
        ->appends(request()->query());
        
        return RecipesCatagoryResource::collection($rec);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'description' => 'max:255',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $rec = RecipesCatagory::create($data);

        return response([ 'RecipesCatagory' => new RecipesCatagoryResource($rec), 'message' => 'Created receo Catagory successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $RecipesCatagory = RecipesCatagory::find($id);
        if ($RecipesCatagory) {
            return new RecipesCatagoryResource($RecipesCatagory);
        }
        return "RecipesCatagory Not found"; 
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
        $rec = RecipesCatagory::find($id);
        $rec->update($request->all());
        return response(['RecipesCatagory' => new RecipesCatagoryResource($rec), 'message' => 'Update RecipesCatagory successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rec = RecipesCatagory::findOrfail($id);
        if ($rec->delete()) {
            return response(['message' => 'RecipesCatagory Deleted successfully']);
        }
        return "Error while deleting";
    }
}

