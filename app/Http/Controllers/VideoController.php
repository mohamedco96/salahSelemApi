<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;

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

        $vid = QueryBuilder::for(Video::class)
        
        ->allowedFilters(['catagory', 'type', 'video_Name'])
        ->paginate()
        ->appends(request()->query());
        
        // $vid = Video::all();
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
        $vid = new Video;
        $vid->video_Name = $request->input('video_Name');
        $vid->catagory_id = $request->input('catagory_id');
        $vid->video_thumbnail = $request->input('video_thumbnail');
        $vid->video_Description = $request->input('video_Description');
        $vid->video_Type = $request->input('video_Type');
        $vid->video_Link = $request->input('video_Link');
        $vid->video_Duration = $request->input('video_Duration');
        $vid->video_Quote = $request->input('video_Quote');
        $vid->video_Reps = $request->input('video_Reps');
        $vid->video_Sets = $request->input('video_Sets');
        $vid->save();
        // return new VideoResource($vid);
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
        $Video = Video::find($id); //id comes from route
        if ($Video) {
            return new VideoResource($Video);
        }
        return "Video Not found"; // temporary error
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
        // return new VideoResource($vid);
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
}

