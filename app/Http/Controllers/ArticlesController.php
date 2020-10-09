<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticlesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\DB;

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
        ->allowedSorts('created_at')
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

        /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function addToFavorites(Request $request)
    {
        $userInfo=auth('api')->user();
        if ($userInfo!==null)
        {
            $article = Article::find($request->id);	
            $article->favorites()->create([
                'user_id' => $userInfo->id,
            ]);
            return "Article is added for user:".$userInfo->social_id;
        }else{
            return "User is not logged in.";
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
    */
    public function removeFromFavorites(Request $request)
    {
        $userInfo=auth('api')->user();
        if ($userInfo!==null)
        {
            $Article = DB::table('favorites')
            ->where('favorites.user_id', '=', $userInfo->id)
            ->where('favorites.favoritable_id', '=', $request->id)
            ->where('favorites.favoritable_type', '=', 'App\Models\Article')
            ->delete();
            // return new ArticlesResource($Article);
            return "Article is delete from favorites for user:".$userInfo->social_id;
        }else{
            return "User is not logged in.";
        }
    }



            /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function articleFillter(Request $request)
    {
       
            $query = DB::table('articles')
            ->join('articles_catagory_pivots', 'articles.id', '=', 'articles_catagory_pivots.article_id')
            ->join('article_tag_pivots', 'articles.id', '=', 'article_tag_pivots.article_id')
            ;
            $result= $query->get();
/****************************************************************************************************************/
            if ($request->category != null) {
                $query->where('articles_catagory_pivots.articles_catagory_id', '=', $request->category);
                $result= $query->get();
            }

            if ($request->tag != null) {
                $query->where('article_tag_pivots.article_tag_id', '=', $request->tag);
                $result= $query->get();
            }
/****************************************************************************************************************/
            return new ArticlesResource($result);

    }
    
}

