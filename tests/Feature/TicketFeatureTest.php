<?php

namespace Tests\Feature;

use Tests\TestCase;

class TicketFeatureTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }
}
