<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;

class Login extends Controller {

    /**
     * @param $id
     * @return string
     * Return current User
     */
    public function returnUser($id) {
        $obj = new User();
        return array($obj->findOrFail($id));
    }

}