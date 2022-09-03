<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_getting_users_list(): void
    {

        $response = $this->getJson('api/users');

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            //dd($json);
            $json->has('users')->etc();
            $json->has('users.0', function (AssertableJson $json) {
                $json
                    ->whereType('id', 'integer')
                    ->whereType('balance', 'double')
                    ->whereType('currency', 'string')
                    ->whereType('email', 'string')
                    ->whereType('created_at', 'string')
                    ->whereType('uid', 'string')
                    ->whereType('transactions', 'array')
                ;
            });
        });
    }

}
