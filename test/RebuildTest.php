<?php

class RebuildTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('categories');
        Schema::enableForeignKeyConstraints();
        Artisan::call(\DenisKisel\NestedCategory\Commands\NestedCategoryInstallCommand::class);
        $this->seedCategories();
    }

    public function test_rebuild()
    {
        $this->assertEquals([5, 3, 1], findPath(7, \App\Models\Category::all()));
        $this->assertEquals([90, 100], findPath(80, \App\Models\Category::all()));

        rebuildNestedCategories(\App\Models\Category::class);
        $this->assertEquals([5, 3, 1], json_decode(\App\Models\Category::find(7)->path));
        $this->assertEquals([90, 100], json_decode(\App\Models\Category::find(80)->path));
    }

    public function test_category_to_tree_array()
    {
        $result = categoryToArrayTree(\App\Models\Category::class, ['id']);
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

        foreach ($inserts as [0 => $id, 1 => $parentId, 2 => $name]) {
            \App\Models\Category::insert([
                'id' => $id,
                'parent_id' => $parentId,
                'name' => $name
            ]);
        }
    }
}
