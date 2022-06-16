<?php

namespace DenisKisel\NestedCategory\Commands;


class NestedCategoryInstallCommand extends \Illuminate\Console\Command
{
    protected $signature = 'nested-category:install {--table-name=categories}';
    protected $description = 'Nested Category Install';

    public function handle()
    {
        $migration = file_get_contents(__DIR__ . '/../../resources/create_nested_categories_table.stub');
        $migration = str_replace('{tableName}', $this->option('table-name'), $migration);
        file_put_contents(database_path('migrations/' . addDateAndFormatToMigrationName('create_nested_categories_table')), $migration);

        \Artisan::call('migrate');
        $this->info(\Artisan::output());

        $this->info('Nested Categories is Installed!');
    }
}
