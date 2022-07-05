<?php

namespace Tests\Unit;

use Tests\TestCase;

class ExampleTest extends TestCase
{
 
    public function test_login_form()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_vista_prodotti()
    {
        $response = $this->get('/prodotti');

        $response->assertStatus(200);
    }

    public function test_vista_carrello()
    {
        $response = $this->get('/carrello');

        $response->assertStatus(200);
    }

}
