<?php

/**
 * AI generated test cases
 */

declare(strict_types=1);

namespace Tests\Unit\Services;

use KVP\Beesinthetrap\Contracts\IRandomizer;
use KVP\Beesinthetrap\Services\Randomizer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Randomizer::class)]
class RandomizerTest extends TestCase
{
    private Randomizer $randomizer;

    protected function setUp(): void
    {
        $this->randomizer = new Randomizer;
    }

    /**
     * randomRangeProvider data provider
     */
    public static function randomRangeProvider(): array
    {
        return [
            'small positive range' => [1, 5],
            'large positive range' => [1, 1000],
            'negative to positive' => [-5, 5],
            'large negative range' => [-1000, -1],
            'zero to positive' => [0, 100],
            'negative to zero' => [-100, 0],
            'single value' => [42, 42],
        ];
    }

    public function test_implements_irandomizer_interface(): void
    {
        $this->assertInstanceOf(IRandomizer::class, $this->randomizer);
    }

    #[DataProvider('randomRangeProvider')]
    public function test_random_with_various_ranges(int $min, int $max): void
    {
        $result = $this->randomizer->random($min, $max);

        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual($min, $result);
        $this->assertLessThanOrEqual($max, $result);
    }

    public function test_array_rand_returns_valid_key_from_indexed_array(): void
    {
        $array = ['apple', 'banana', 'cherry', 'date'];

        $result = $this->randomizer->arrayRand($array);

        $this->assertIsInt($result);
        $this->assertArrayHasKey($result, $array);
        $this->assertGreaterThanOrEqual(0, $result);
        $this->assertLessThan(count($array), $result);
    }

    public function test_array_rand_returns_valid_key_from_associative_array(): void
    {
        $array = [
            'fruit' => 'apple',
            'vegetable' => 'carrot',
            'grain' => 'rice',
            'protein' => 'chicken',
        ];

        $result = $this->randomizer->arrayRand($array);

        $this->assertTrue(is_int($result) || is_string($result));
        $this->assertArrayHasKey($result, $array);
        $this->assertContains($result, array_keys($array));
    }
}
