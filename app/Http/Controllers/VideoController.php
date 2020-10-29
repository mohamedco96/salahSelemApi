<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vid = QueryBuilder::for(Video::class)
            ->allowedFilters([
                'video_Name',
            ])
            ->paginate()
            ->appends(request()->query());

        return VideoResource::collection($vid);
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
            'video_Name' => 'required',
            'video_thumbnail' => 'required',
            'video_Link' => 'required',
            'video_Reps' => 'numeric',
            'video_Sets' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $vid = Video::create($data);

        return response(['message' => 'Created Video successfully', 'Video' => new VideoResource($vid)], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Video = Video::find($id);
        if ($Video) {
            return new VideoResource($Video);
        }
        return "Video Not found";
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
        $vid = Video::find($id);
        $vid->update($request->all());
        return response(['message' => 'Update Video successfully', 'Video' => new VideoResource($vid)], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vid = Video::findOrfail($id);
        if ($vid->delete()) {
            return response(['message' => 'Video Deleted successfully']);
            // return new VideoResource($vid);
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
            $vid = Video::find($request->id);
            $vid->favorites()->create([
                'user_id' => $userInfo->id,
            ]);
            return "Video is added for user:" . $userInfo->social_id;
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
            $videos = DB::table('favorites')
                ->where('favorites.user_id', '=', $userInfo->id)
                ->where('favorites.favoritable_id', '=', $request->id)
                ->where('favorites.favoritable_type', '=', 'App\Models\Video')
                ->delete();

            return "Video is delete from favorites for user:" . $userInfo->social_id;
        } else {
            return "User is not logged in.";
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function videoFillter(Request $request)
    {
        $userInfo = auth('api')->user();

        if ($userInfo !== null) {
            /****************************************************************************************************************/
            $interesteds = DB::table('interesteds')
                ->where('interesteds.user_id', '=', $userInfo->id)
                ->get();
            /****************************************************************************************************************/
            $query = DB::table('videos')
                ->join('videocategoriespivots', 'videos.id', '=', 'videocategoriespivots.video_id')
                ->join('video_type_pivots', 'videos.id', '=', 'video_type_pivots.video_id')
                ->join('video_tag_pivots', 'videos.id', '=', 'video_tag_pivots.video_id')
                ->join('muscle_pivots', 'videos.id', '=', 'muscle_pivots.video_id')

                ->join('video_catagories', 'video_catagories.id', '=', 'videocategoriespivots.video_catagory_id')
                ->join('video_types', 'video_types.id', '=', 'video_type_pivots.video_type_id')
                ->join('video_tags', 'video_tags.id', '=', 'video_tag_pivots.video_tag_id')
                ->join('muscles', 'muscles.id', '=', 'muscle_pivots.muscle_id')

                ->where('muscle_pivots.muscle_id', '=', $request->muscle)
                ->select('videos.*', 'video_catagories.name AS categorie_name', 'video_types.name AS type_name', 'video_tags.name AS tag_name', 'muscles.name AS muscle_name');
            // $result= $query->get();
            /****************************************************************************************************************/
            if ($interesteds[0]->functional_training == 'false') {
                $query->where('video_catagory_id', '!=', '1');
                $result= $query->groupBy('id')->get();
            }

            if ($interesteds[0]->power_training == 'false') {
                $query->where('video_catagory_id', '!=', '2');
                $result= $query->groupBy('id')->get();
            }

            if ($interesteds[0]->CrossFit == 'false') {
                $query->where('video_catagory_id', '!=', '3');
                $result= $query->groupBy('id')->get();
            }

            if ($interesteds[0]->yoga == 'false') {
                $query->where('video_catagory_id', '!=', '4');
                $result= $query->groupBy('id')->get();
            }

            if ($interesteds[0]->workouts == 'false') {
                $query->where('video_catagory_id', '!=', '5');
                $result= $query->groupBy('id')->get();
            }

            if ($interesteds[0]->cardio == 'false') {
                $query->where('video_catagory_id', '!=', '6');
                $result= $query->groupBy('id')->get();
            }
            /****************************************************************************************************************/
            if ($userInfo->training_type == 'home') {
                $query->where('video_type_pivots.video_type_id', '=', '1');
                $result= $query->groupBy('id')->get();
            }

            if ($userInfo->training_type == 'gym') {
                $query->where('video_type_pivots.video_type_id', '=', '2');
                $result= $query->groupBy('id')->get();
            }
            /****************************************************************************************************************/
            if ($userInfo->tag == 'weight') {
                $query->where('video_tag_pivots.video_tag_id', '=', '1');
                $result= $query->groupBy('id')->get();
            }

            if ($userInfo->tag == 'no weight') {
                $query->where('video_tag_pivots.video_tag_id', '=', '2');
                $result= $query->groupBy('id')->get();
            }

            return new VideoResource($result);
        } else {
            $query = DB::table('videos')
                ->join('videocategoriespivots', 'videos.id', '=', 'videocategoriespivots.video_id')
                ->join('video_type_pivots', 'videos.id', '=', 'video_type_pivots.video_id')
                ->join('video_tag_pivots', 'videos.id', '=', 'video_tag_pivots.video_id')
                ->join('muscle_pivots', 'videos.id', '=', 'muscle_pivots.video_id')

                ->join('video_catagories', 'video_catagories.id', '=', 'videocategoriespivots.video_catagory_id')
                ->join('video_types', 'video_types.id', '=', 'video_type_pivots.video_type_id')
                ->join('video_tags', 'video_tags.id', '=', 'video_tag_pivots.video_tag_id')
                ->join('muscles', 'muscles.id', '=', 'muscle_pivots.muscle_id')

                ->where('muscle_pivots.muscle_id', '=', $request->muscle)
                ->select('videos.*', 'video_catagories.name AS categorie_name', 'video_types.name AS type_name', 'video_tags.name AS tag_name', 'muscles.name AS muscle_name');
                $result= $query->groupBy('id')->get();

            return new VideoResource($result);
        }
    }
}
