# Laravel Auth Recovery-Codes

[![Latest Version](http://img.shields.io/packagist/v/astrotomic/laravel-auth-recovery-codes.svg?label=Release&style=for-the-badge)](https://packagist.org/packages/astrotomic/laravel-auth-recovery-codes)
[![MIT License](https://img.shields.io/github/license/Astrotomic/laravel-auth-recovery-codes.svg?label=License&color=blue&style=for-the-badge)](https://github.com/Astrotomic/laravel-auth-recovery-codes/blob/master/LICENSE)
[![Offset Earth](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-green?style=for-the-badge)](https://plant.treeware.earth/Astrotomic/laravel-auth-recovery-codes)

[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/Astrotomic/laravel-auth-recovery-codes/run-tests?style=flat-square&logoColor=white&logo=github&label=Tests)](https://github.com/Astrotomic/laravel-auth-recovery-codes/actions?query=workflow%3Arun-tests)
[![StyleCI](https://styleci.io/repos/284008331/shield)](https://styleci.io/repos/284008331)
[![Total Downloads](https://img.shields.io/packagist/dt/astrotomic/laravel-auth-recovery-codes.svg?label=Downloads&style=flat-square)](https://packagist.org/packages/astrotomic/laravel-auth-recovery-codes)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require astrotomic/laravel-auth-recovery-codes
```

And publish the config via artisan:

```bash
php artisan vendor:publish --provider="Astrotomic\AuthRecoveryCodes\AuthRecoveryCodesServiceProvider" --tag=config
```

## Usage

### Model

You will have to add the `Recoverable` trait to your model you want to have recovery codes and should add a `json` or `array` cast to the attribute holding the recovery codes.

```php
use Illuminate\Database\Eloquent\Model;
use Astrotomic\AuthRecoveryCodes\Recoverable;

class User extends Model
{
    use Recoverable;

    protected $casts = [
        'recovery_codes' => 'array',
    ];
}
```

By default the trait uses a `recovery_codes` attribute/column - you can change this by setting `$recoveryCodesName` property:

```php
class User extends Model
{
    use Recoverable;

    protected string $recoveryCodesName = 'mfa_recovery_codes';

    protected $casts = [
        'mfa_recovery_codes' => 'array',
    ];
}
```

To set the new recovery codes to your model you should use the `setRecoveryCodes()` method because this method automatically hashes the recovery codes, if not already hashed.
This step is important for security because with this step only the user has access to the recovery codes and no one else.
The following snippet is an example of a possible controller action

-   generating the codes
-   setting and saving the codes on the user model
-   responding with the codes to the user (the one and only time anyone can get/read the plaintext recovery codes)

```php
$codes = User::generateRecoveryCodes();

$user->setRecoveryCodes($codes)->save();

return response()->json($codes);
```

If you want to use the default model attribute without the need to use `setRecoveryCodes()` method you should add your own accessor and mutator, keep in mind to call the `Recoverable::hashRecoveryCodes()` method on set and that you have to do the JSON casting by your own.

### Migration

After setting up your model you will have to add the new column to your database table, there aren't much requirements - the `json` column type would only help to prevent invalid content, but the recovery codes JSON isn't really queryable (only an array of hashes), but the column should be `nullable` if you don't setup recovery codes on user create/register.

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecoveryCodesToUsersTable extends Migration
{
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->json('recovery_codes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->dropColumn('recovery_codes');
        });
    }
}
```

### Recovery

Now that you have setup your app to generate and store recovery codes you should add the logic to recover an account.
The `Recoverable` trait comes with two methods to help you with this task.

-   `isValidRecoveryCode()` return a `bool` and tells you if any of the saved recovery codes matches the input
-   `useRecoveryCode()` removes the matching hash from the array and sets the array of remaining recovery codes

```php
use Astrotomic\AuthRecoveryCodes\Recoverable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class RecoverController
{
    public function __invoke(Request $request)
    {
        /** @var Model|Recoverable $user */
        $user = User::whereEmail($request->email)->firstOrFail();

        abort_unless(Hash::check($request->password, $user->password), Response::HTTP_NOT_FOUND);

        abort_unless($user->isValidRecoveryCode($request->recovery_code), Response::HTTP_NOT_FOUND);

        // do something to allow the user to recover the account
        // - log him in and redirect to account/security settings
        // - disable 2FA
        // - send an email with a signed link to do something

        $user->useRecoveryCode($request->recovery_code)->save();

        // you should check if user has remaining recovery codes
        // if not you should re-generate some and tell the user
        // for sure you can trigger this before all codes are used
        // or remind the user on regular login to generate new ones
        // if he's running out of remaining ones
        if(empty($user->getRecoveryCodes())) {
            $codes = User::generateRecoveryCodes();

            $user->setRecoveryCodes($codes)->save();

            return response()->json($codes);
        }
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/Astrotomic/.github/blob/master/CONTRIBUTING.md) for details. You could also be interested in [CODE OF CONDUCT](https://github.com/Astrotomic/.github/blob/master/CODE_OF_CONDUCT.md).

### Security

If you discover any security related issues, please check [SECURITY](https://github.com/Astrotomic/.github/blob/master/SECURITY.md) for steps to report it.

## Credits

-   [Tom Witkowski](https://github.com/Gummibeer)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Treeware

You're free to use this package, but if it makes it to your production environment I would highly appreciate you buying the world a tree.

It’s now common knowledge that one of the best tools to tackle the climate crisis and keep our temperatures from rising above 1.5C is to [plant trees](https://www.bbc.co.uk/news/science-environment-48870920). If you contribute to my forest you’ll be creating employment for local families and restoring wildlife habitats.

You can buy trees at [offset.earth/treeware](https://plant.treeware.earth/Astrotomic/laravel-auth-recovery-codes)

Read more about Treeware at [treeware.earth](https://treeware.earth)
