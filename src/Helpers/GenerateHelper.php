<?php

namespace Revdojo\MT\Helper;

use Illuminate\Support\Str;

class GenerateHelper
{

   /**
    * Generate system id manually
    */
    public static function generateSystemId($prefix) 
    {
       return $prefix .'_'.Str::random(10);
    }
}

