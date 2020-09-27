<?php

namespace App\Http\Controllers;

use App\Models\VideoCatagory;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoCatagoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoCatagoryController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vid = VideoCatagory::all();
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
        $vid = new VideoCatagory;
        $vid->name = $request->input('name');
        $vid->description = $request->input('description');
        $vid->save();
        // return new VideoCatagoryResource($vid);
        return response([ 'VideoCatagory' => new VideoCatagoryResource($vid), 'message' => 'Created VideoCatagory successfully'], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $VideoCatagory = VideoCatagory::find($id); //id comes from route
        if ($VideoCatagory) {
            return new VideoCatagoryResource($VideoCatagory);
        }
        return "VideoCatagory Not found"; // temporary error
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
        $vid = VideoCatagory::find($id);
        $vid->update($request->all());
        return response(['VideoCatagory' => new VideoCatagoryResource($vid), 'message' => 'Update VideoCatagory successfully'], 200);
        // return new VideoCatagoryResource($vid);
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
            // return new VideoCatagoryResource($vid);
        }
        return "Error while deleting";
    }
}

