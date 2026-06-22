<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * Test login view works.
     */
    public function test_login_view_renders(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * Test register view works.
     */
    public function test_register_view_renders(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }
}
