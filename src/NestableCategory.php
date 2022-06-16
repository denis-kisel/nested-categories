<?php

namespace DenisKisel\NestedCategory;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @extends Model|Builder
 */
trait NestableCategory
{
    public function breadcrumbs() :Collection
    {
        $output = collect([$this]);
        $path = json_decode($this->path);

        $output = $output->merge(static::whereIn('id', $path)->get()
            ->sortBy(function ($item, $key) use ($path) {
            return array_search($item->id, $path);
        }));

        return $output->reverse();
    }

    public function asArrayTree(array $fields = ['id', 'parent_id', 'name'], \DateTimeInterface|int|null $cacheTTL = null, $associative = true)
    {
        $cacheKey = 'nestable_category.array_tree';
        if (!is_null($cacheTTL)) {
            $cashed = Cache::get('nestable_category.array_tree');
            if ($cashed) {
                return $cashed;
            }
            $result = categoryToArrayTree(static::class, $fields, $associative);
            Cache::put($cacheKey, $result, $cacheTTL);
            return $result;
        }

        return categoryToArrayTree(static::class, $fields);
    }

    public function leafs(string $leafClassName, $foreign = null)
    {
        $foreign ??= Str::singular($this->getTable());
        $categoryIds = static::whereJsonContains('path', $this->id);
        return $leafClassName::whereIn($foreign, $categoryIds);
    }

    public static function rebuild() :void
    {
        rebuildNestedCategories(static::class);
    }

    public function createPath()
    {
        //TODO for fast add category
    }
}
