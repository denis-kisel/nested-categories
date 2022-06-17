<?php

class CategoryTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        \DenisKisel\NestedCategory\TestCategoryModel::reinstall();
        $this->seedCategories();
    }

    public function test_rebuild()
    {
        $this->assertEquals([5, 3, 1], findPath(7, \DenisKisel\NestedCategory\TestCategoryModel::all()));
        $this->assertEquals([90, 100], findPath(80, \DenisKisel\NestedCategory\TestCategoryModel::all()));

        rebuildNestedCategories(\DenisKisel\NestedCategory\TestCategoryModel::class);
        $this->assertEquals([5, 3, 1], json_decode(\DenisKisel\NestedCategory\TestCategoryModel::find(7)->path));
        $this->assertEquals([90, 100], json_decode(\DenisKisel\NestedCategory\TestCategoryModel::find(80)->path));

        $category = new \DenisKisel\NestedCategory\TestCategoryModel(['id' => 8, 'name' => 'C1_1_1_1', 'parent_id' => 7]);
        $category->save();
        $category->rebuild();
        $this->assertEquals([7, 5, 3, 1], json_decode(\DenisKisel\NestedCategory\TestCategoryModel::find(8)->path));
    }

    public function test_breadcrumbs()
    {
        \DenisKisel\NestedCategory\TestCategoryModel::rebuild();
        $this->assertEquals([1, 3, 5, 7], \DenisKisel\NestedCategory\TestCategoryModel::find(7)->breadcrumbs()->pluck('id')->toArray());
        $this->assertEquals([100, 90, 80], \DenisKisel\NestedCategory\TestCategoryModel::find(80)->breadcrumbs()->pluck('id')->toArray());
    }

    public function test_category_to_tree_array()
    {
        $result = categoryToArrayTree(\DenisKisel\NestedCategory\TestCategoryModel::class, ['id']);
        $this->assertEquals([
            [
                'id' => 1,
                'children' => [
                    [
                        'id' => 3,
                        'children' => [
                            [
                                'id' => 5,
                                'children' => [
                                    [
                                        'id' => 7,
                                        'children' => []
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => 4,
                        'children' => [
                            [
                                'id' => 6,
                                'children' => []
                            ]
                        ]
                    ]
                ]
            ],
            [
                'id' => 2,
                'children' => []
            ],
            [
                'id' => 100,
                'children' => [
                    [
                        'id' => 90,
                        'children' => [
                            [
                                'id' => 80,
                                'children' => []
                            ]
                        ]
                    ]
                ]
            ],
        ], $result);
    }

    protected function seedCategories()
    {
        $inserts = [
            //id, parent_id, name
            [1,      null,   'P1'],
            [2,      null,   'P2'],
            [3,      1,      'C1_p1'],
            [4,      1,      'C2_p1'],
            [5,      3,      'C3_c1_p1'],
            [6,      4,      'C4_c2_p1'],
            [7,      5,      'C5_c3_c1_p1'],

            [100,    null,   'P3'],
            [90,     100,    'C6_p3'],
            [80,     90,     'C7_c6_p3'],
        ];

        \DenisKisel\NestedCategory\TestCategoryModel::seed($inserts);
    }
}
