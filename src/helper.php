<?php

function rebuildNestedCategories(string $className)
{
    /** @var \Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection $categories */
    $categories = $className::all();
    $tableName = app($className)->getTable();

    $toUpdate = [];
    if ($categories->isNotEmpty()) {
        foreach ($categories as $category) {
            $toUpdate[] = [
                'id' => $category->id,
                'path' => json_encode(findPath($category->id, $categories))
            ];
        }
    }

    (new \DenisKisel\BatchUpdate\BatchUpdate($tableName, $toUpdate))->run();
}

function findPath(int $id, \Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection $categories, array &$ouput = []) :array
{
    $parentId = $categories->where('id', $id)->first()->parent_id;
    if (!is_null($parentId)) {
        $ouput[] = $parentId;
        findPath($parentId, $categories, $ouput);
        return $ouput;
    } else {
        return $ouput;
    }
}

function categoryToArrayTree(string $categoryClassName, $fields = ['id', 'parent_id', 'name'], $associative = true, $sortBy = 'id', $sortDirect = 'asc') :array
{
    $categories = $categoryClassName::all();

    $output = ($mkTreeFn = function ($parentId = null) use ($categories, $fields, &$mkTreeFn, $associative, $sortBy, $sortDirect) {
        $output = [];
        if (is_null($parentId)) {
            $children = $categories->whereNull('parent_id');
        } else {
            $children = $categories->where('parent_id', $parentId);
        }
        /** @var \Illuminate\Support\Collection $children */
        if ($children->isNotEmpty()) {

            if ($sortDirect == 'asc') {
                $children = $children->sortBy($sortBy);
            } else {
                $children = $children->sortByDesc($sortBy);
            }

            foreach ($children as $child) {
                $node = [];
                foreach ($fields as $field) {
                    $node[$field] = $child->{$field};
                }
                $node['children'] = $mkTreeFn($child->id);
                settype($node, $associative ? 'array' : 'object');
                $output[] = $node;
            }
            return $output;
        }

        return $output;
    })();
    return $output;
}

function addDateAndFormatToMigrationName(string $name) :string
{
    $date = now()->format('Y_m_d_his');
    return "{$date}_{$name}.php";
}
