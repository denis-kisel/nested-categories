
<?php

class LeafTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        \DenisKisel\NestedCategory\TestCategoryModel::reinstall();
        \DenisKisel\NestedCategory\TestLeafModel::reinstall();

        $this->seedCategories();
        $this->seedLeafs();
        \DenisKisel\NestedCategory\TestCategoryModel::rebuild();
    }

    public function test_get_leafs()
    {
        $this->assertEquals(12, \DenisKisel\NestedCategory\TestCategoryModel::find(1)->leafs(\DenisKisel\NestedCategory\TestLeafModel::class)->count());
        $this->assertEquals(6, \DenisKisel\NestedCategory\TestCategoryModel::find(3)->leafs(\DenisKisel\NestedCategory\TestLeafModel::class)->count());
        $this->assertEquals(3, \DenisKisel\NestedCategory\TestCategoryModel::find(4)->leafs(\DenisKisel\NestedCategory\TestLeafModel::class)->count());
        $this->assertEquals(3, \DenisKisel\NestedCategory\TestCategoryModel::find(5)->leafs(\DenisKisel\NestedCategory\TestLeafModel::class)->count());
    }

    protected function seedCategories()
    {
        $inserts = [
            //id, parent_id, name
            [1,      null,   'P1'],
            [3,      1,      'C1_p1'],
            [4,      1,      'C2_p1'],
            [5,      3,      'C3_c1_p1'],
        ];

        foreach ($inserts as [0 => $id, 1 => $parentId, 2 => $name]) {
            \DenisKisel\NestedCategory\TestCategoryModel::insert([
                'id' => $id,
                'parent_id' => $parentId,
                'name' => $name
            ]);
        }
    }

    protected function seedLeafs()
    {
        foreach (\DenisKisel\NestedCategory\TestCategoryModel::all() as $category) {
            \DenisKisel\NestedCategory\TestLeafModel::insert([
                ['test_category_id' => $category->id],
                ['test_category_id' => $category->id],
                ['test_category_id' => $category->id],
            ]);
        }
    }
}
