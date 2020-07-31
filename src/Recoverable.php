<?php

namespace Astrotomic\AuthRecoveryCodes;

use Astrotomic\AuthRecoveryCodes\Exceptions\InvalidRecoveryCodeException;
use Astrotomic\AuthRecoveryCodes\Exceptions\MissingRecoveryCodesException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

/** @mixin Model */
trait Recoverable
{
    public static function generateRecoveryCodes(): array
    {
        return app(RecoveryCodes::class)->toArray();
    }

    public function isValidRecoveryCode(string $input): bool
    {
        foreach ($this->getRecoveryCodes() as $code) {
            if (Hash::check($input, $code)) {
                return true;
            }
        }

        return false;
    }

    public function useRecoveryCode(string $input): self
    {
        $codes = $this->getRecoveryCodes();

        throw_if(empty($codes), MissingRecoveryCodesException::make($this));

        $hash = Arr::first(
            $codes,
            fn (string $code): bool => Hash::check($input, $code)
        );

        throw_if(empty($hash), InvalidRecoveryCodeException::make($this, $input));

        Arr::forget($codes, array_search($hash, $codes));

        return $this->setRecoveryCodes($codes);
    }

    public function getRecoveryCodes(): array
    {
        return $this->getAttribute($this->getRecoveryCodesName()) ?? [];
    }

    public function setRecoveryCodes(?array $codes = null): self
    {
        return $this->setAttribute(
            $this->getRecoveryCodesName(),
            $this->hashRecoveryCodes($codes)
        );
    }

    protected function hashRecoveryCodes(?array $codes = null): ?array
    {
        if ($codes === null) {
            return null;
        }

        return array_map(
            fn (string $code): string => Hash::info($code)['algo'] === null ? Hash::make($code) : $code,
            $codes
        );
    }

    public function getRecoveryCodesName(): string
    {
        return $this->recoveryCodesName ?? 'recovery_codes';
    }

    public function getQualifiedRecoveryCodesName(): string
    {
        return $this->qualifyColumn($this->getRecoveryCodesName());
    }
}
