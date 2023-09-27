<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation\SnapshotDriver;

use App\General\Doctrine\AbstractDocument;
use PHPUnit\Framework\Assert;
use ProxyManager\Configuration;
use ProxyManager\Inflector\ClassNameInflector;
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
    private static ?ClassNameInflector $classNameInflector = null;

    public function __construct(
        private array $ignoredProperties = [],
        private array $redactedProperties = []
    )
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
                return sprintf('CIRCULAR_%s', array_reverse(explode('\\', self::resolveFromObject($object)))[0]);
            },
            AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
            AbstractObjectNormalizer::MAX_DEPTH_HANDLER => function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
                return sprintf('MAX_DEPTH_%s', array_reverse(explode('\\', self::resolveFromObject($innerObject)))[0]);
            },
            AbstractNormalizer::IGNORED_ATTRIBUTES => $this->ignoredProperties,
        ]);

        $this->ignoreKeys($normalizedData, $this->ignoredProperties);
        $this->redactKeys($normalizedData, $this->redactedProperties);

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

    private function ignoreKeys(array &$values, array $ignoredKeys): void
    {
        foreach($values as $key => $value) {
            if (in_array($key, $ignoredKeys)) {
                unset($values[$key]);
            }
            else if(is_array($value)) {
                $this->ignoreKeys($value, $ignoredKeys);
                $values[$key] = $value;
            }
        }
    }

    private function redactKeys(array &$values, array $redactedKeys): void
    {
        foreach($values as $key => $value) {
            if (in_array($key, $redactedKeys) && ($value !== null || (is_array($value) && !empty($value)))) {
                $values[$key] = 'REDACTED';
            }
            else if(is_array($value)) {
                $this->redactKeys($value, $redactedKeys);
                $values[$key] = $value;
            }
        }
    }

    private static function resolve(string $proxyObjectClass): string
    {
        return self::getClassNameInflector()->getUserClassName($proxyObjectClass);
    }

    private static function resolveFromObject(object $object): string
    {
        return self::resolve($object::class);
    }

    private static function getClassNameInflector(): ClassNameInflector
    {
        if (!self::$classNameInflector) {
            self::$classNameInflector = (new Configuration())->getClassNameInflector();
        }

        return self::$classNameInflector;
    }
}
