<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation;

use Exception;
use PhpArsenal\SymfonyMongoTestingFoundation\Exception\TestEnvironmentRequiredException;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\ContainerTrait;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\DatabaseTrait;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\HttpKernelBrowser;

abstract class IntegrationTestCase extends WebTestCase
{
    use DatabaseTrait;
    use FixturesTrait;
    use ContainerTrait;

    /** @var HttpKernelBrowser */
    protected HttpKernelBrowser $client;

    public function getClient(): HttpKernelBrowser
    {
        if (!$this->client) {
            $this->client = static::createClient();
        }

        return $this->client;
    }

    public function getService(string $className)
    {
        return $this->getContainer()->get($className);
    }

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->dbOnSetUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();

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
