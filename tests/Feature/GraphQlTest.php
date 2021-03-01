<?php

namespace Tests\Feature;

use App\Models\Entity;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

class GraphQlTest extends TestCase
{
    use RefreshDatabase;
    use MakesGraphQLRequests;

    /**
     * GraphQl list Entities test example.
     *
     * @return void
     */
    public function testEntityList(): void
    {
        $this->seed();
        $response = $this->graphQL(/** @lang GraphQL */ '
        {entities(
orderBy: {column:SALARY,order:DESC}
first: 10
page: 10
){paginatorInfo{count,currentPage,firstItem,lastItem,lastPage,total},
  data{id,salary,name,email,docker,agile,start,senior,fullstack,description}}}');
        $response->assertJsonStructure([
                "data" => [
                    "entities" => [
                        "paginatorInfo" => [
                            "count",
                            "currentPage",
                            "firstItem",
                            "lastItem",
                            "lastPage",
                            "total"
                        ],
                        "data" => ['*' =>
                            ["id",
                            "salary",
                            "name",
                            "email",
                            "docker",
                            "agile",
                            "start",
                            "senior",
                            "fullstack",
                            "description"]
                        ]
                    ]
                ]
        ]);
    }

    /**
     * GraphQl Update test example.
     *
     * @return void
     */

    public function testEntityUpdate(): void
    {
        $this->seed();
        $response = $this->graphQL(/** @lang GraphQL */ '
            mutation {
             entityupdate(
                id: 100,
                salary: 859706,
                name: "Dr. Ivsdfsdfory Herman",
                docker: 0,
                agile: 1,
                start: "2021-03-11",
                senior: null,
                fullstack: null,
                description: null
            ){id}
            }');
        var_dump($response->getContent());
        $response->assertJsonStructure(["data" => ["entityupdate" => ["id"]]]);
    }

    /**
     * GraphQl Create test example.
     *
     * @return void
     */
    public function testEntityCreate(): void
    {
        $this->seed();
        $response = $this->graphQL(/** @lang GraphQL */ '
        mutation {
         entitycreate(
            salary: 859706,
            name: "Dr. Ivory Herman",
            email: "sdgheruasdflg@sadiuf.hu"
            docker: 0,
            agile: 1,
            start: "2021-03-11",
            senior: null,
            fullstack: null,
            description: null
        ){id}
        }');
        $response->assertJsonStructure(["data" => ["entitycreate" => ["id"]]]);
    }
}
