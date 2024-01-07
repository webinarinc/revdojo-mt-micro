<?php

namespace Revdojo\MT\Helpers;

use Illuminate\Support\Str;
use Revdojo\MT\Helpers\ConvertHelper;

class GenerateHelper
{

   /**
    * Generate system id manually
    */
    public static function generateSystemId($prefix, $modelClass = null) 
    {
      if ($modelClass) {
         $prefix = basename(str_replace('\\', '/', $modelClass));
      }

      $prefix = ConvertHelper::convertToSluggable($prefix, '_');
      
      return strtolower($prefix .'_'.Str::random(10));
    }
}

