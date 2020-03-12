<?php

namespace Comsave\SymfonyTestingBase\Traits;

use LogicItLab\Salesforce\MapperBundle\Mapper;
use Symfony\Component\HttpFoundation\Response;

trait SalesforceTrait
{
    /** @var string */
    protected $salesforceApiPath = '/salesforce/sync';
    /** @var array */
    protected $sfObjectsToRemove = [];

    protected function sfGetManager(): Mapper
    {
        return $this->getService(Mapper::class);
    }

    protected function sfOnSetUp(): void
    {
    }

    protected function sfOnTearDown(): void
    {
        foreach ($this->sfObjectsToRemove as $object) {
            $this->sfRemove($object);
        }
    }

    protected function sfFind($model, $id)
    {
        $this->sfGetManager()->getUnitOfWork()->clear();

        return $this->sfGetManager()->find($model, $id);
    }

    protected function sfFindBy($model, $arguments)
    {
        $this->sfGetManager()->getUnitOfWork()->clear();

        return $this->sfGetManager()->findBy($model, ...$arguments);
    }

    protected function sfSave($model): void
    {
        $this->sfGetManager()->save($model);
    }

    protected function sfRemove($model): void
    {
        if (!is_array($model)) {
            $model = [$model];
        }

        $this->sfGetManager()->delete($model);
    }

    protected function sfAddObjectToRemove($model)
    {
        $this->sfObjectsToRemove[] = $model;
    }

    protected function sendSalesforceOutboundMessage(string $messagePath): Response
    {
        $message = file_get_contents(sprintf('%s/../SalesforceMessages/%s.xml', dirname(__FILE__), $messagePath));

        $this->getClient()->request('POST', $this->salesforceApiPath, [], [], [
            'Content-Type' => 'text/xml; charset=utf-8',
        ], $message);

        return $this->getClient()->getResponse();
    }
}
