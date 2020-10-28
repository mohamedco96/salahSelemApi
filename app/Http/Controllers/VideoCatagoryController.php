<?php

namespace App\Http\Controllers;

use App\Models\VideoCatagory;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoCatagoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class VideoCatagoryController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $vid = QueryBuilder::for(VideoCatagory::class)
        ->allowedFilters([
            AllowedFilter::exact('name'),
        ])
        ->paginate()
        ->appends(request()->query());
        
        return VideoCatagoryResource::collection($vid);
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
            'name' => 'required|max:255|unique:video_catagories',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $vid = VideoCatagory::create($data);

        return response([ 'VideoCatagory' => new VideoCatagoryResource($vid), 'message' => 'Created Video Catagory successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $VideoCatagory = VideoCatagory::find($id);
        if ($VideoCatagory) {
            return new VideoCatagoryResource($VideoCatagory);
        }
        return "VideoCatagory Not found"; 
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
            'name' => 'max:255|unique:video_catagories',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $vid = VideoCatagory::find($id);
        $vid->update($data);
        return response(['VideoCatagory' => new VideoCatagoryResource($vid), 'message' => 'Update VideoCatagory successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vid = VideoCatagory::findOrfail($id);
        if ($vid->delete()) {
            return response(['message' => 'VideoCatagory Deleted successfully']);
        }
        return "Error while deleting";
    }
}

