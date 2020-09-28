<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticlesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ArticlesController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $art = QueryBuilder::for(Article::class)
        ->allowedFilters([
            'title',
            AllowedFilter::exact('author'),
            AllowedFilter::exact('catagory'),
            AllowedFilter::exact('tag'),
        ])
        ->paginate()
        ->appends(request()->query());
        
        return ArticlesResource::collection($art);
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
            'author' => 'required|max:255',
            'catagory' => 'required|max:255',
            'tag' => 'required|max:255',
            'thumbnail' => 'required|max:255',
            'image' => 'required|max:255',
            'content' => 'required',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $art = Article::create($data);

        return response([ 'Article' => new ArticlesResource($art), 'message' => 'Created arteo Catagory successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Article = Article::find($id);
        if ($Article) {
            return new ArticlesResource($Article);
        }
        return "Article Not found"; 
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
        $art = Article::find($id);
        $art->update($request->all());
        return response(['Article' => new ArticlesResource($art), 'message' => 'Update Article successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $art = Article::findOrfail($id);
        if ($art->delete()) {
            return response(['message' => 'Article Deleted successfully']);
        }
        return "Error while deleting";
    }
}

