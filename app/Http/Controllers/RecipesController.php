<?php

namespace App\Http\Controllers;

use App\Models\Recipes;
use App\Http\Controllers\Controller;
use App\Http\Resources\RecipesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class RecipesController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $rec = QueryBuilder::for(Recipes::class)
        ->allowedFilters([
            'title',
            AllowedFilter::exact('catagory'),
        ])
        ->paginate()
        ->appends(request()->query());
        
        return RecipesResource::collection($rec);
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
            'title' => 'required|max:255',
            'catagory' => 'required|max:255',
            'thumbnail' => 'required|max:255',
            'image' => 'required|max:255',
            'content' => 'required',
            'calories' => 'max:255|numeric',
            'fat' => 'max:255|numeric',
            'protein' => 'max:255|numeric',
            'carb' => 'max:255|numeric',

        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $rec = Recipes::create($data);

        return response([ 'Recipes' => new RecipesResource($rec), 'message' => 'Created Recipes successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Recipes = Recipes::find($id);
        if ($Recipes) {
            return new RecipesResource($Recipes);
        }
        return "Recipes Not found"; 
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
        $data = $request->all();

        $validator = Validator::make($data, [
            'calories' => 'max:255|numeric',
            'fat' => 'max:255|numeric',
            'protein' => 'max:255|numeric',
            'carb' => 'max:255|numeric',

        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $rec = Recipes::find($id);
        $rec->update($request->all());
        return response(['Recipes' => new RecipesResource($rec), 'message' => 'Update Recipes successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rec = Recipes::findOrfail($id);
        if ($rec->delete()) {
            return response(['message' => 'Recipes Deleted successfully']);
        }
        return "Error while deleting";
    }

          /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function addToFavorites(Request $request)
    {
        $userInfo=auth('api')->user();
        if ($userInfo!==null)
        {
            $rec = Recipes::find($request->id);	
            $rec->favorites()->create([
                'user_id' => $userInfo->id,
            ]);
            return "Recipes is added for user:".$userInfo->social_id;
        }else{
            return "User is not logged in.";
        }
    }
}

