<?php

namespace Comsave\SymfonyTestingBase\Traits;

use Doctrine\ODM\MongoDB\DocumentManager;

trait DatabaseTrait
{
    protected function dbGetManager(): DocumentManager
    {
        return $this->getService('doctrine_mongodb.odm.document_manager');
    }

    /**
     * @throws \Exception
     */
    protected function dbOnSetUp(): void
    {
        $this->dbClear();
    }

    protected function dbOnTearDown(): void
    {
    }

    /**
     * @throws \Exception
     */
    protected function dbClear(): void
    {
        $this->mustBeTestEnvironment();
        $mongoClient = $this->dbGetManager()->getClient();
        $testDatabaseName = $this->getContainer()->getParameter('default_database');

        foreach ($mongoClient->selectDatabase($testDatabaseName)->listCollections() as $collection) {
            $mongoClient->selectDatabase($testDatabaseName)->dropCollection($collection->getName());
        }
    }

    protected function dbFind(string $documentClass, $id)
    {
        return $this->dbGetManager()->find($documentClass, $id);
    }

    protected function dbSave($model)
    {
        $this->dbGetManager()->persist($model);
        $this->dbGetManager()->flush();
    }

    protected function dbRemove($document): void
    {
        $this->dbGetManager()->remove($document);
        $this->dbGetManager()->flush();
    }
}
