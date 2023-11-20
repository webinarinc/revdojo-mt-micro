<?php

namespace Revdojo\MT\Helpers;

use Illuminate\Support\Str;
use Revdojo\MT\Models\Service;
class GenerateHelper
{

   /**
    * Generate system id manually
    */
    public static function generateSystemId($prefix, $model = null) 
    {
      if ($model) {
         $prefix = basename(str_replace('\\', '/', $model));
      }
      
      return strtolower($prefix .'_'.Str::random(10));
    }

    /**
    * Generate Local Port
    */
    public static function generateLocalPort($portName) 
    {
      $port = (int)(Service::max($portName));
      return ++$port;
    }
}

