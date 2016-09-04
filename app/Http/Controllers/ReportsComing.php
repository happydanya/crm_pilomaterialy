<?php

/**
 * Author: Dmitriev V. Daniil
 * Date: 04.09.2016
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use App\Http\Requests;

class ReportsComing extends Controller
{
    /**
     * Getting all of Comings
     * @return mixed
     */

    public function getComings() {
        $comings = DB::table('coming')
            ->orderBy('id_of_coming')
            ->get();

        return $comings;
    }

    /**
     * Get same coming by ID
     * @param $id
     * @return mixed
     */

    public function getComingsByID($id) {
        $comings = DB::table('coming')
            ->where('id_of_coming', '=', $id)
            ->orderBy('id_of_coming')
            ->get();

        return $comings;
    }

    /**
     * Get array of comings by date (from-to)
     *
     * @param $from
     * @param $to
     * @return mixed
     */

    public function getComingsByTime($from, $to) {
        $from = date('Y-m-d', strtotime($from)); // format the date for DB
        $to = date('Y-m-d', strtotime($to));

        $comings = DB::table('coming')
            ->whereBetween('date', [$from, $to])
            ->orderBy('id_of_coming')
            ->get();

        return $comings;
    }

    /**
     * Add new Coming
     *
     * @param $provider
     * @param null $date
     * @return string
     */

    public function addComing($provider, $date = null) {
        if($date != null) {
            if(DB::table('coming')->insert([
                'provider' => $provider,
                'date' => $date
            ])) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            if(DB::table('coming')->insert([
                'provider' => $provider
            ])) {
                return 'true';
            } else {
                return 'false';
            }
        }
    }

    /**
     * Get count of products in same coming
     *
     * @param $id
     * @return mixed
     */

    public function getCountOfProductsForComing($id) {
        $products = DB::table('coming_products')
            ->leftJoin('products', 'products.id', '=', 'coming_products.id_of_product')
            ->where('parent_coming_id', '=', $id)
            ->count();
        return $products;
    }

    /**
     * Get products from coming (by ID)
     *
     * @param $id
     * @return mixed
     */

    public function getProductsOfComing($id) {
        $products = DB::table('coming_products')
            ->leftJoin('products', 'products.id', '=', 'coming_products.id_of_product')
            ->where('parent_coming_id', '=', $id)
            ->orderBy('unique_id')
            ->get();
        return $products;
    }

    /**
     * Add new product to coming
     *
     * @param $id_of_coming
     * @param $id_of_product
     * @param $count
     * @param $price
     * @return string
     */

    public function addProductsToComing($id_of_coming, $id_of_product, $count, $price) {
        if(DB::table('coming_products')->insert([
            'parent_coming_id' => $id_of_coming,
            'count_of_coming' => $count,
            'price_of_coming' => $price,
            'id_of_product' => $id_of_product
        ]))
        {
            $new_count = intval(DB::table('products')
                                ->select('count')
                                ->where('id', '=', $id_of_product)
                                ->first()
                                ->count) + $count; // add count of products from coming to default count of products
            if(DB::table('products')
                ->where('id', '=', $id_of_product)
                ->update(['count' => $new_count]))
            {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            return 'false';
        }
    }

    /**
     * Delete product from coming
     *
     * @param $id
     * @return string
     */

    public function deleteProduct($id) {
        if(DB::table('coming_products')
            ->where('unique_id', '=', $id)
            ->delete()) {
            return 'true';
        } else {
            return 'false';
        }
    }

    /**
     * Delete coming
     *
     * @param $coming_id
     * @return string
     */

    public function deleteComing($coming_id) {
        if(DB::table('coming')
            ->where('id_of_coming', '=', $coming_id)
            ->delete()) { // delete coming
            if(DB::table('coming_products')
            ->where('parent_coming_id', '=', $coming_id)
            ->delete()) { // and delete all products from this coming
                return 'true';
            } else {
                return 'false';
            }
        } else {
            return 'false';
        }
    }

    /**
     * Get total price of coming products
     *
     * All products have price from coming or default price.
     * And if product price from coming == 0 - set default price for coming product
     *
     * @param $coming_id
     * @return int
     */

    public function getTotal($coming_id) {
        $products_of_coming = $this->getProductsOfComing($coming_id);
        $price = 0;
        if(count($products_of_coming) != 0) {
            foreach ($products_of_coming as $product) {
                if($product->price_of_coming != 0) { // if price of product from coming != 0 add in total
                    $price += round((floatval($product->price_of_coming) * intval($product->count_of_coming)), 2);
                } else { // else - add in total default price
                    $price += round((floatval($product->price) * intval($product->count_of_coming)), 2);
                }
            }
        }
        return $price;
    }

    /**
     * Updating count of product from the coming
     *
     * @param $id
     * @param $product_count
     * @return string
     */

    public function updateCountOfProductComing($id, $product_count) {
        if(DB::table('coming_products')
            ->where('unique_id', '=', $id)
            ->update(['count_of_coming' => $product_count])) {
            return 'true';
        } else {
            return 'false';
        }
    }

}
