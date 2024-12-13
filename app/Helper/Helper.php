<?php

namespace App\Helper;

use function time;

class Helper
{
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
