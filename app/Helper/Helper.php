<?php

namespace App\Helper;

use function time;

class Helper
{

    public static function generateRandomString($length = 10, $type = 0)
    {
        if ($type == 1) {
            $characters = '0123456789';
        } elseif ($type == 2) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        } elseif ($type == 3) {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        } else {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        }

        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function setStoragePath($directory, $storedItem)
    {
        if ($storedItem) {
            return '/storage/' . $storedItem->storeAs(
                    $directory,
                    time() . '.' . $storedItem->getClientOriginalExtension(),
                    'public'
                );
        }
        return null;
    }
}
