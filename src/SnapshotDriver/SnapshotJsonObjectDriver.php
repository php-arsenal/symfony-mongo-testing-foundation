<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation\SnapshotDriver;

use PHPUnit\Framework\Assert;
use Spatie\Snapshots\Driver;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
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

        $normalizedData = $serializer->normalize($data, 'array', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return sprintf('CIRCULAR_%s', array_reverse(explode('\\', get_class($object)))[0]);
            },
            AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
            AbstractObjectNormalizer::MAX_DEPTH_HANDLER => function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
                return sprintf('MAX_DEPTH_%s', array_reverse(explode('\\', get_class($object)))[0]);
            },
            AbstractNormalizer::IGNORED_ATTRIBUTES => $this->ignoredProperties,
        ]);

        $this->unsetIgnoredKeys($normalizedData, $this->ignoredProperties);

        return $serializer->serialize($normalizedData, JsonEncoder::FORMAT, [
            'json_encode_options' => JSON_PRETTY_PRINT
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

    private function unsetIgnoredKeys(array &$values, array $ignoredKeys): void
    {
        foreach($values as $key => $value) {
            if(is_array($value)) {
                $this->unsetIgnoredKeys($value, $ignoredKeys);
            }
            else if (in_array($key, $ignoredKeys)) {
                unset($values[$key]);
            }
        }
    }
}
