<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test the update function of ProductController.
     */
    public function test_product_update(): void
    {
        $product = Product::factory()->create([
            'name' => 'Old Product Name',
            'details' => 'old details',
        ]);

        $updateData = [
            'name' => 'Updated Product Name',
            'details' => 'details updated',
        ];

        $response = $this->put(route('product.update', $product->id), $updateData);

        $response->assertRedirect(route('product.index'));
        $response->assertSessionHas('success', 'product updated successfully.');

        $this->assertDatabaseHas('product', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'details' => 'details updated',
        ]);
    }
}