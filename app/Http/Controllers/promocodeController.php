<?php

namespace App\Http\Controllers;

use App\Models\promocode;
use App\Http\Controllers\Controller;
use App\Http\Resources\promocodeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class promocodeController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $promo = QueryBuilder::for(promocode::class)
        ->allowedFilters([
            AllowedFilter::exact('name'),
        ])
        ->paginate()
        ->appends(request()->query());
        
        return promocodeResource::collection($promo);
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
            'percentage' => 'required|max:255',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $promo = promocode::create($data);

        return response([ 'promocode' => new promocodeResource($promo), 'message' => 'Created promoeo successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $promocode = promocode::find($id);
        if ($promocode) {
            return new promocodeResource($promocode);
        }
        return "promocode Not found"; 
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
        $promo = promocode::find($id);
        $promo->update($request->all());
        return response(['promocode' => new promocodeResource($promo), 'message' => 'Update promocode successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promo = promocode::findOrfail($id);
        if ($promo->delete()) {
            return response(['message' => 'promocode Deleted successfully']);
        }
        return "Error while deleting";
    }
}

