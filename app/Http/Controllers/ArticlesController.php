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
        $isfavorite;
        $userInfo = auth('api')->user();
        
        $query = DB::table('articles')
            ->join('articles_catagory_pivots', 'articles.id', '=', 'articles_catagory_pivots.article_id')
            ->join('article_tag_pivots', 'articles.id', '=', 'article_tag_pivots.article_id')

            ->join('articles_catagories', 'articles_catagories.id', '=', 'articles_catagory_pivots.articles_catagory_id')
            ->join('article_tags', 'article_tags.id', '=', 'article_tag_pivots.article_tag_id')

        
            // ->join('favorites', 'articles.id', '=', 'favorites.favoritable_id')

            // ->where('favorites.favoritable_id', '=', $id)
            // ->where('favorites.favoritable_type', '=', 'App\Models\Article')
            // ->where('favorites.user_id', '=', $userInfo->id)

            // ->select('articles.*', 'favorites.id AS favorites.id', 'favoritable_type','user_id')
            ->select('articles.*', 'articles_catagories.name AS categorie_name', 'article_tags.name AS tag_name')
            ->groupBy('articles.id')
            ->orderBy('created_at', 'desc')
            ->get();

                        
            if($query->isNotEmpty()){
                return response([ 'Article' => new ArticlesResource($query)], 200);
            }
            
            return response(['isfavorite' => $isfavorite='false', 'Article' => new ArticlesResource($Article)], 200);

        // $userInfo = auth('api')->user();

        // $art = QueryBuilder::for(Article::class)
        //     ->allowedFilters([
        //         'title',
        //         AllowedFilter::exact('author'),
        //     ])
        //     ->allowedSorts('created_at')
        //     ->paginate()
        //     ->appends(request()->query());

        // return ArticlesResource::collection($art);
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
            'author' => 'required',
            'thumbnail' => 'required',
            'image' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $art = Article::create($data);

        return response(['message' => 'Created Catagory successfully', 'Article' => new ArticlesResource($art)], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $isfavorite;
        $userInfo = auth('api')->user();
        $Article = Article::find($id);
        
        if ($Article) {
            $query = DB::table('articles')
            ->join('articles_catagory_pivots', 'articles.id', '=', 'articles_catagory_pivots.article_id')
            ->join('article_tag_pivots', 'articles.id', '=', 'article_tag_pivots.article_id')

            ->join('articles_catagories', 'articles_catagories.id', '=', 'articles_catagory_pivots.articles_catagory_id')
            ->join('article_tags', 'article_tags.id', '=', 'article_tag_pivots.article_tag_id')

            ->where('articles.id', '=', $id)

        
            // ->join('favorites', 'articles.id', '=', 'favorites.favoritable_id')

            // ->where('favorites.favoritable_id', '=', $id)
            // ->where('favorites.favoritable_type', '=', 'App\Models\Article')
            // ->where('favorites.user_id', '=', $userInfo->id)

            // ->select('articles.*', 'favorites.id AS favorites.id', 'favoritable_type','user_id')
            ->select('articles.*', 'articles_catagories.name AS categorie_name', 'article_tags.name AS tag_name')
            ->groupBy('articles.id')->get();

                        
            if($query->isNotEmpty()){
                return response([ 'Article' => new ArticlesResource($query)], 200);
            }
            
            return response(['isfavorite' => $isfavorite='false', 'Article' => new ArticlesResource($Article)], 200);
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
        return response(['message' => 'Update Article successfully', 'Article' => new ArticlesResource($art)], 200);
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
        $userInfo = auth('api')->user();
        if ($userInfo !== null) {
            $article = Article::find($request->id);
            $article->favorites()->create([
                'user_id' => $userInfo->id,
            ]);
            return "Article is added for user:" . $userInfo->social_id;
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
            $Article = DB::table('favorites')
                ->where('favorites.user_id', '=', $userInfo->id)
                ->where('favorites.favoritable_id', '=', $request->id)
                ->where('favorites.favoritable_type', '=', 'App\Models\Article')
                ->delete();
            // return new ArticlesResource($Article);
            return "Article is delete from favorites for user:" . $userInfo->social_id;
        } else {
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

            ->join('articles_catagories', 'articles_catagories.id', '=', 'articles_catagory_pivots.articles_catagory_id')
            ->join('article_tags', 'article_tags.id', '=', 'article_tag_pivots.article_tag_id')

            ->select('articles.*', 'articles_catagories.name AS categorie_name', 'article_tags.name AS tag_name');
            $result= $query->groupBy('id')->get(); 
/****************************************************************************************************************/
        if ($request->category != null) {
            $query->where('articles_catagory_pivots.articles_catagory_id', '=', $request->category);
            $result= $query->groupBy('id')->get();
        }

        if ($request->tag != null) {
            $query->where('article_tag_pivots.article_tag_id', '=', $request->tag);
            $result= $query->groupBy('id')->get();
        }
/****************************************************************************************************************/
        return new ArticlesResource($result);
    }


        /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function articleAndRecipes(Request $request)
    {

        $articles = DB::table('articles')->groupBy('id')->get(); 

        $recipes = DB::table('recipes')->groupBy('id')->get(); 

        return response(['Articles' => new ArticlesResource($articles), 'Recipes' => new ArticlesResource($recipes)], 200);

        // return new ArticlesResource($recipes);
    }
}
