<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class PreferenceTest extends TestCase
{
    /**
     * User can view preferences; Saved and Available values
     */
    public function test_user_can_view_preferences(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->getJson('api/preferences');
        $response->assertStatus(200);
    }

    /**
     * User can save preferences
     */
    public function test_user_can_save_preferences(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->putJson('api/preferences', [
            [
                "authors" => [
                        "Alexis Soloski",
                        "Andrew E. Kramer and Alan Rappeport",
                        "Andrew Sparrow",
                        "Anna Kodé",
                        "Annie Aguiar",
                        "Anson Frericks",
                        "Anton Troianovski",
                        "Catie Edmondson",
                        "Christina Goldbaum and Reham Mourshed",
                        "Claire Cain Miller",
                        "Claire Fahy",
                        "Dan Milmo",
                        "Danny Hakim and David W. Chen"
                ],
                "categories" => [
                    "news",
                    "art"
                ],
                "sources" => [
                    "the_guardian",
                    "ny_times"
                ]
            ]
        ]);
        $response->assertStatus(200);
    }
}
