<?php

namespace DenisKisel\NestedCategory;

use Illuminate\Support\Facades\Schema;

class TestLeafModel extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'test_leafs';


    public static function reinstall()
    {
        Schema::dropIfExists('test_leafs');
        Schema::create('test_leafs', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('test_category_id');
        });
    }
}
