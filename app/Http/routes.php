<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Login;
use \App\Http\Controllers\Reports\Products;
use App\Http\Controllers\Reports\Remains;
use \App\Http\Controllers\ReportsComing;
use \App\Http\Controllers\ReportsConsumption;
use \App\Http\Controllers\MailSender;
use App\User;

/**
 * My functions for work with data
 * START
 */

/**
 * @param $title = title of page
 * @return array = array of parameters by default
 */

function defaultLayout($title) {
    return $data = array(
        'menu' => View::make('menu')->render(),
        'header' => View::make('header', ['title' => $title])->render(),
        'footer' => View::make('footer')->render(),
        'title_heading' => $title
    );
}

/**
 * Small function for my comfort
 *
 * @param $var
 * @return bool
 */

function checkValue($var) {
    if(isset($var) && $var != '') {
        return true;
    } else {
        return false;
    }
}

/**
 * My functions for work with data
 * END
 */

/**
 * Unusable path, but maybe be useful in future
 * USER ROUTE
 * START
 */

// User page

Route::get('user/{id}', function($id)
{
    $title = 'Страница пользователя';
    $data = defaultLayout($title);
    $var = new Login();
    $data['user'] = $var->returnUser($id)[0];

    return View::make('user', $data);
});

// send inviting email

Route::post('/sendEmailToAdmin', function()
{
    if(checkValue($_POST['email']) && checkValue($_POST['name']))
    {
        $mailer = new MailSender();
        if($mailer->sendEmailInvite($_POST['email'], $_POST['name']) == 'true') {
            return 'true';
        } else {
            return 'false';
        }
    } else {
        return 'false';
    }
});

// send email for raising status of current user

Route::post('/sendEmailRaisingStatus', function()
{
    if(checkValue($_POST['text']) && checkValue($_POST['user_id']) && checkValue($_POST['email'])) {
        $mailer = new MailSender();
        $mailer->sendEmailRaisingStatus($_POST['text'], $_POST['user_id'], $_POST['email']);
    }
});

// page of inviting

Route::get('/invite', function()
{
    $title = 'Приглашение пользователя в систему';
    $data = defaultLayout($title);

    return View::make('auth.invite', $data);
});

// page of raising status

Route::get('/raisingStatus', function()
{
    $title = 'Запрос повышения статуса';
    $data = defaultLayout($title);

    return View::make('auth.raisingStatus', $data);
});

// page for complete raising status

Route::get('/raisingStatus/{id}/{token}', function($id, $token)
{
    $title = 'Подтверждение статуса';
    $data = defaultLayout($title);
    $controller = new User();
    if($controller->checkToken($token, $id) === 'true') { // function in \App\User
        $data['checked'] = 'true';
    } else {
        $data['checked'] = 'false';
    }

    return View::make('auth.raisingStatus', $data);
});

/**
 * USER ROUTE
 * END
 *
 * AUTH ROUTES
 * START
 * location: class Route in "Illuminate" folder (default files of framework)
 */

Route::auth();

/**
 * AUTH ROUTES
 * END
 *
 * HOME ROUTES
 * START
 */

Route::get('/', function()
{
    $title = 'Главная';
    $data = defaultLayout($title);

    return View::make('home', $data);
});

Route::get('/home', function() {

    $home_controller = new HomeController();
    $title = 'Главная';
    $data = defaultLayout($title);

    return $home_controller->index($data);
});

/**
 * HOME ROUTES
 * END
 *
 * REMAINS ROUTES
 * START
 */


// Main page

Route::get('/remains', function() {
    $products_controller = new Products();
    $title = 'Отчет по остатку';
    $data = defaultLayout($title);
    $data['url'] = url('/products');
    $data['url_date'] = url('/sortByDateRemains');
    $data['url_delete'] = url('/deleteProducts');
    $products = $products_controller->getProducts();

    return View::make('remains', $data)->with(array('products' => $products));
});

// if set date in post request to this page -> get remains by date

