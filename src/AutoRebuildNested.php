<?php

namespace DenisKisel\NestedCategory;

trait AutoRebuildNested
{
    protected static function booted()
    {
        static::created(function ($category) {
            $category->rebuild();
        });

        static::updated(function ($category) {
            $category->rebuild();
        });
//
        static::deleted(function ($category) {
            $category->rebuild();
        });
    }
}
