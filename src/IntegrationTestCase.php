<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation;

use PhpArsenal\SymfonyMongoTestingFoundation\Exception\TestEnvironmentRequiredException;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\DatabaseTrait;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Client;

abstract class IntegrationTestCase extends FunctionalTestCase
{
    use DatabaseTrait;
    use FixturesTrait;

    /** @var Client */
    protected $client;

    /** @return Client|object */
    public function getClient(): Client
    {
        if (!$this->client) {
            $this->client = $this->getService('test.client');
        }

        return $this->client;
    }

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        $this->dbOnSetUp();
    }

    public function tearDown(): void
    {
        $this->dbOnTearDown();
    }

    /**
     * @throws TestEnvironmentRequiredException
     */
    protected function mustBeTestEnvironment(): void
    {
        if ('test' !== $this->getContainer()->getParameter('kernel.environment')) {
            throw new TestEnvironmentRequiredException();
        }
    }
}
