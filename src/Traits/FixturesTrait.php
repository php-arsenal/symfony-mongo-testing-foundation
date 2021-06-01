<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation\Traits;

use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;
use Doctrine\Common\DataFixtures\ProxyReferenceRepository;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;

trait FixturesTrait
{
    protected function dbLoadFixtures(array $fixtureClasses, bool $shouldAppend = true): ReferenceRepository
    {
        $this->mustBeTestEnvironment();

        /** @var DocumentManager $manager */
        $manager = $this->dbGetManager();

        $executor = new MongoDBExecutor($manager);
        $executor->setReferenceRepository(new ProxyReferenceRepository($manager));
        $executor->setPurger(new MongoDBPurger($manager));

        $resolvedFixtureNames = $this->dbResolveFixtureClasses($fixtureClasses);
        $executor->execute($resolvedFixtureNames, $shouldAppend);

        return $executor->getReferenceRepository();
    }

    protected function dbLoadFixture(string $fixtureClass): object
    {
        return $this->dbLoadFixtures([$fixtureClass], true)->getReference($fixtureClass);
    }

    private function dbResolveFixtureClasses(array $fixtureClasses): array
    {
        $fixtures = [];

        foreach ($fixtureClasses as $fixtureClass) {
            $fixtureClass = str_replace('\\', '/', $fixtureClass);
            $fixtureClass = str_replace('Tests', getcwd().'/tests', $fixtureClass);
            $fixtureClass = sprintf('%s.php', $fixtureClass);
            $loader = new ContainerAwareLoader($this->getContainer());

            if (is_file($fixtureClass)) {
                $fixtures = array_merge($fixtures, $loader->loadFromFile($fixtureClass));
            } else {
                echo sprintf('No fixture was found in file \'%s\'. Make sure the file exists.', $fixtureClass);
            }
        }

        return $fixtures;
    }
}
