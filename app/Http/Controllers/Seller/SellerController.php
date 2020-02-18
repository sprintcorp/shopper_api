<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Http\Request;

class SellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $seller = Seller::has('products')->get();
        $seller = Seller::all();
        return $this->showAll($seller);
    }

    public function show(Seller $seller)
    {
        return $this->showOne($seller);
    }

}
