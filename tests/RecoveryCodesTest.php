<?php

namespace Astrotomic\AuthRecoveryCodes\Tests;

use Astrotomic\AuthRecoveryCodes\AuthRecoveryCodesServiceProvider;
use Astrotomic\AuthRecoveryCodes\RecoveryCodes;
use Illuminate\Config\Repository;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

final class RecoveryCodesTest extends TestCase
{
    /** @test */
    public function it_generates_codes(): void
    {
        $codes = $this->recoveryCodes()->toArray();

        static::assertRecoveryCodes(8, '%10-%10', $codes);
    }

    /** @test */
    public function it_can_adjust_code_count(): void
    {
        app('config')->set('recoverycodes.count', 6);
        $codes = $this->recoveryCodes()->toArray();

        static::assertRecoveryCodes(6, '%10-%10', $codes);
    }

    /** @test */
    public function it_can_adjust_block_count(): void
    {
        app('config')->set('recoverycodes.blocks', 3);
        $codes = $this->recoveryCodes()->toArray();

        static::assertRecoveryCodes(8, '%10-%10-%10', $codes);
    }

    /** @test */
    public function it_can_adjust_char_count(): void
    {
        app('config')->set('recoverycodes.chars', 5);
        $codes = $this->recoveryCodes()->toArray();

        static::assertRecoveryCodes(8, '%5-%5', $codes);
    }

    /** @test */
    public function it_can_adjust_separator(): void
    {
        app('config')->set('recoverycodes.separator', ':');
        $codes = $this->recoveryCodes()->toArray();

        static::assertRecoveryCodes(8, '%10:%10', $codes);
    }

    /** @test */
    public function it_can_use_only_numeric(): void
    {
        app('config')->set('recoverycodes.numeric', true);
        $codes = $this->recoveryCodes()->toArray();

        static::assertRecoveryCodes(8, '#10-#10', $codes);
    }

    /** @test */
    public function it_can_use_only_lowercase(): void
    {
        app('config')->set('recoverycodes.casing', 'lower');
        $codes = $this->recoveryCodes()->toArray();

        static::assertRecoveryCodes(8, '_10-_10', $codes);
    }

    /** @test */
    public function it_can_use_only_uppercase(): void
    {
        app('config')->set('recoverycodes.casing', 'upper');
        $codes = $this->recoveryCodes()->toArray();

        static::assertRecoveryCodes(8, '^10-^10', $codes);
    }

    /** @test */
    public function it_can_generate_fully_customized_codes(): void
    {
        app('config')->set('recoverycodes.count', 6);
        app('config')->set('recoverycodes.blocks', 3);
        app('config')->set('recoverycodes.chars', 5);
        app('config')->set('recoverycodes.casing', 'lower');
        $codes = $this->recoveryCodes()->toArray();

        static::assertRecoveryCodes(6, '_5-_5-_5', $codes);
    }

    protected function recoveryCodes(): RecoveryCodes
    {
        return $this->app->make(RecoveryCodes::class);
    }

    protected static function assertRecoveryCodes(int $count, string $pattern, array $codes): void
    {
        static::assertIsArray($codes);
        static::assertCount($count, $codes);

        $schema = preg_replace_callback(
            '/(\#|\%|\_|\^)(\d+)/',
            fn(array $m): string => "{$m[1]}{{$m[2]}}",
            $pattern
        );
        $pattern = str_replace(
            ['%', '#', '_', '^'],
            ['[a-zA-Z0-9]', '[0-9]', '[a-z0-9]', '[A-Z0-9]'],
            $schema
        );

        foreach($codes as $code) {
            static::assertMatchesRegularExpression(
                '/^'.$pattern.'$/',
                $code
            );
        }
    }
}
