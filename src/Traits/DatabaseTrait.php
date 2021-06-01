<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation\Traits;

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
        $db = $this->dbGetManager()->getClient()->selectDatabase($_ENV['MONGODB_DB']);

        foreach ($db->listCollections() as $collection) {
            $db->dropCollection($collection->getName());
        }
    }

    protected function dbFind(string $documentClass, $id): ?object
    {
        return $this->dbGetManager()->find($documentClass, $id);
    }

    protected function dbSave($model): void
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
