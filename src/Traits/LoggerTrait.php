<?php

namespace Comsave\SymfonyTestingBase\Traits;

use Psr\Log\LoggerInterface;

trait LoggerTrait
{
    /** @var LoggerInterface */
    private $logger;

    /** @required */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function debug(string $value)
    {
        $this->logger->debug($value);
    }

    public function info(string $value)
    {
        $this->logger->info($value);
    }

    public function critical(string $value)
    {
        $this->logger->critical($value);
    }
}
