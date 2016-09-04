<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;

class ReportsConsumption extends Controller
{
    public function getConsumption() {
        $consumption = DB::table('consumption')
            //->leftJoin('products', 'consumption.product_id', '=', 'products.id')
            ->orderBy('id_of_consumption')
            ->get();

        return $consumption;
    }

    public function getConsumptionByID($id) {
        $consumption = DB::table('consumption')
            //->leftJoin('products', 'consumption.product_id', '=', 'products.id')
            ->where('id_of_consumption', '=', $id)
            ->orderBy('id_of_consumption')
            ->get();

        return $consumption;
    }

    public function getConsumptionByTime($from, $to) {
        $from = date('Y-m-d', strtotime($from));
        $to = date('Y-m-d', strtotime($to));

        $consumption = DB::table('consumption')
            //->leftJoin('products', 'consumption.product_id', '=', 'products.id')
            ->whereBetween('date', [$from, $to])
            ->orderBy('id_of_consumption')
            ->get();

        return $consumption;
    }

    /* OLD
     public function addConsumption($product_id, $count) {
        if(DB::table('consumption')->insert([
            'product_id' => $product_id,
            'count_of_consumption' => $count
        ])) {
            $new_count = intval(DB::table('products')
                    ->select('count')
                    ->where('id', '=', $product_id)
                    ->first()
                    ->count) - $count;
            if(DB::table('products')
                ->where('id', '=', $product_id)
                ->update(['count' => $new_count])) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            return 'false';
        }
    }
    */
    // NEW
    public function addConsumption($provider, $date = null) {
        if($date != null) {
            if(DB::table('consumption')->insert([
                'provider' => $provider,
                'date' => $date
            ])) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            if(DB::table('consumption')->insert([
                'provider' => $provider
            ])) {
                return 'true';
            } else {
                return 'false';
            }
        }
    }
    
    public function getCountOfProductsForConsumption($id) {
        $products = DB::table('consumption_products')
            ->leftJoin('products', 'consumption_products.id_of_product', '=', 'products.id')
            ->where('parent_consumption_id', '=', $id)
            ->count();
        return $products;
    }

    public function getProductsOfConsumption($id) {
        $products = DB::table('consumption_products')
            ->leftJoin('products', 'consumption_products.id_of_product', '=', 'products.id')
            ->where('parent_consumption_id', '=', $id)
            ->get();
        return $products;
    }

    public function addProductsToConsumption($id_of_consumption, $id_of_product, $count, $price) {
        if(DB::table('consumption_products')->insert([
            'id_of_product' => $id_of_product,
            'parent_consumption_id' => $id_of_consumption,
            'count_of_consumption' => $count,
            'price_of_consumption' => $price
        ])) {
            $new_count = intval(DB::table('products')
                    ->select('count')
                    ->where('id', '=', $id_of_product)
                    ->first()
                    ->count) - $count;
            if(DB::table('products')
                ->where('id', '=', $id_of_product)
                ->update(['count' => $new_count])) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            return 'false';
        }
    }

    public function deleteProduct($id) {
        if(DB::table('consumption_products')
            ->where('unique_id', '=', $id)
            ->delete()) {
            return 'true';
        } else {
            return 'false';
        }
    }

    public function getTotal($consumption_id) {
        $products_of_consumption = $this->getProductsOfConsumption($consumption_id);
        $price = 0;
        if(count($products_of_consumption) != 0) {
            foreach ($products_of_consumption as $product) {
                if($product->price_of_consumption != 0) {
                    $price += round((floatval($product->price_of_consumption) * intval($product->count_of_consumption)), 2);
                } else {
                    $price += round((floatval($product->price) * intval($product->count_of_consumption)), 2);
                }
            }
        }
        return $price;
    }

    public function deleteConsumption($consumption_id) {
        if(DB::table('consumption')
            ->where('id_of_consumption', '=', $consumption_id)
            ->delete()) {
            return 'true';
        } else {
            return 'false';
        }
    }

    /*public function updateConsumption($id, $name, $quantity, $price) {
        if(DB::table('consumption_products')
            ->where('unique_id', '=', $id)
            ->update([
                'name' => $name,
                'count' => $quantity,
                'price' => $price
            ]))
        {
            return 'true';
        } else {
            return 'false';
        }
    }*/

    public function updateCountOfProductConsumption($id, $product_count) {
        if(DB::table('consumption_products')
            ->where('unique_id', '=', $id)
            ->update(['count_of_consumption' => $product_count])) {
            return 'true';
        } else {
            return 'false';
        }
    }
}
