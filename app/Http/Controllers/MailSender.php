<?php

namespace App\Http\Controllers;

use Mail;
use DB;
use App\User;
use App\Http\Controllers\Controller;

use App\Http\Requests;

class MailSender extends Controller
{

    /**
     * Function for sending invitation in the system by email
     *
     * @param $user_email = email for invite
     * @param $name = name of new user
     * @return string
     */

    public function sendEmailInvite($user_email, $name) {
        $user = DB::table('users')
            ->where('email', '=', $user_email)
            ->get();
        if(count($user) == 0) {
            $pass = str_random(8); // generate random pass
            $data = array( // data of user
                'email' => $user_email,
                'password' => $pass,
                'name' => $name
            );
            User::create(array( // creating new user
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password'])
            ));
            if(Mail::send('auth.emails.invite', $data, function($message) use ($user_email, $name)
            {
                $message->from('admin@pilomaterialy.dp.ua', 'Администрация системы управления поступлениями');
                $message->to($user_email)->subject('Добро пожаловать, ' . $name . '!');
            })) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            return 'false';
        }
    }

    /**
     * Function for sending email to admin of system
     * for raising status of current user
     *
     * @param $text
     * @param $user_id
     * @param $user_email
     * @return string
     */

    public function sendEmailRaisingStatus($text, $user_id, $user_email) {
        $var = str_random(); // generate token for link of 'confirmation status' page
        DB::table('users')
            ->where('id', '=', $user_id)
            ->update(array('status_token' => $var));

        $users = DB::table('users')
            ->where('status', '=', 0) // getting admin of system
            ->get();

        foreach ($users as $user) {
            $data = array( // data for view 'auth.emails.raisingStatus.blade.php'
                'id' => $user_id,
                'token' => $var,
                'email' => $user->email,
                'email_of_current' => $user_email,
                'name' => $user->name,
                'text' => $text
            );
            if(Mail::send('auth.emails.raisingStatus', $data, function($message) use ($data)
            {
                $message->from('admin@pilomaterialy.dp.ua', 'Администрация системы управления поступлениями');
                $message->to($data['email'])->subject('Запрос на повышение статуса');
            })) {
                // if all is OK - continue
                continue;
            } else {
                // if not OK - return
                return 'false';
            }
        }
        return 'true';
    }
}
