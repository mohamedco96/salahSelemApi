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
        // $vid = QueryBuilder::for(Video::class)
        // ->allowedFilters('catagory')
        // ->get();

        // $vid = QueryBuilder::for(Video::class)
        
        // ->allowedFilters(['catagory', 'type', 'video_Name'])
        // ->paginate()
        // ->appends(request()->query());
        

        $vid = QueryBuilder::for(Video::class)
        ->allowedFilters([
            'video_Name',
            AllowedFilter::exact('catagory'),
            AllowedFilter::exact('type'),
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
            'video_Name' => 'required|max:255',
            'catagory' => 'required|max:255',
            'type' => 'required|max:255',
            'video_thumbnail' => 'required|max:255',
            'video_Link' => 'required|max:255',
            'video_Reps' => 'max:255|numeric',
            'video_Sets' => 'max:255|numeric',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $vid = Video::create($data);

        return response([ 'Video' => new VideoResource($vid), 'message' => 'Created Video successfully'], 200);
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
        return response(['Video' => new VideoResource($vid), 'message' => 'Update Video successfully'], 200);
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
        $userInfo=auth('api')->user();
        if ($userInfo!==null)
        {
            $vid = Video::find($request->id);	
            $vid->favorites()->create([
                'user_id' => $userInfo->id,
            ]);
            return "Video is added for user:".$userInfo->social_id;
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
            $videos = DB::table('favorites')
            ->where('favorites.user_id', '=', $userInfo->id)
            ->where('favorites.favoritable_id', '=', $request->id)
            ->where('favorites.favoritable_type', '=', 'App\Models\Video')
            ->delete();

            return "Video is delete from favorites for user:".$userInfo->social_id;
        }else{
            return "User is not logged in.";
        }
    }

            /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function videoFillter(Request $request)
    {
        $userInfo=auth('api')->user();

        if ($userInfo!==null)
        {
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
            ;
            $result= $query->get();
/****************************************************************************************************************/
            if ($interesteds[0]->functional_training=='false') {
                $query->where('video_catagory_id', '!=', '1');
                $result= $query->get();
            }

            if ($interesteds[0]->power_training=='false') {
                $query->where('video_catagory_id', '!=', '2');
                $result= $query->get();
            }

            if ($interesteds[0]->CrossFit=='false') {
                $query->where('video_catagory_id', '!=', '3');
                $result= $query->get();
            }

            if ($interesteds[0]->yoga=='false') {
                $query->where('video_catagory_id', '!=', '4');
                $result= $query->get();
            }

            if ($interesteds[0]->workouts=='false') {
                $query->where('video_catagory_id', '!=', '5');
                $result= $query->get();
            }

            if ($interesteds[0]->cardio=='false') {
                $query->where('video_catagory_id', '!=', '6');
                $result= $query->get();
            }
/****************************************************************************************************************/
            if ($userInfo->training_type=='home') {
                $query->where('video_type_pivots.video_type_id', '=', '1');
                $result= $query->get();
            }

            if ($userInfo->training_type=='gym') {
                $query->where('video_type_pivots.video_type_id', '=', '2');
                $result= $query->get();
            }
/****************************************************************************************************************/
            if ($userInfo->tag=='weight') {
                $query->where('video_tag_pivots.video_tag_id', '=', '1');
                $result= $query->get();
            }

            if ($userInfo->tag=='no weight') {
                $query->where('video_tag_pivots.video_tag_id', '=', '2');
                $result= $query->get();
            }
/****************************************************************************************************************/
            if ($request->muscle=='shoulders') {
                $query->where('muscle_pivots.muscle_id', '=', '1');
                $result= $query->get();
            }

            if ($request->muscle=='chest') {
                $query->where('muscle_pivots.muscle_id', '=', '2');
                $result= $query->get();
            }

            if ($request->muscle=='biceps') {
                $query->where('muscle_pivots.muscle_id', '=', '3');
                $result= $query->get();
            }

            if ($request->muscle=='forearm') {
                $query->where('muscle_pivots.muscle_id', '=', '4');
                $result= $query->get();
            }

            if ($request->muscle=='abs') {
                $query->where('muscle_pivots.muscle_id', '=', '5');
                $result= $query->get();
            }

            if ($request->muscle=='obliques') {
                $query->where('muscle_pivots.muscle_id', '=', '6');
                $result= $query->get();
            }

            if ($request->muscle=='quads') {
                $query->where('muscle_pivots.muscle_id', '=', '7');
                $result= $query->get();
            }

            if ($request->muscle=='abductors') {
                $query->where('muscle_pivots.muscle_id', '=', '8');
                $result= $query->get();
            }

            if ($request->muscle=='adductors') {
                $query->where('muscle_pivots.muscle_id', '=', '9');
                $result= $query->get();
            }

            if ($request->muscle=='traps') {
                $query->where('muscle_pivots.muscle_id', '=', '10');
                $result= $query->get();
            }

            if ($request->muscle=='triceps') {
                $query->where('muscle_pivots.muscle_id', '=', '11');
                $result= $query->get();
            }

            if ($request->muscle=='lats') {
                $query->where('muscle_pivots.muscle_id', '=', '12');
                $result= $query->get();
            }

            if ($request->muscle=='lower back') {
                $query->where('muscle_pivots.muscle_id', '=', '13');
                $result= $query->get();
            }

            if ($request->muscle=='glutes') {
                $query->where('muscle_pivots.muscle_id', '=', '14');
                $result= $query->get();
            }

            if ($request->muscle=='hamstrings') {
                $query->where('muscle_pivots.muscle_id', '=', '15');
                $result= $query->get();
            }

            if ($request->muscle=='calves') {
                $query->where('muscle_pivots.muscle_id', '=', '16');
                $result= $query->get();
            }
/****************************************************************************************************************/
            return new VideoResource($result);
        }else{
            return "User is not logged in.";
        }
    }
}

