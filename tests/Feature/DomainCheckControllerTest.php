<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DomainCheckControllerTest extends TestCase
{
    private $id;

    protected function setUp(): void
    {
        parent::setUp();
        $this->id = DB::table('domains')->insertGetId(
            ['name' => 'https://yandex.ru']
        );
    }

    public function testStore()
    {
        Http::fake();
        $response = $this->post(route('domains.checks.store', $this->id), []);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
    }
}
