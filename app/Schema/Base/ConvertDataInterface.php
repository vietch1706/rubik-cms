<?php

namespace App\Schema\Base;

use Illuminate\Database\Eloquent\Model;

interface ConvertDataInterface
{
  public function convertData(Model $model);
}
