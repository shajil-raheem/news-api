<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class NewsTest extends TestCase
{
    /**
     * get news with no filter applied
     */
    function test_news_api_no_filters() {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->getJson('api/news');
        $response->assertStatus(200);
    }

    /**
     * get news with full filters applied
     */
    function test_news_api_full_filters() {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->getJson('api/news', [
            'dateFrom' => '2025-03-08',
            'dateTo' => '2025-03-11',
            'category' => 'art',
            'keyword' => 'bridge',
            'offset' => '20',
            'limit' => '20',
        ]);
        $response->assertStatus(200);
    }

    /**
     * get news with full filters applied
     */
    function test_news_api_invalid_filters() {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->getJson('api/news?' . http_build_query([
            'dateFrom' => 'wrong date string',
            'dateTo' => '2025-03-11',
            'category' => 'art',
            'keyword' => 'bridge',
            'offset' => 'Non Numeric',
            'limit' => '20',
        ]));
        $response->assertStatus(422);
    }
}
