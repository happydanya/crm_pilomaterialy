<?php

/**
 * Author: Dmitriev V. Daniil
 * Date: 04.09.2016
 */

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\ReportsComing;
use App\Http\Controllers\ReportsConsumption;
use Illuminate\Http\Request;

use DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Remains extends Controller
{

    /**
     * Description:
     * Add in products new values
     * (consumption - consumption of this date,
     * coming - coming of this date),
     * and recalculate new count of products
     *
     * @param $from = start 'between'
     * @param $to = end 'between'
     * @return mixed = array or object, where is remains
     */

    public function getRemainsByTime($from, $to) {
        $controller_consumption = new ReportsConsumption();
        $controller_coming = new ReportsComing();
        $controller_products = new Products();
        $products = $controller_products->getProducts();
        $consumptions = $controller_consumption->getConsumptionByTime($from, $to);
        $comings = $controller_coming->getComingsByTime($from, $to);

        foreach ($products as $product) {
            foreach ($consumptions as $consumption) {
                $products_consumptions = $controller_consumption
                    ->getProductsOfConsumption($consumption->id_of_consumption);
                foreach ($products_consumptions as $product_consumption) {
                    if($product_consumption->id_of_product == $product->id) {
                        $product->count = intval($product->count) + intval($product_consumption->count_of_consumption);
                        $product->consumption = intval($product_consumption->count_of_consumption); // add new object to current product
                    }
                }
            }
            foreach ($comings as $coming) {
                $products_coming = $controller_coming
                    ->getProductsOfComing($coming->id_of_coming);
                foreach ($products_coming as $product_coming) {
                    if($product_coming->id_of_product == $product->id) {
                        $product->count = intval($product->count) - intval($product_coming->count_of_coming);
                        $product->coming = intval($product_coming->count_of_coming); // and there: add new object to current product
                    }
                }
            }

            /**
             * Without this small block of code - logic has broken,
             * because we need this vars for generate table
             */

            if(!isset($product->coming)) {
                $product->coming = 0;
            }
            if(!isset($product->consumption)) {
                $product->consumption = 0;
            }
        }
        return $products;
    }
}
