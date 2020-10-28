<?php

namespace App\Http\Controllers;

use App\Models\VideoType;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoTypeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class VideoTypeController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $vid = QueryBuilder::for(VideoType::class)
        ->allowedFilters([
            AllowedFilter::exact('name'),
        ])
        ->paginate()
        ->appends(request()->query());
        
        return VideoTypeResource::collection($vid);
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
            'name' => 'required|max:255|unique:video_types',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $vid = VideoType::create($data);

        return response([ 'VideoType' => new VideoTypeResource($vid), 'message' => 'Created Video Catagory successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $VideoType = VideoType::find($id);
        if ($VideoType) {
            return new VideoTypeResource($VideoType);
        }
        return "VideoType Not found"; 
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
            'name' => 'max:255|unique:video_types',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $vid = VideoType::find($id);
        $vid->update($data);
        return response(['VideoType' => new VideoTypeResource($vid), 'message' => 'Update VideoType successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vid = VideoType::findOrfail($id);
        if ($vid->delete()) {
            return response(['message' => 'VideoType Deleted successfully']);
        }
        return "Error while deleting";
    }
}

