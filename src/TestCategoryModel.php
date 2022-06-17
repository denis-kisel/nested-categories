<?php

namespace DenisKisel\NestedCategory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class TestCategoryModel extends Model
{
    use NestableCategory;

    protected $table = 'test_categories';
    protected $guarded = [];


    public static function reinstall()
    {
        self::rmMigrate();
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('test_categories');
        Schema::enableForeignKeyConstraints();
        Artisan::call(\DenisKisel\NestedCategory\Commands\NestedCategoryInstallCommand::class, [
            '--table-name' => 'test_categories'
        ]);
        self::rmMigrate();
    }

    public static function rmMigrate()
    {
        File::delete(File::glob(database_path('migrations/*create_nested_categories_table*')));
        \Illuminate\Support\Facades\DB::table('migrations')->where('migration', 'like', '%create_nested_categories_table')->delete();
    }

    public static function seed($data)
    {
        foreach ($data as [0 => $id, 1 => $parentId, 2 => $name]) {
            \DenisKisel\NestedCategory\TestCategoryModel::insert([
                'id' => $id,
                'parent_id' => $parentId,
                'name' => $name
            ]);
        }
    }
}
