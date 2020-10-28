<?php

namespace App\Http\Controllers;

use App\Models\ArticlesCatagory;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticlesCatagoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ArticlesCatagoryController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $art = QueryBuilder::for(ArticlesCatagory::class)
        ->allowedFilters([
            AllowedFilter::exact('name'),
        ])
        ->paginate()
        ->appends(request()->query());
        
        return ArticlesCatagoryResource::collection($art);
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
            'name' => 'required|max:255|unique:articles_catagories',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $art = ArticlesCatagory::create($data);

        return response([ 'ArticlesCatagory' => new ArticlesCatagoryResource($art), 'message' => 'Created arteo Catagory successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ArticlesCatagory = ArticlesCatagory::find($id);
        if ($ArticlesCatagory) {
            return new ArticlesCatagoryResource($ArticlesCatagory);
        }
        return "ArticlesCatagory Not found"; 
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
            'name' => 'max:255|unique:articles_catagories',
        ]);
        

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $art = ArticlesCatagory::find($id);
        $art->update($data);
        return response(['ArticlesCatagory' => new ArticlesCatagoryResource($art), 'message' => 'Update ArticlesCatagory successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $art = ArticlesCatagory::findOrfail($id);
        if ($art->delete()) {
            return response(['message' => 'ArticlesCatagory Deleted successfully']);
        }
        return "Error while deleting";
    }
}

