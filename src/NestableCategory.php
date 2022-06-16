<?php

namespace DenisKisel\NestedCategory;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @extends Model|Builder
 */
trait NestableCategory
{
    public function breadcrumbs()
    {
        //SELECT * FROM `categories` where id = 7
        //or JSON_CONTAINS((SELECT path from categories where id = 7), CAST(id as JSON))
    }

    public function asArrayTree(array $fields = ['id', 'parent_id', 'name'], \DateTimeInterface|int|null $cacheTTL = null)
    {
        $cacheKey = 'nestable_category.array_tree';
        if (!is_null($cacheTTL)) {
            $cashed = Cache::get('nestable_category.array_tree');
            if ($cashed) {
                return $cashed;
            }
            $result = categoryToArrayTree(static::class, $fields);
            Cache::put($cacheKey, $result, $cacheTTL);
            return $result;
        }

        return categoryToArrayTree(static::class, $fields);
    }

    public function asModelTree()
    {

    }

    public function leafs()
    {

    }

    public static function rebuild() :void
    {
        rebuildNestedCategories(static::class);
    }

    public function createPath()
    {

    }
}
