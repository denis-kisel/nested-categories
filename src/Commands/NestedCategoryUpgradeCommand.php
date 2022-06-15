<?php

namespace DenisKisel\NestedCategory\Commands;


use Illuminate\Database\Console\Migrations\MigrateCommand;

class NestedCategoryUpgradeCommand extends \Illuminate\Console\Command
{
    protected $signature = 'nested-category:upgrade {--table-name=categories}';
    protected $description = 'Nested Category Upgrade';

    public function handle()
    {
        $migration = file_get_contents(__DIR__ . '/../../resources/upgrade_to_nested_categories_table.stub');
        $migration = str_replace('{tableName}', $this->option('table-name'), $migration);
        file_put_contents(database_path('migrations/' . addDateAndFormatToMigrationName('upgrade_to_nested_categories_table')), $migration);
        \Artisan::call(MigrateCommand::class);
        \Artisan::call(NestedCategoryRebuildCommand::class);
        $this->info('Nested Categories is Upgraded!');
        return 0;
    }
}
