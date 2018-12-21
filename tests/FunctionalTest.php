<?php declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Client;

class FunctionalTest extends WebTestCase
{
    /** @var Client */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testVerify()
    {
        $this->client->request('POST', '/api/v1/verifications', ['phoneNumber' => '699010203']);

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function testFullWorkflow()
    {
        $this->client->request('POST', '/api/v1/verifications', ['phoneNumber' => '699010203']);

        $content          = json_decode($this->client->getResponse()->getContent(), true);
        $verificationId   = $content['id'];
        $verificationCode = $content['code'];

        $this->client->request('POST', "/api/v1/verifications/{$verificationId}", ['code' => $verificationCode]);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(json_decode($this->client->getResponse()->getContent(), true)['verified']);
    }
}