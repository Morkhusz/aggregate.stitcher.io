<?php

namespace App\Providers;

use Support\Markdown;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use Spatie\BladeX\BladeX;
use Spatie\QueryString\QueryString;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /** @var \Spatie\BladeX\BladeX $bladeX */
        $bladeX = $this->app->get(BladeX::class);

        $bladeX->component([
            'components.*',
            'components.form.*',
            'components.icons.*',
        ]);

        LengthAwarePaginator::defaultView('layouts.pagination');
    }

    public function register()
    {
        $this->app->alias('bugsnag.multi', \Psr\Log\LoggerInterface::class);
        $this->app->alias('bugsnag.multi', \Psr\Log\LoggerInterface::class);

        $this->app->singleton(QueryString::class, function () {
            /** @var \Illuminate\Http\Request $request */
            $request = $this->app->get(Request::class);

            return new QueryString(urldecode($request->getRequestUri()));
        });

        $this->app->singleton(Markdown::class, function () {
            $environment = Environment::createCommonMarkEnvironment();

            $convertor = new CommonMarkConverter([], $environment);

            return new Markdown($convertor);
        });
    }
}
