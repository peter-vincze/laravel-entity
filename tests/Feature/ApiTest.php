<?php

namespace Tests\Feature;

use App\Models\Entity;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Api list Entities test example.
     *
     * @return void
     */
    public function testListTest()
    {
        $this->seed();
        $response = $this->getJson('/api/entity?page=2&first=15&orderBy[column]=salary&orderBy[order]=DESC');
        $response->assertJsonStructure([
            'data' => [ '*' =>
                [
                    'salary',
                    'name',
                    'email',
                    'docker',
                    'agile',
                    'start',
                    'senior',
                    'fullstack',
                    'description'
                ]
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links' => [ '*' =>
                    [
                        'url',
                        'label',
                        'active'
                    ]
                ],
                'path',
                'per_page',
                'to',
                'total'
            ],
        ]);
        $content = json_decode($response->getContent(), true);
        $lastSalary = 1000000;
        foreach($content['data'] as $key => $value) {
            $this->assertGreaterThanOrEqual( $value['salary'], $lastSalary);
            $lastSalary = $value['salary'];
        }

        $response->assertJsonPath('meta.current_page',2);
    }

    public function testCreateUpdateDeleteTest()
    {
        $this->seed();
        $data = [
            [
                'salary' => "1",
                'name' => '',
                'email' => '',
                'docker' => '',
                'agile' => '',
                'start' => '',
            ],
            [
                'salary' => 800000,
                'name' => 'Vincze Péter',
                'email' => 'vinczepetertamas@gmail.com',
                'docker' => '1',
                'agile' => '1',
                'start' => '2021-03-15',
            ],
        ];
        $response = $this->postJson('/api/entity', $data[0]);
        $content = json_decode($response->getContent(), true);

        $this->AssertCount(6,$content['errors']);

        $response = $this->postJson('/api/entity', $data[1]);

        $me = Entity::where('email', 'vinczepetertamas@gmail.com')->first();

        $this->assertEquals("2021-03-15", $me->start);

        $response = $this->postJson('/api/entity', $data[1]);

        $response->assertJsonStructure(['errors']);

        $data = [
            'salary' => 900000,
            'name' => 'Vincze Péter',
            'email' => 'vinczepetertamas@gmail.com',
            'docker' => 1,
            'agile' => 1,
            'start' => '2021-06-15',
        ];

        $id = $me->id;

        $response = $this->putJson("/api/entity/{$id}", $data);

        $me = Entity::where('email', 'vinczepetertamas@gmail.com')->first();

        $this->assertEquals('2021-06-15', $me->start);
        $response = $this->deleteJson("/api/entity/{$id}", $data);

        $countIfProblematicBugGoingToProduction = Entity::where('email', 'vinczepetertamas@gmail.com')->count();

        $this->assertEquals(0, $countIfProblematicBugGoingToProduction);
    }

}
