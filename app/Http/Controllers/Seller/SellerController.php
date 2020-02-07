<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seller = Seller::has('products')->get();
        return response()->json(['data'=>$seller],200);
    }

    public function show($id)
    {
        $seller = Seller::has('products')->findorFail($id);
        return response()->json(['data'=>$seller],200);
    }

}
