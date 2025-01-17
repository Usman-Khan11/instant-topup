<?php

use App\Models\GeneralSetting;

function general()
{
    return GeneralSetting::first();
}

function menuActive($routeName, $type = null)
{
    if ($type == 3) {
        $class = 'side-menu-open';
    } elseif ($type == 2) {
        $class = 'active open';
    } else {
        $class = 'active';
    }
    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) {
                return $class;
            }
        }
    } elseif (request()->routeIs($routeName)) {
        return $class;
    }
}
