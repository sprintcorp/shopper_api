<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @param Buyer $buyer
     * @return void
     */
    public function index(Buyer $buyer)
    {
        $product = $buyer->transactions()->with('product')->get()->pluck('product');
        return $this->showAll($product);
    }

}
