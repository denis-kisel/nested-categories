<?php

namespace DenisKisel\NestedCategory\Commands;


class NestedCategoryRebuildCommand extends \Illuminate\Console\Command
{
    protected $signature = 'nested-category:rebuild {CategoryClassName}';
    protected $description = 'Nested Category Rebuild';

    public function handle()
    {
        rebuildNestedCategories($this->argument('CategoryClassName'));
        $this->info('Rebuild was saccess!');
    }
}
