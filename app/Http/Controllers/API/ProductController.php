<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /* Ürünleri Listeleme */
    public function index()
    {
        $all_products = ProductModel::all();
        return $all_products ? response()->json(['message' => 'All products listed.', 'ProductInfo:' => $all_products], 200) : response()->json(['failed' => 'Undefined error.']);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /* Ürün oluşturma */
    public function store(Request $request)
    {
        $create = ProductModel::create([
            'provider_id' => Auth::id(),
            'name' => $request->get('name'),
            'order_code' => Auth::id() . "-" . Str::random(15) . rand(0,5),
            'quantity' => $request->get('quantity'),
            'address' => $request->get('address'),
            'price' => $request->get('price'),
            'shipping_date' => Carbon::now()->addDay(3),
        ]);

        return $create ? response()->json(['message' => 'Product saved', 'ProductInfo:' => $create], 200) : response()->json(['failed' => 'Not created product.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $details = ProductModel::where('id', $id)->get();
        return $details ? response()->json(['message' => 'success', 'ProductInfo:' => $details], 200) : response()->json(['failed' => 'Undefined error.']);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /* Ürün güncelleme işlemleri - shippingDate tarihine göre */
    public function update(Request $request)
    {
        $update = ProductModel::where('id', $request->get('id'))->whereDate('shipping_date', '>', Carbon::now())->
        update([
            'name' => $request->get('name'),
            'quantity' => $request->get('quantity'),
            'price' => $request->get('price'),
            'address' => $request->get('address')
        ]);

        return $update ? response()->json(['message' => 'Product updated.', 'productId' => $request->get('id'), 'productInfo' => ProductModel::where('id', $request->get('id'))->get()]) : response()->json(['failed' => 'Not updated product.']);
    }


}
