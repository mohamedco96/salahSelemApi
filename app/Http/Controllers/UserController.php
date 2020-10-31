<?php

namespace App\Http\Controllers;

use App\Models\User;

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

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $query = DB::table('users')
        ->where('users.social_id', '=', $request->social_id)
        ->get();
        // register
        if($query->isEmpty()){
            $data['password'] = bcrypt($request->password);
            $user = User::create($data);
            $accessToken = $user->createToken('authToken')->accessToken;
            $user->access_token = $accessToken;
            $user->update();
            return response(['message' => 'Register successfully', 'user' => $user, 'access_token' => $accessToken]);
        }

        //login
        if($query->isNotEmpty()){
            $iscomplete='true';
        // $accessToken = auth()->user()->createToken('authToken')->accessToken;
        // $test= $query->keyBy('id')->json_decode();
        $test= $query->toArray();
        // return response(['message' => 'Login successfully', 'user' => $test[0]->id]);
        $user = User::find($test[0]->id);
        $user->status = 'online';
        $user->update();
        if($test[0]->tag==null){
            $iscomplete='false';
        }
        return response(['message' => 'Login successfully', 'iscomplete' => $iscomplete, 'user' => $user, 'access_token' => $user->access_token]);
        }
    }

    public function logout(Request $request)
    {
        $userInfo = auth('api')->user();
        if ($userInfo !== null) {
            $user = User::find($userInfo->id);
            $user->status = 'offline';
            $user->update();
            return "User is logout successfully";
        } else {
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
        // $data['password'] = bcrypt($request->password);
        $validator = Validator::make($data, [
            'social_id' => 'required|unique:users',
            'password' => 'required',
            'role_id' => 'numeric',
            'email' => 'email',
            'age' => 'numeric',
            'height' => 'numeric',
            'weight' => 'numeric',
            'neck_size' => 'numeric',
            'waist_size' => 'numeric',
            'hips' => 'numeric',
            'days_of_training' => 'numeric',
            'Water' => 'numeric',
            'sleep_hours' => 'numeric',
            'fat' => 'numeric',
            'calorie' => 'numeric',
            'protein' => 'numeric',
            'volume' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $User = User::create($data);
        $accessToken = $User->createToken('authToken')->accessToken;
        $User->access_token = $accessToken;
        $User->update();
        return response(['message' => 'Created User successfully', 'user' => new ApiResource($User), 'access_token' => $User->access_token], 200);
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
                'name',
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
        // $data['password'] = bcrypt($request->password);
        $validator = Validator::make($data, [
            'role_id' => 'numeric',
            'email' => 'email',
            'age' => 'numeric',
            'height' => 'numeric',
            'weight' => 'numeric',
            'neck_size' => 'numeric',
            'waist_size' => 'numeric',
            'hips' => 'numeric',
            'days_of_training' => 'numeric',
            'Water' => 'numeric',
            'sleep_hours' => 'numeric',
            'fat' => 'numeric',
            'calorie' => 'numeric',
            'protein' => 'numeric',
            'volume' => 'numeric',
        ]);


        if ($validator->fails()) {
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
        $userInfo = auth('api')->user();

        if ($userInfo !== null) {
            $articles = DB::table('users')
                ->join('favorites', 'users.id', '=', 'favorites.user_id')
                ->join('articles', 'favorites.favoritable_id', '=', 'articles.id')
                ->where('favorites.favoritable_type', '=', 'App\Models\Article')
                ->where('users.id', '=', $userInfo->id)
                ->select('users.social_id', 'users.name', 'favorites.favoritable_type', 'articles.id', 'articles.title', 'articles.thumbnail')
                ->get();


            $videos = DB::table('users')
                ->join('favorites', 'users.id', '=', 'favorites.user_id')
                ->join('videos', 'favorites.favoritable_id', '=', 'videos.id')
                // ->join('videocategoriespivots', 'videocategoriespivots.video_id', '=', 'videos.id')
                // ->join('video_catagories', 'video_catagories.id', '=', 'videocategoriespivots.video_catagory_id')
                ->where('favorites.favoritable_type', '=', 'App\Models\Video')
                ->where('users.id', '=', $userInfo->id)
                ->select('users.social_id', 'users.name', 'favorites.favoritable_type', 'videos.id', 'videos.video_Name', 'videos.video_thumbnail')
                ->get();

            $recipes = DB::table('users')
        ->join('favorites', 'users.id', '=', 'favorites.user_id')
                ->join('recipes', 'favorites.favoritable_id', '=', 'recipes.id')
                ->where('favorites.favoritable_type', '=', 'App\Models\Recipes')
                ->where('users.id', '=', $userInfo->id)
                ->select('users.social_id', 'users.name', 'favorites.favoritable_type', 'recipes.id', 'recipes.title', 'recipes.thumbnail')
                ->get();

            $merged = $articles->merge($videos)->merge($recipes);
            $result = $merged->all();
            return new ApiResource($result);
        } else {
            return "User is not logged in.";
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function AddInteresteds(Request $request)
    {
        $userInfo = auth('api')->user();
        if ($userInfo !== null) {
            $user = User::find($userInfo->id);
            $user->interesteds()->create([
                'user_id' => $userInfo->id,
                'functional_training' => $request->functional_training,
                'power_training' => $request->power_training,
                'CrossFit' => $request->CrossFit,
                'yoga' => $request->yoga,
                'workouts' => $request->workouts,
                'cardio' => $request->cardio,
            ]);
            return "interesteds is added for social id:" . $userInfo->social_id;
        } else {
            return "User is not logged in.";
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function UpdateInteresteds(Request $request)
    {
        $userInfo = auth('api')->user();
        if ($userInfo !== null) {
            $user = User::find($userInfo->id);
            $user->interesteds()->update([
                'functional_training' => $request->functional_training,
                'power_training' => $request->power_training,
                'CrossFit' => $request->CrossFit,
                'yoga' => $request->yoga,
                'workouts' => $request->workouts,
                'cardio' => $request->cardio,
            ]);
            return "interesteds is Updated for social id:" . $userInfo->social_id;
        } else {
            return "User is not logged in.";
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function DeleteInteresteds(Request $request)
    {
        $userInfo = auth('api')->user();
        if ($userInfo !== null) {
            $user = User::find($userInfo->id);
            $user->interesteds()->delete();
            return "interesteds is deleted for social id:" . $userInfo->social_id;
        } else {
            return "User is not logged in.";
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function ShowInteresteds()
    {
        $userInfo = auth('api')->user();

        if ($userInfo !== null) {
            $interesteds = DB::table('users')
                ->join('interesteds', 'users.id', '=', 'interesteds.user_id')
                ->where('users.id', '=', $userInfo->id)
                ->select('users.social_id', 'interesteds.*')
                ->get();

            return new ApiResource($interesteds);
        } else {
            return "User is not logged in.";
        }
    }


}
