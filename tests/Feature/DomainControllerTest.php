<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class DomainControllerTest extends TestCase
{
    private $id;

    protected function setUp(): void
    {
        parent::setUp();
        $this->id = DB::table('domains')->insertGetId(
            ['name' => 'https://yandex.ru']
        );

        DB::table('domain_checks')->insert([
            ['domain_id' => $this->id]
        ]);
    }

    public function testIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertOk();
    }

    public function testShow()
    {
        $response = $this->get(route('domains.show', $this->id));
        $response->assertOk();
    }

    public function testStore()
    {
        $data = ['name' => 'https://testpage.com'];
        $response = $this->post(route('domains.store'), ['domain' => $data]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('domains', $data);
    }
}
