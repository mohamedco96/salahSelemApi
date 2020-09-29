<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class BannerController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $banner = QueryBuilder::for(Banner::class)
        ->allowedFilters([
            AllowedFilter::exact('name'),
        ])
        ->paginate()
        ->appends(request()->query());
        
        return BannerResource::collection($banner);
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
            'name' => 'required|max:255',
            'link' => 'required|max:255',
            'image' => 'required|max:255',
            'body' => 'max:255',
            'description' => 'max:255',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $banner = Banner::create($data);

        return response([ 'Banner' => new BannerResource($banner), 'message' => 'Created bannereo successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Banner = Banner::find($id);
        if ($Banner) {
            return new BannerResource($Banner);
        }
        return "Banner Not found"; 
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
        $banner = Banner::find($id);
        $banner->update($request->all());
        return response(['Banner' => new BannerResource($banner), 'message' => 'Update Banner successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $banner = Banner::findOrfail($id);
        if ($banner->delete()) {
            return response(['message' => 'Banner Deleted successfully']);
        }
        return "Error while deleting";
    }
}

