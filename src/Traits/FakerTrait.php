<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation\Traits;

use Faker\Factory;
use Faker\Generator;

trait FakerTrait
{
    /** @var Generator */
    protected $faker;

    public function getFaker(): Generator
    {
        if (!$this->faker) {
            $this->faker = Factory::create();
        }

        return $this->faker;
    }
}
