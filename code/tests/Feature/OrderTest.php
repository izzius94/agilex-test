<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_cannot_place_an_order_if_not_logged(): void
    {
        $response = $this->postJson('/orders');

        $response->assertStatus(401);
    }
}
