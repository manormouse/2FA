<?php declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\VerificationCode;
use App\Domain\VerificationCodeGenerator;

class HexadecimalVerificationCodeGenerator implements VerificationCodeGenerator
{
    public function generate(): VerificationCode
    {
        return new VerificationCode('AAA' . rand(100, 999));
    }
}
