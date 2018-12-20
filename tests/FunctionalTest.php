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

    public function testWorkflow()
    {
        $this->client->request('POST', '/api/v1/verifications', ['phoneNumber' => '699010203']);

        $verificationId = json_decode($this->client->getResponse()->getContent(), true)['id'];
        $verificationCode = $this->client->getResponse()->headers->get('x-verificationcode');
        var_dump($verificationId, $verificationCode);exit;
    }
}