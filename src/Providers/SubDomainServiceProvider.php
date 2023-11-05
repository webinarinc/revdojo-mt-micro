<?php

namespace Revdojo\MT\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
class SubDomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
       
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $url = request()->root();
        $parsedUrl = parse_url($url);


        if (isset($parsedUrl['host'])) {
            preg_match('/^(.*?)\./', $parsedUrl['host'], $matches);
            $subdomain = empty($matches) ? 'base_service' : 'tenant'.$matches[1];
        }

        Config::set('app.tenant', $subdomain);
    }
}
