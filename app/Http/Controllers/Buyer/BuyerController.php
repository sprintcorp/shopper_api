<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerController extends ApiController
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
//        $buyer = Buyer::has('transactions')->get();
        $buyer = Buyer::all();
        return $this->showAll($buyer,200);
    }

    /**
     * Display the specified resource.
     *
     * @param Buyer $buyer
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
//        $buyer = Buyer::has('transactions')->findorFail($id);
        return $this->showOne($buyer,200);
    }


}
