<?php

namespace App\Http\Controllers;

use App\Models\ArticleTag;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticlesTagResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ArticlesTagController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $art = QueryBuilder::for(ArticleTag::class)
        ->allowedFilters([
            AllowedFilter::exact('name'),
        ])
        ->paginate()
        ->appends(request()->query());
        
        return ArticlesTagResource::collection($art);
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
            'name' => 'required|max:255|unique:article_tags',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $art = ArticleTag::create($data);

        return response([ 'ArticleTag' => new ArticlesTagResource($art), 'message' => 'Created arteo Catagory successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ArticleTag = ArticleTag::find($id);
        if ($ArticleTag) {
            return new ArticlesTagResource($ArticleTag);
        }
        return "ArticleTag Not found"; 
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
            'name' => 'max:255|unique:article_tags',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $art = ArticleTag::find($id);
        $art->update($data);
        return response(['ArticleTag' => new ArticlesTagResource($art), 'message' => 'Update ArticleTag successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $art = ArticleTag::findOrfail($id);
        if ($art->delete()) {
            return response(['message' => 'ArticleTag Deleted successfully']);
        }
        return "Error while deleting";
    }
}

