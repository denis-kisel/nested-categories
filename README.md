# nested-categories

### Support only for
`mysql5.7.22+`
`laravel`
`php8.0+`

### Install package

Install via composer
```
composer require denis-kisel/nested-categories
```



### Install or upgrade Category table

If you have not yet category table, install it:
```
# Created table 'categories'
php artisan nested-category:install 

#Or specify table name
php artisan nested-category:install --table-name=categories
```


If you have category table already, just upgrade it:  
**Suppose what your table has fields: `id`, `parent_id`**
```
#Specify model to upgrade
php artisan nested-category:upgrade App\\Models\\Category
```


### <a id="configure"></a>Configure
Add trait `NestableCategory` to category model
```
use DenisKisel\NestedCategory\NestableCategory;

class Category extends Model
{
    use NestableCategory;
    ....
}
```

Add `path` to fillable or set guarded
```
class Category extends Model
{
    ....
    protected $fillable = ['path'];
    ....
}

#OR
class Category extends Model
{
    ....
    protected $guarded = [];
    ....
}
```

**[Optional]**  
Add trait `AutoRebuildNested` to category model for auto rebuild category structure after events: `created`, `updated`, `deleted`. 
BUT ITS DONT AUTO REBUILD AFTER BATCH OPERATION(see [Rebuild Structure](#rebuild-structure)). 

> Background use one query for rebuild all table

```
use DenisKisel\NestedCategory\NestableCategory;
use DenisKisel\NestedCategory\AutoRebuildNested;

class Category extends Model
{
    use NestableCategory, AutoRebuildNested;
    ....
}

#The same
$category->save();
$category->rebuild();

$category->update();
$category->rebuild();

$category->delete();
$category->rebuild();
```

## Usage
### Tree As Array
| id  | parent_id | name   | order |
|-----|-----------|--------|-------|
| 1   | NULL      | Parent | 0     |
| 2   | 1         | Child1 | 0     |
| 3   | 1         | Child2 | 1     |
```php
$result = Category::asArrayTree();
dump($result)
//Output:
[
    'id' => 1,
    'parent_id' => NULL,
    'name' => 'Parent',
    'children' => [
        [
            'id' => 2,
            'parent_id' => 1,
            'name' => 'Child1',
            'children' => []
        ],
        [
            'id' => 3,
            'parent_id' => 1,
            'name' => 'Child2',
            'children' => []
        ]
    ]      
]

# Specify needed fields
$result = Category::asArrayTree(fields: ['name', 'order']);
dump($result)
//Output:
[
    'name' => 'Parent',
    'order' => 0,
    'children' => [
        [
            'name' => 'Child1',
            'order' => 0,
            'children' => []
        ],
        [
            'name' => 'Child2',
            'order' => 1,
            'children' => []
        ]
    ]      
]

# Specify cache time(minutes or DateTimeInterface)
$result = Category::asArrayTree(cacheTTL: 10);
//Cached data

$result = Category::asArrayTree(cacheTTL: 10);
//Data from previous cache

$result = Category::asArrayTree();
//Data without cache


# Get array of objects
$result = Category::asArrayTree(associative: false);
dump($result)
//Output:
[
    {
        name: 'Parent',
        order: 0,
        children: [{...}]
    },
....
]
```


### Breadcrumbs
Backend use one sql query for N nested categories
```php
$category = Category::find(2)
dump($category->breadcrumbs());

//Output
Collection {
    array:2 [
        Category {id: 1, ...},
        Category {id: 2, ...},
    ]   
}
```


### Leafs(Nested Products, Posts, Podcasts, etc..)
Input tables: `categories`(id, parent_id, name), `products`(id, category_id, name).  
Backend use one sql query for nested leafs
```
ParentCategory(id: 1)
│   Product_1
│   Product_2    
│
└───ChildCategory_1(id: 2)
│   │   Product_3
│   │   Product_4
│   │
│   └───ChildCategory_1_1(id: 3)
│       │   Product_5
│       │   Product_6
│   
└───ChildCategory_2(id: 4)
    │   Product_7
    │   Product_8
```

```
#GetAllProducts
$products = Category::find(1)->leafs(App\Models\Product::class)->get();
dump($products->count());
//Output: 8
```

```
#In Models\Category
....
public nestedProducts() :Builder
{
    return $this->leafs(Product::class)
}

public nestedPosts() :Builder
{
    return $this->leafs(Post::class)
}
....

#Client Code
$products = Category::find(1)->nestedProducts()->where('name', 'like', '%some%')->get();
$products = Category::first()->nestedPosts()->count();
```

### <a id="rebuild-structure"></a>Rebuild Structure
After BATCH CRUD operations for rebuild categories structure, need to use `rebuild` method.
Or you can use trait `AutoRebuildNested` after single operation(see more in [Configure](#configure))
> Background use one query for rebuild all table
```php
# Inserts
Category::insert([
    ['id' => 1, 'parent_id' => null],
    ['id' => 2, 'parent_id' => 1],
    ['id' => 3, 'parent_id' => 2],
    .....
])

Category::rebuild();

# Delete
Category::where('is_active', false)->delete();
Category::rebuild();
```

### Additional Commands
```
# Rebuild specify category
php artisan nested-category:rebuild App\\Models\\Category
```


### Testing
```
cd vendor/denis-kisel/nested-categories
vendor/bin/phpunit test
```