Route::post('/sortByDateRemains', function() {
    $remains_controller = new Remains();
    if(checkValue($_POST['from_date']) && checkValue($_POST['to_date']))
    {
        setlocale(LC_ALL, 'ru_RU.utf8');
        $from = date("m.d.y", strtotime($_POST['from_date']));
        $to = date("m.d.y", strtotime($_POST['to_date']));
        $title = 'Отчет по остатку с ' . $from . ' до ' . $to;
        $data = defaultLayout($title);
        $data['url'] = url('/remains');
        $data['url_delete'] = url('/deleteRemains');
        $data['url_date'] = url('/sortByDateRemains');
        $products = $remains_controller->getRemainsByTime($_POST['from_date'], $_POST['to_date']);
        foreach ($products as $product) {
            $product->total = round(intval($product->count) * floatval($product->price), 2);
        }
        return View::make('remains', $data)->with(array('products' => $products));

    } else {
        $title = 'Отчет по остатку';
        $data = defaultLayout($title);
        $data['url'] = url('/remains');
        $data['url_delete'] = url('/deleteRemains');
        $data['url_date'] = url('/sortByDateRemains');
        $products = '';
        return View::make('remains', $data)->with(array('products' => $products));
    }
});

/**
 * REMAINS ROUTES
 * END
 *
 * PRODUCTS ROUTES
 * START
 */

// Main page

Route::get('/products', function() {

    $products_controller = new Products();
    $title = 'Каталог продуктов';
    $data = defaultLayout($title);
    $data['url'] = url('/products');
    $data['url_delete'] = url('/deleteProducts');
    $products = $products_controller->getProducts();
    return View::make('products', $data)->with(array('products' => $products));

});

// Add new product

Route::post('/products', function() {

    $products_controller = new Products();
    if(checkValue($_POST['name']) && checkValue($_POST['count']) && checkValue($_POST['price'])) {
        if($products_controller->addProducts(
            $_POST['name'], $_POST['count'],
            $_POST['price'], $_POST['description'])
        ) {
            return 'true';
        } else {
            return 'false';
        }
    } else {
        return 'false';
    }
});

// POST request for update count of product by id

Route::post('/updateCountProduct', function() {

    $products_controller = new Products();
    if(checkValue($_POST['id']) && checkValue($_POST['count'])) {
        if($products_controller->updateCountOfProduct(
            $_POST['id'], $_POST['count'])
        ) {
            return 'true';
        } else {
            return 'false';
        }
    } else {
        return 'false';
    }
});

// POST request for delete product by id

Route::post('/deleteProducts', function() {

    $products_controller = new Products();
    if(checkValue($_POST['id'])) {
        if($products_controller->deleteProduct($_POST['id'])) {
            return 'true';
        } else {
            return 'false';
        }
    } else {
        return 'false';
    }
});

/**
 * PRODUCTS ROUTES
 * END
 *
 * COMING ROUTES
 * START
 */

// Main page

Route::get('/coming', function() {

    $products_controller = new Products();
    $controller = new ReportsComing();
    $title = 'Каталог приходов';
    $data = defaultLayout($title);
    $data['url'] = url('/coming');
    $data['url_delete'] = url('/deleteComing');
    $data['url_date'] = url('/sortByDateComing');
    $coming = $controller->getComings();
    foreach ($coming as $come) {
        $come->total = $controller->getTotal($come->id_of_coming);
        $come->count = $controller->getCountOfProductsForComing($come->id_of_coming);
    }
    $products = $products_controller->getProducts();
    return View::make('coming', $data)->with(array('comings' => $coming, 'products' => $products));
});

// Get some coming by ID

Route::get('/coming/{id}', function($id)
{
    $controller = new ReportsComing();
    $products_controller = new Products();
    $current_coming = $controller->getComingsByID($id)[0]; // [0] because function return array
    $title = 'Страница прихода №' . $id . ' от поставщика ' . $current_coming->provider;
    $data = defaultLayout($title);
    $products = $products_controller->getProducts();
    $coming = $controller->getProductsOfComing($id);
    $data['url'] = url('/coming/' . $id . '/addProduct');
    $data['url_delete'] = url('/deleteProductFromComing');
    return View::make('comingID', $data)->with(array('comings' => $coming, 'products' => $products));
});

// Add product in current coming

Route::post('/coming/{id}/addProduct', function($id) {
    if(checkValue($_POST['count_of_coming']) && checkValue($_POST['id']) && checkValue($_POST['price'])) {
        $controller = new ReportsComing();
        if($controller->addProductsToComing($id, $_POST['id'], $_POST['count_of_coming'], $_POST['price'])) {
            return 'true';
        } else {
            return 'false';
        }
    } else {
        return 'false';
    }
});

// delete product from coming by id of product

