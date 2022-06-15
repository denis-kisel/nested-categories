<?php

namespace DenisKisel\NestedCategory;


use DenisKisel\NestedCategory\Commands\NestedCategoryInstallCommand;
use DenisKisel\NestedCategory\Commands\NestedCategoryRebuildCommand;
use DenisKisel\NestedCategory\Commands\NestedCategoryUpgradeCommand;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                NestedCategoryInstallCommand::class,
                NestedCategoryUpgradeCommand::class,
                NestedCategoryRebuildCommand::class,
            ]);
        }
    }
}
