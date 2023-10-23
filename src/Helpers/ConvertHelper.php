<?php

namespace Revdojo\MT\Helper;

use Illuminate\Support\Str;

class ConvertHelper
{

    /**
     * use this if array is an array object and wants to fetch the array of ids only
     */
    public static function convertArrayIds($item) 
    {
        if (is_string($item)) {
            $item = json_decode($item);
        }

        if (!$item) {
            return $item;
        }

        //check if items are objects
        if (!(is_int($item[0]) || is_string($item[0])) || is_array($item[0])) {
            return collect($item)->pluck('id')->toArray();
        }

        return $item;
    }

    /**
     * $value must be string
     * $delimiter can be customise 
     * use this if you want to convert string to sluggable 
     * ex. Sample Test 1 -> sample-test-1
     */
    public static function convertToSluggable($value, $delimiter = '-') 
    {

        if ($value == trim($value) && str_contains($value, ' ')) {
            return strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $value))))), $delimiter));
        }

        return strtolower($value);
    }

      /**
     * $value must be string
     * ex. SampleTest -> Sample Test
     */
    public static function convertToFriendlyName($value) 
    {

        $separatedString = preg_replace('/([A-Z])/', ' $1', $value);
        $separatedString = trim($separatedString);
        $finalString = preg_replace('/\s+/', ' ', $separatedString);
        
        return $finalString;
    }

    /**
     * use this if value is 'true', '1', '0', 'false'
     * string and want to convert to valid boolean
     */
    public static function convertToBoolean($value) 
    {

        if ($value === 'true' || $value === '1' || $value === true || $value === 1) {
            return true;
        }

        return false;
    }

    /**
     * substr the string to cut from specific word/character
     */
    public static function convertSubstrValue($string, $value) 
    {
        return strstr($string, $value, true);
    }

    /**
     * use this if you want to convert the string into html
     * set URL_SECURED env = true/false
     */
    public static function convertUrl($string) 
    {
        return config('system_setting.url_secured')
            ? 'https://' . $string . '/'
            : 'http://' . $string . '/';
    }


    /**
     * use this if you want to convert the config as file and return value as array
     */
    public static function convertConfigFileToArray($file) 
    {
        $pattern = '/return (\[.*\]);/s';
        preg_match($pattern, $file->getContents(), $matches);
        $jsonString = $matches[1];
        $data = str_replace(["\n", " "], '', $jsonString);

        return eval("return $data;");
    }
    
}
