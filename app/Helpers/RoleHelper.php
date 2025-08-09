<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class RoleHelper
{
    public static function admin()
    {
        /**
         * @disregard P1009 Undefined type
         */
        return Auth::user()->hasRole('admin');
    }

    public static function manager()
    {
        /**
         * @disregard P1009 Undefined type
         */
        return Auth::user()->hasRole('manager');
    }

    public static function support()
    {
        /**
         * @disregard P1009 Undefined type
         */
        return Auth::user()->hasRole('support');
    }

    public static function seller()
    {
        /**
         * @disregard P1009 Undefined type
         */
        return Auth::user()->hasRole('seller');
    }

    public static function dashboard()
    {
        /**
         * @disregard P1009 Undefined type
         */
        return
            Auth::user()->hasRole('admin')
            || Auth::user()->hasRole('manager')
            || Auth::user()->hasRole('support')
            || Auth::user()->hasRole('seller');
    }
}