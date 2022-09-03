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

    public function test_filter_by_status_code(): void
    {
        $response = $this->get('api/users?status_code=authorized');

        $response->assertStatus(200);
        $res_array = (array)json_decode($response->content(),true);

        foreach ($res_array['users'] as $item){
            foreach ($item['transactions'] as $transaction) {
                $this->assertEquals('authorized', $transaction['status_code']);
            }
        }
    }

    public function test_that_validation_work_if_item_is_missing_from_the_data_range(){
        $arr = array(
            "date_range"=>[
                "from"=>"2021-01-01"
            ]
        );
        $response = $this->get('api/users?'.http_build_query($arr));

        $response->assertStatus(200);
        $responseItem = $response->json();
        $this->assertSame($responseItem['errors']['date_range'][0], "The date range must contain 2 items.");

    }
}
