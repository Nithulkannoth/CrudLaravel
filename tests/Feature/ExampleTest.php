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
        // Step 1: Set up a product to update
        $product = Product::factory()->create([
            'name' => 'Old Product Name',
            'details' => 'old details',
        ]);

        // Step 2: Prepare new data for updating the product
        $updateData = [
            'name' => 'Updated Product Name',
            'details' => 'details updated',
        ];

        // Step 3: Send a PUT request to update the product
        $response = $this->put(route('product.update', $product->id), $updateData);

        // Step 4: Assert that the response redirects to the product index with a success message
        $response->assertRedirect(route('product.index'));
        $response->assertSessionHas('success', 'product updated successfully.');

        // Step 5: Assert that the product data was updated in the database
        $this->assertDatabaseHas('product', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'details' => 'details updated',
        ]);
    }
}