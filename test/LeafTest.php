
<?php

class LeafTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('test_leafs', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('category_id');
        });

        \App\Models\Category::where('id', '>', 0)->delete();
        $this->seedCategories();
        $this->seedLeafs();
        \App\Models\Category::rebuild();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Schema::dropIfExists('test_leafs');
    }

    public function test_get_leafs()
    {
        $this->assertEquals(3, \App\Models\Category::find(1)->leafs(TestLeaf::class)->count());
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
            \App\Models\Category::insert([
                'id' => $id,
                'parent_id' => $parentId,
                'name' => $name
            ]);
        }
    }

    protected function seedLeafs()
    {
        foreach (\App\Models\Category::all() as $category) {
            DenisKisel\NestedCategory\TestLeaf::insert([
                ['category_id' => $category->id],
                ['category_id' => $category->id],
                ['category_id' => $category->id],
            ]);
        }
    }
}
