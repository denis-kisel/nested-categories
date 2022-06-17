<?php

use \DenisKisel\NestedCategory\TestCategoryModelWithAutoRebuild;

class AutoRebuildTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        \DenisKisel\NestedCategory\TestCategoryModel::reinstall();
        \DenisKisel\NestedCategory\TestCategoryModel::where('id', '>', 0)->update(['path' => null]);
    }

    public function test_auto_rebuild()
    {
        (new TestCategoryModelWithAutoRebuild(['id' => 1, 'name' => 'P1']))->save();
        $this->assertEquals([], json_decode(TestCategoryModelWithAutoRebuild::find(1)->path));
        (new TestCategoryModelWithAutoRebuild(['id' => 2, 'name' => 'C1', 'parent_id' => 1]))->save();
        $this->assertEquals([1], json_decode(TestCategoryModelWithAutoRebuild::find(2)->path));
        (new TestCategoryModelWithAutoRebuild(['id' => 3, 'name' => 'C1', 'parent_id' => 2]))->save();
        $this->assertEquals([2, 1], json_decode(TestCategoryModelWithAutoRebuild::find(3)->path));
    }

    public function test_not_rebuild_after_batch()
    {
        TestCategoryModelWithAutoRebuild::insert([
            ['id' => 1, 'name' => 'P1', 'parent_id' => null],
            ['id' => 2, 'name' => 'C1', 'parent_id' => 1],
            ['id' => 3, 'name' => 'C1_1', 'parent_id' => 2],
        ]);

        $this->assertEquals(3, TestCategoryModelWithAutoRebuild::whereNull('path')->count());
    }
}
