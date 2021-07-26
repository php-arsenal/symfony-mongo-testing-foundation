<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation\SnapshotDriver;

use PHPUnit\Framework\Assert;
use Spatie\Snapshots\Driver;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
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
                return sprintf('CIRCULAR_%s', $this->getObjectClassName($object));
            },
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
            AbstractObjectNormalizer::MAX_DEPTH_HANDLER => function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
                return sprintf('MAX_DEPTH_%s', $this->getObjectClassName($innerObject));
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

    private function getObjectClassName($object): string
    {
        return array_reverse(explode('\\', get_class($object)))[0];
    }
}
