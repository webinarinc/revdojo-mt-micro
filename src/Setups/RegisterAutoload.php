<?php

namespace Revdojo\MT\Setups;

use Illuminate\Support\Facades\File;

class RegisterAutoload 
{
    public function execute($autoloadData)
    {
        $composerJsonPath = base_path('composer.json');
        $composerJson = json_decode(File::get($composerJsonPath), true);
        $composerJson = $this->registerPsr4Autoload($composerJson, $autoloadData);
      
        File::put($composerJsonPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return 'Successfully registered all services to composer.json. Please run composer dumpautoload';
    }

    protected function registerPsr4Autoload($composerJson, $autoloadData)
    {
        foreach ($autoloadData as $namespace => $path) {
            $composerJson['autoload']['psr-4'][$namespace] = $path;
        }

        return $composerJson;
    }
}