Route::post('/deleteProductFromComing', function() {
    if(checkValue($_POST['id_of_product'])) {
        $controller = new ReportsComing();
        if($controller->deleteProduct($_POST['id_of_product'])) {
            return 'true';
        } else {
            return 'false';
        }
    } else {
        return 'false';
    }
});

// Updating count of product in coming

Route::post('/updateCountOfProductFromComing', function() {
    if(checkValue($_POST['id']) && checkValue($_POST['count'])) {
        $controller = new ReportsComing();
        if($controller->updateCountOfProductComing($_POST['id'], $_POST['count'])) {
            return 'true';
        } else {
            return 'false';
        }
    } else {
        return 'false';
    }
});

// Add new coming


Route::post('/coming', function() {

    $controller = new ReportsComing();
    if(checkValue($_POST['provider'])) {
        if(checkValue($_POST['date_get'])) {
            $date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $_POST['date_get'])));
            if($date === '1970-01-01 00:00:00') {
                // default value, if can't defined DATETIME format
                // if true - don't add date and set current timestamp in DB
                if($controller->addComing(
                    $_POST['provider']
                )
                ) {
                    return 'true';
                } else {
                    return 'false';
                }
            } else {
                if($controller->addComing(
                    $_POST['provider'], $date
                )
                ) {
                    return 'true';
                } else {
                    return 'false';
                }
            }
        } else {
            if($controller->addComing(
                $_POST['provider']
            )
            ) {
                return 'true';
            } else {
                return 'false';
            }
        }
    } else {
        return 'false';
    }
});

// Sort by date all comings

Route::post('/sortByDateComing', function() {

        $products_controller = new Products();
        $controller = new ReportsComing();
        if(checkValue($_POST['from_date']) && checkValue($_POST['to_date']))
        {
            $from = date("m.d.y", strtotime($_POST['from_date']));
            $to = date("m.d.y", strtotime($_POST['to_date']));
            $title = 'Отчет по приходам с ' . $from . ' до ' . $to;
            $data = defaultLayout($title);
            $data['url'] = url('/coming');
            $data['url_delete'] = url('/deleteComing');
            $data['url_date'] = url('/sortByDateComing');
            $comings = $controller->getComingsByTime($_POST['from_date'], $_POST['to_date']);
            foreach ($comings as $come) {
                $come->total = $controller->getTotal($come->id_of_coming);
                $come->count = $controller->getCountOfProductsForComing($come->id_of_coming);
            }
            $products = $products_controller
                                        ->getProducts();
            return View::make('coming', $data)->with(array('comings' => $comings, 'products' => $products));

        } else {
            $title = 'Каталог расходов';
            $data = defaultLayout($title);
            $data['url'] = url('/coming');
            $data['url_delete'] = url('/deleteComing');
            $data['url_date'] = url('/sortByDateComing');
            $comings = '';
            $products = $products_controller->getProducts();
            return View::make('coming', $data)->with(array('comings' => $comings, 'products' => $products));
        }
});

// Delete coming.

Route::post('/deleteComing', function() {

    $controller = new ReportsComing();
    if(checkValue($_POST['coming_id'])) {
        if($controller->deleteComing($_POST['coming_id'])) {
            return 'true';
        } else {
            return 'false';
        }
    } else {
        return 'false';
    }
});

/**
 * COMING ROUTES
 * END
 *
 * CONSUMPTIONS ROUTES
 * START
 */

// Main page

Route::get('/consumption', function() {

    $products_controller = new Products();
    $controller = new ReportsConsumption();
    $title = 'Каталог расходов';
    $data = defaultLayout($title);
    $data['url'] = url('/consumption');
    $data['url_delete'] = url('/deleteConsumption');
    $data['url_date'] = url('/sortByDateConsumption');
    $products = $products_controller
                        ->getProducts();
    $consumptions = $controller
                        ->getConsumption();
    foreach ($consumptions as $consumption) {
        $consumption->total = $controller
                                ->getTotal($consumption->id_of_consumption);
        $consumption->count = $controller
                                ->getCountOfProductsForConsumption($consumption->id_of_consumption);
    }
    return View::make('consumption', $data)->with(array('consumption' => $consumptions, 'products' => $products));

});

// Add new consumption

