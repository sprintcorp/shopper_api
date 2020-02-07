<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Http\Request;

class SellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seller = Seller::has('products')->get();
        return $this->showAll($seller);
    }

    public function show($id)
    {
        $seller = Seller::has('products')->findorFail($id);
        return $this->showOne($seller);
    }

}
