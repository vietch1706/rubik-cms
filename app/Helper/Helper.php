<?php

namespace App\Helper;

use App\Models\Users\Users;
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

    public static function setStoragePath($directory, $storedItem, $customName = null)
    {
        if ($storedItem) {
            $filename = $customName ? $customName : time();
            return '/storage/' . $storedItem->storeAs(
                    $directory,
                    $filename . '.' . $storedItem->getClientOriginalExtension(),
                    'public'
                );
        }
        return null;
    }

    public static function getUsersFullName($table, $id)
    {
        return Users::join($table, 'users.id', '=', $table . '.user_id')
            ->select($table . '.id as id', 'users.first_name', 'users.last_name')
            ->where($table . '.id', $id)
            ->get(['id', 'first_name', 'last_name'])
            ->pluck('fullName', 'id')
            ->toArray();
    }
}
