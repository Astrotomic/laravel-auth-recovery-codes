<?php

namespace Astrotomic\AuthRecoveryCodes;

use Illuminate\Support\ServiceProvider;
use PragmaRX\Recovery\Recovery;

class AuthRecoveryCodesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('recoverycodes.php'),
            ], 'config');
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'recoverycodes');

        $this->registerRecoveryCodeGenerator();
    }

    protected function registerRecoveryCodeGenerator(): void
    {
        $this->app->bind(RecoveryCodes::class, function (): RecoveryCodes {
            return (new RecoveryCodes())
                ->setCount(config('recoverycodes.count'))
                ->setBlocks(config('recoverycodes.blocks'))
                ->setChars(config('recoverycodes.chars'))
                ->setBlockSeparator(config('recoverycodes.separator'))
                ->when(config('recoverycodes.numeric'))->numeric()
                ->when(!config('recoverycodes.numeric'))->alpha()
                ->when(config('recoverycodes.casing') === 'lower')->lowercase()
                ->when(config('recoverycodes.casing') === 'upper')->uppercase()
                ->when(config('recoverycodes.casing') === 'mixed')->mixedcase();
        });
        $this->app->alias(RecoveryCodes::class, Recovery::class);
    }
}
