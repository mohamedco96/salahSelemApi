<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

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
}

