<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation\Traits;

trait IdGeneratorTrait
{
    public function getDossierIdRegex(string $operatorName): string
    {
        return sprintf('/^%s-[0-9ABD-Z][0-9A-Z]{11}$/', $operatorName);
    }

    public function generateDossierId(string $operatorName): string
    {
        return $this->getFaker()->regexify($this->getDossierIdRegex($operatorName));
    }

    public function getSalesforceIdRegex(): string
    {
        return sprintf('/^[a-zA-Z0-9]{18}/');
    }

    public function generateSalesforceId(): string
    {
        return $this->getFaker()->regexify($this->getSalesforceIdRegex());
    }
}
