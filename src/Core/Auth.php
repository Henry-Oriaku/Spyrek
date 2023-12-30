<?php

namespace Spyrek\Core;

use Spyrek\Models\User;

class Auth
{
    public function user()
    {
        $token = str_replace("Bearer ", "", request()->headers("AUTHORIZATION"));
        if ($token) {
            $id = decrypt($token, APP_KEY);
            $user = new User;
            $user = $user->first(["id" => $id]);
            if ($user) {
                return $user;
            }
        }
        return false;
    }

    /**
     * Logs a User in and Return the Bearer Token
     */
    public function authenticate($email, $password, $id = null)
    {
        $user = new User();
        if ($id) {
            $user_detail = $user->first(["id" => $id]);
        } else {
            $user_detail = $user->first(["email" => $email, "password" => $password]);
        }
        if ($user_detail) {
            return encrypt($user_detail->id, APP_KEY);
        } else {
            return false;
        }
    }
}
