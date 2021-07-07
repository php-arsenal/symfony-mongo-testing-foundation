<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation\SnapshotDriver;

use PHPUnit\Framework\Assert;
use Spatie\Snapshots\Driver;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SnapshotJsonObjectDriver implements Driver
{
    public function __construct(private array $ignoredProperties = [])
    {
    }

    public function serialize($data): string
    {
        $serializer = new Serializer([
            new DateTimeNormalizer(),
            new ObjectNormalizer(),
        ], [
            new JsonEncoder(),
        ]);

        return $serializer->serialize($data, JsonEncoder::FORMAT, [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                $className = array_reverse(explode('\\', get_class($object)))[0];
                return 'CIRCULAR_' . strtoupper($className);
            },
            AbstractNormalizer::IGNORED_ATTRIBUTES => $this->ignoredProperties,
            'json_encode_options' => JSON_PRETTY_PRINT,
        ]);
    }

    public function extension(): string
    {
        return 'json';
    }

    public function match($expected, $actual)
    {
        Assert::assertEquals($expected, $this->serialize($actual));
    }
}