Route::post('/consumption', function() {

    $controller = new ReportsConsumption();
    if(checkValue($_POST['provider'])) {
        if(checkValue($_POST['date_get'])) {
            $date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $_POST['date_get'])));
            if($date === '1970-01-01 00:00:00') { // same problem is in coming - read
                if($controller->addConsumption(
                    $_POST['provider']
                )
                ) {
                    return 'true';
                } else {
                    return 'false';
                }
            } else {
                if($controller->addConsumption(
                    $_POST['provider'], $date
                )
                ) {
                    return 'true';
                } else {
                    return 'false';
                }
            }
        } else {
            if($controller->addConsumption(
                $_POST['provider']
            )
            ) {
                return 'true';
            } else {
                return 'false';
            }
        }
    } else {
        return 'false';
    }
});

// Delete consumption.

Route::post('/deleteConsumption', function() {

    $controller = new ReportsConsumption();
    if(checkValue($_POST['consumption_id'])) {
        if($controller->deleteConsumption($_POST['consumption_id'])) {
            return 'true';
        } else {
            return 'false';
        }
    } else {
        return 'false';
    }
});

// Updating count of product in consumption

Route::post('/updateCountOfProductFromConsumption', function() {
    if(checkValue($_POST['id']) && checkValue($_POST['count'])) {
        $controller = new ReportsConsumption();
        if($controller->updateCountOfProductConsumption($_POST['id'], $_POST['count'])) {
            return 'true';
        } else {
            return 'false';
        }
    } else {
        return 'false';
    }
});

// Sort by date all consumptions

Route::post('/sortByDateConsumption', function() {
    $products_controller = new Products();
    $controller = new ReportsConsumption();
    if(checkValue($_POST['from_date']) && checkValue($_POST['to_date'])) {
        $from = date("m.d.y", strtotime($_POST['from_date']));
        $to = date("m.d.y", strtotime($_POST['to_date']));
        $title = 'Отчет по расходам с ' . $from . ' до ' . $to;
        $data = defaultLayout($title);
        $data['url'] = url('/consumption');
        $data['url_delete'] = url('/deleteConsumption');
        $data['url_date'] = url('/sortByDateConsumption');
        $consumptions = $controller->getConsumptionByTime($_POST['from_date'], $_POST['to_date']);
        $products = $products_controller->getProducts();
        foreach ($consumptions as $consumption) {
            $consumption->total = $controller->getTotal($consumption->id_of_consumption);
            $consumption->count = $controller->getCountOfProductsForConsumption($consumption->id_of_consumption);
        }
        return View::make('consumption', $data)->with(array('consumption' => $consumptions, 'products' => $products));

    } else {
        $title = 'Каталог расходов';
        $data = defaultLayout($title);
        $data['url'] = url('/consumption');
        $data['url_delete'] = url('/deleteConsumption');
        $data['url_date'] = url('/sortByDateConsumption');
        $consumption = '';
        $products = $products_controller->getProducts();
        return View::make('consumption', $data)->with(array('consumption' => $consumption, 'products' => $products));
    }
});

// Get consumption by ID

Route::get('/consumption/{id}', function($id)
{
    $products_controller = new Products();
    $controller = new ReportsConsumption();
    $current_coming = $controller->getConsumptionByID($id)[0];
    $title = 'Страница расхода №' . $id . ' от поставщика ' . $current_coming->provider;
    $data = defaultLayout($title);
    $products = $products_controller->getProducts();
    $consumptions = $controller->getProductsOfConsumption($id);
    $data['url'] = url('/consumption/' . $id . '/addProduct');
    $data['url_delete'] = url('/deleteProductFromConsumption');
    return View::make('consumptionID', $data)->with(array('consumption' => $consumptions, 'products' => $products));
});

// Add product to current consumption

Route::post('/consumption/{id}/addProduct', function($id) {
    if(checkValue($_POST['product_id']) && checkValue($_POST['count']) && checkValue($_POST['price'])) {
        $controller = new ReportsConsumption();
            $controller->addProductsToConsumption(
                $id, $_POST['product_id'], $_POST['count'], $_POST['price']
            );
            return 'true';
    } else {
        return 'false';
    }
});

// Delete product from current consumption

Route::post('/deleteProductFromConsumption', function() {
    if(checkValue($_POST['id_of_product'])) {
        $controller = new ReportsConsumption();
        if($controller->deleteProduct($_POST['id_of_product'])) {
            return 'true';
        } else {
            return 'false';
        }
    } else {
        return 'false';
    }
});

/**
 * CONSUMPTIONS ROUTES
 * END
 */
