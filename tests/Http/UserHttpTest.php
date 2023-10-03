<?php

namespace Tests\Http;

use Tests\TestCase;

class UserHttpTest extends TestCase
{
    /**
     * Undocumented function
     *
     * @return array
     */
    public function payloadProvider(): array
    {
        return [
            [
                [
                    'username' => null,
                    'email' => null,
                    'mobile_number' => null,
                    'password' => 'strongPassword123'
                ]
            ]
        ];
    }
    /**
     *@test
     *@dataProvider payloadProvider
     * @return void
     */
    public function store(array $payload)
    {
        $this->json(
            'POST',
            '/api/register',
            $payload
        );

        $response = $this->response->json();
        dd($response);
        $this->assertResponseStatus(200);
        $this->seeInDatabase(
            'client_profiles',
            [
                'id' => $response['data']['id'],
                'username' => $response['data']['username'],
                'mobile_number' => $response['data']['mobile_number'],
                'email' => $response['data']['email'],
            ]
        );
    }
}
