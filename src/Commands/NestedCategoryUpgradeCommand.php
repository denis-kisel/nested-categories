<?php

namespace DenisKisel\NestedCategory\Commands;


use App\Models\Category;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Symfony\Component\Console\Output\ConsoleOutput;

class NestedCategoryUpgradeCommand extends \Illuminate\Console\Command
{
    protected $signature = 'nested-category:upgrade {CategoryClassName}';
    protected $description = 'Nested Category Upgrade';

    public function handle()
    {
        $categoryClassName = $this->argument('CategoryClassName');
        $tableName = app($categoryClassName)->getTable();
        $migration = file_get_contents(__DIR__ . '/../../resources/upgrade_to_nested_categories_table.stub');
        $migration = str_replace('{tableName}', $tableName, $migration);
        file_put_contents(database_path('migrations/' . addDateAndFormatToMigrationName('upgrade_to_nested_categories_table')), $migration);

        \Artisan::call('migrate');
        $this->info(\Artisan::output());

        $output = \Artisan::call(NestedCategoryRebuildCommand::class, [
            'CategoryClassName' => $categoryClassName
        ]);
        $this->info(\Artisan::output());

        $this->info('Nested Categories is Upgraded!');
        return 0;
    }
}
