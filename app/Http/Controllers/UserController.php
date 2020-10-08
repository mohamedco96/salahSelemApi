<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use App\Models\Favorite;
use App\Models\Article;


use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class UserController extends Controller
{
    public function auth(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'social_id' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
             // register
        if (!auth()->attempt($data)) {
            $data['password'] = bcrypt($request->password);
            $user = User::create($data);
            $accessToken = $user->createToken('authToken')->accessToken;
            return response(['user' => $user, 'access_token' => $accessToken]);
            return response(['message' => 'new user created']);
        }
        
        //login
        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        $user = User::find(auth()->user()->id);
        $user->status = 'online';
        $user->update();
        return response(['user' => auth()->user(), 'access_token' => $accessToken]);

   
    }

    public function logout(Request $request)
    {    
        $user = User::find(auth('api')->user()->id);
        if ($user!==null)
        {
            $user->status = 'offline';
            $user->update();
            return "User is logout";
        }else{
            return "User is not logged in.";
        }
      
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
        $data['password'] = bcrypt($request->password);
        $validator = Validator::make($data, [
            'social_id' => 'required|unique:users',
            'password' => 'required',
            'role_id' => 'max:255|numeric',
            'email' => 'max:255|email',
            'avatar' => 'max:255',
            'fname' => 'max:255',
            'lname' => 'max:255',
            'age' => 'max:255|numeric',
            'gender' => 'max:255',
            'height' => 'max:255|numeric',
            'weight' => 'max:255|numeric',
            'neck_size' => 'max:255|numeric',
            'waist_size' => 'max:255|numeric',
            'hips' => 'max:255|numeric',
            'goals' => 'max:255',
            'activity' => 'max:255',
            'days_of_training' => 'max:255|numeric',
            'training_type' => 'max:255',
            'Water' => 'max:255|numeric',
            'sleep_hours' => 'max:255|numeric',
            'fat' => 'max:255|numeric',
            'calorie' => 'max:255|numeric',
            'protein' => 'max:255|numeric',
            'volume' => 'max:255|numeric',
            'status' => 'max:255',
            
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $User = User::create($data);

        return response([ 'user' => new ApiResource($User), 'message' => 'Created User successfully'], 200);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = QueryBuilder::for(User::class)
        ->allowedFilters([
            'fname',
            'lname',
            'age',
            AllowedFilter::exact('social_id'),
            AllowedFilter::exact('email'),
            AllowedFilter::exact('role_id'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('gender'),

        ])
        ->paginate()
        ->appends(request()->query());
        
        return ApiResource::collection($user);


        // $user = User::paginate(3);
        
    // return view('products.index-paging')->with('products', $products);

        // $user = User::all();
        // return response(['user' => ApiResource::collection($user), 'message' => 'Retrieved All successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id); 
        if ($user) {
            return new ApiResource($user);
        }
        return "user Not found"; 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'password' => 'max:255',
            'role_id' => 'max:255|numeric',
            'email' => 'max:255|email',
            'avatar' => 'max:255',
            'fname' => 'max:255',
            'lname' => 'max:255',
            'age' => 'max:255|numeric',
            'gender' => 'max:255',
            'height' => 'max:255|numeric',
            'weight' => 'max:255|numeric',
            'neck_size' => 'max:255|numeric',
            'waist_size' => 'max:255|numeric',
            'hips' => 'max:255|numeric',
            'goals' => 'max:255',
            'activity' => 'max:255',
            'days_of_training' => 'max:255|numeric',
            'training_type' => 'max:255',
            'Water' => 'max:255|numeric',
            'sleep_hours' => 'max:255|numeric',
            'fat' => 'max:255|numeric',
            'calorie' => 'max:255|numeric',
            'protein' => 'max:255|numeric',
            'volume' => 'max:255|numeric',
            'status' => 'max:255',
        ]);
        

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        
        $user->update($data);

        return response(['user' => new ApiResource($user), 'message' => 'Update User successfully'], 200);
    }

  /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrfail($id);
        if ($user->delete()) {
            return response(['message' => 'User Deleted successfully']);
        }
        return response(['message' => 'Error while deleting']);
        // return "Error while deleting";
    }

            /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function userFavorites()
    {
        $userInfo=auth('api')->user();

        if ($userInfo!==null)
        {
        $articles = DB::table('users')
            ->join('favorites', 'users.id', '=', 'favorites.user_id')
            ->join('articles', 'favorites.favoritable_id', '=', 'articles.id')
            ->where('favorites.favoritable_type', '=', 'App\Models\Article')
            ->where('users.id', '=', $userInfo->id)
            ->select('users.social_id','users.name', 'favorites.favoritable_type', 'articles.id', 'articles.title', 'articles.thumbnail', 'articles.catagory')
            ->get();

      
        $videos = DB::table('users')
            ->join('favorites', 'users.id', '=', 'favorites.user_id')
            ->join('videos', 'favorites.favoritable_id', '=', 'videos.id')
            ->where('favorites.favoritable_type', '=', 'App\Models\Video')
            ->where('users.id', '=', $userInfo->id)
            ->select('users.social_id','users.name', 'favorites.favoritable_type', 'videos.id', 'videos.video_Name', 'videos.video_thumbnail', 'videos.catagory')
            ->get();

        $recipes = DB::table('users')
            ->join('favorites', 'users.id', '=', 'favorites.user_id')
            ->join('recipes', 'favorites.favoritable_id', '=', 'recipes.id')
            ->where('favorites.favoritable_type', '=', 'App\Models\Recipes')
            ->where('users.id', '=', $userInfo->id)
            ->select('users.social_id','users.name', 'favorites.favoritable_type', 'recipes.id', 'recipes.title', 'recipes.thumbnail', 'recipes.catagory')
            ->get();

        $merged = $articles->merge($videos)->merge($recipes);
        $result = $merged->all();
       return new ApiResource($result);
        }else{
            return "User is not logged in.";
        }
    }

              /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function interesteds(Request $request)
    {
        $userInfo=auth('api')->user();
        if ($userInfo!==null)
        {
            $user = User::find($userInfo->id);	
            $user->interesteds()->create([
                'user_id' => $userInfo->id,
                'functional_training' => 'true',
                'power_training' => 'true',
                'CrossFit' => 'true',
                'yoga' => 'true',
                'workouts' => 'true',
                'cardio' => 'true',
            ]);
            return "interesteds is added for user:".$userInfo->social_id;
        }else{
            return "User is not logged in.";
        }
    }

}
