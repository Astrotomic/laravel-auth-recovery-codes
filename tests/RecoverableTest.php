<?php

namespace Astrotomic\AuthRecoveryCodes\Tests;

use Astrotomic\AuthRecoveryCodes\AuthRecoveryCodesServiceProvider;
use Astrotomic\AuthRecoveryCodes\RecoveryCodes;
use Astrotomic\AuthRecoveryCodes\Tests\Models\User;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

final class RecoverableTest extends TestCase
{
    /** @test */
    public function it_hashes_recovery_codes(): void
    {
        $user = new User();
        $user->setRecoveryCodes(User::generateRecoveryCodes());

        foreach($user->getRecoveryCodes() as $hash) {
            static::assertSame('2y', Hash::info($hash)['algo']);
            static::assertSame('bcrypt', Hash::info($hash)['algoName']);
        }
    }

    /** @test */
    public function it_does_not_rehash_recovery_codes(): void
    {
        $user = new User();
        $user->setRecoveryCodes(User::generateRecoveryCodes());
        $hashedCodes = $user->getRecoveryCodes();
        $user->setRecoveryCodes($user->getRecoveryCodes());

        foreach($hashedCodes as $hashedCode) {
            static::assertTrue(in_array($hashedCode, $user->getRecoveryCodes()));
        }
    }

    /** @test */
    public function it_can_verify_recovery_code(): void
    {
        $codes = User::generateRecoveryCodes();

        $user = new User();
        $user->setRecoveryCodes($codes);

        foreach($codes as $code) {
            static::assertTrue($user->isValidRecoveryCode($code));
        }

        static::assertFalse($user->isValidRecoveryCode(Str::random()));
    }
}
