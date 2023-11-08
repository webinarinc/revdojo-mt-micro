<?php

namespace Revdojo\MT\Setups;

use Illuminate\Support\Facades\File;

class CreateConfigFile 
{
    public function execute($data, $destination)
    {
         // Generate the PHP configuration file content
         $configContents = "<?php\n\nreturn [\n";

         foreach ($data as $key => $value) {
             $configContents .= "    '$key' => '$value',\n";
         }
 
         $configContents .= "];\n";
 
         // Path to the configuration file
         $configFilePath = $destination;
 
         // Write data to the PHP file
         File::put($configFilePath, $configContents);
    }
}
