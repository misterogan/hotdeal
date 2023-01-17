<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChangeLogTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAdminChangeLogProductData()
    {
        $response = $this->post('/product/log/dt');

        $response->assertStatus(200);
    }

    public function testAdminChangeLogProductView()
    {
        $response = $this->get('/admin/product/changes/');

        $response->assertStatus(200);
    }
    
}
