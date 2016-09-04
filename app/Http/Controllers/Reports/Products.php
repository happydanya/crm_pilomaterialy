<?php

/**
 * Author: Dmitriev V. Daniil
 * Date: 04.09.2016
 */

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Products extends Controller
{
    /**
     * Main function - render page 'index'
     *
     * @param null $data
     * @return mixed
     */

    public function index($data = null) {
        return view('products', $data);
    }

    /**
     * Get all products from DB
     * @return mixed
     */

    public function getProducts() {
        $products = DB::table('products')
            ->orderBy('id')
            ->get();
        return $products;
    }

    /**
     * Add new product
     *
     * @param $name
     * @param $count
     * @param $price
     * @param string $description = by default: empty string
     * @return string
     */

    public function addProducts($name, $count, $price, $description = '') {
        if(DB::table('products')->insert([
            'name' => $name,
            'description' => $description,
            'count' => $count,
            'price' => $price
        ])) {
            return 'true';
        } else {
            return 'false';
        }
    }

    /**
     * If we need only one product
     * May be useful in future
     *
     * @param $id
     * @return mixed
     */

    public function getProductNameByID($id) {
        $product = DB::table('products')
            ->where('id', '=', $id)
            ->orderBy('id')
            ->get();
        return $product;
    }

    /**
     * delete product
     *
     * @param $id
     * @return string
     */

    public function deleteProduct($id) {
        if(DB::table('products')
            ->where('id', '=', $id)
            ->delete()) {
            return 'true';
        } else {
            return 'false';
        }
    }

    /**
     * New function for updating count
     * on ajax-request from front-end
     *
     * @param $id
     * @param $count
     * @return string
     */

    public function updateCountOfProduct($id, $count) {
        if(DB::table('products')
            ->where('id', '=', $id)
            ->update(['count' => $count])) {
            return 'true';
        } else {
            return 'false';
        }
    }
}
