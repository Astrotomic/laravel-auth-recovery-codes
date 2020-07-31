<?php

namespace Astrotomic\AuthRecoveryCodes\Tests\Models;

use Astrotomic\AuthRecoveryCodes\Recoverable;
use Illuminate\Database\Eloquent\Model;

final class User extends Model
{
    use Recoverable;
}
