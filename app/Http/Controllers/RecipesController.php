<?php

namespace App\Http\Controllers;

use App\Models\Recipes;
use App\Http\Controllers\Controller;
use App\Http\Resources\RecipesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\DB;

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
            'title' => 'required',
            'thumbnail' => 'required',
            'image' => 'required',
            'ingredients' => 'required',
            'content' => 'required',
            'time' => 'numeric',
            'calories' => 'numeric',
            'fat' => 'numeric',
            'protein' => 'numeric',
            'carb' => 'numeric',

        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $rec = Recipes::create($data);

        return response(['message' => 'Created Recipes successfully', 'Recipes' => new RecipesResource($rec)], 200);
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
            'calories' => 'numeric',
            'fat' => 'numeric',
            'protein' => 'numeric',
            'carb' => 'numeric',
            'time' => 'numeric',

        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $rec = Recipes::find($id);
        $rec->update($request->all());
        return response(['message' => 'Update Recipes successfully', 'Recipes' => new RecipesResource($rec)], 200);
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
        $userInfo = auth('api')->user();
        if ($userInfo !== null) {
            $rec = Recipes::find($request->id);
            $rec->favorites()->create([
                'user_id' => $userInfo->id,
            ]);
            return "Recipes is added for user:" . $userInfo->social_id;
        } else {
            return "User is not logged in.";
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function removeFromFavorites(Request $request)
    {
        $userInfo = auth('api')->user();
        if ($userInfo !== null) {
            $Recipes = DB::table('favorites')
                ->where('favorites.user_id', '=', $userInfo->id)
                ->where('favorites.favoritable_id', '=', $request->id)
                ->where('favorites.favoritable_type', '=', 'App\Models\Recipes')
                ->delete();
            // return new RecipesResource($Recipes);
            return "Recipes is delete from favorites for user:" . $userInfo->social_id;
        } else {
            return "User is not logged in.";
        }
    }



    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function RecipesFillter(Request $request)
    {

        $query = DB::table('recipes')
            ->join('recipes_category_pivots', 'recipes.id', '=', 'recipes_category_pivots.recipes_id');
        $result = $query->get();
        /****************************************************************************************************************/
        if ($request->category != null) {
            $query->where('recipes_category_pivots.recipes_catagory_id', '=', $request->category);
            $result = $query->get();
        }
        /****************************************************************************************************************/
        return new RecipesResource($result);
    }
}
