<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cities;

class CitiesController extends Controller
{
    public function searchByParams(Request $request)
    {
        try {
            $size = $request->input('size');
            $input = $request->input('input');

            $cities = Cities::where('nombre','like',"%$input%")
            ->paginate($request->input('size'));

            return response()->json($cities);
        } catch (\Exception $e) {
           throw $e;
        }
    }
}
