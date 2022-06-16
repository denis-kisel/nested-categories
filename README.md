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
