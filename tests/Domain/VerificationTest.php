<?php declare(strict_types=1);

namespace App\Test\Domain;

use App\Domain\PhoneNumber;
use App\Domain\Verification;
use App\Domain\VerificationCode;
use App\Domain\VerificationCodeGenerator;
use App\Domain\VerificationId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class VerificationTest extends TestCase
{
    /** @var VerificationCodeGenerator|MockObject */
    private $verificationCodeGenerator;

    protected function setUp()
    {
        $this->verificationCodeGenerator = $this->getMockBuilder(VerificationCodeGenerator::class)->getMock();
    }

    private function forceVerificationCodeGeneratorCodeReturned($code)
    {
        $this->verificationCodeGenerator
            ->method('generate')
            ->willReturn(new VerificationCode($code));
    }

    public function testVerifyCodeSuccessfully()
    {
        $this->forceVerificationCodeGeneratorCodeReturned('AAA123');

        $verification = new Verification(
            new VerificationId('11111111-1111-1111-1111-111111111111'),
            new PhoneNumber('+34699010203')
        );

        $verification = $verification->generateCode($this->verificationCodeGenerator);

        $verification->verify(new VerificationCode('AAA123'));

        static::assertTrue($verification->verified()->isVerified());
    }

    public function testCannotVerifySameVerificationTwice()
    {
        static::expectException(\Exception::class);

        $this->forceVerificationCodeGeneratorCodeReturned('AAA123');

        $verification = new Verification(
            new VerificationId('11111111-1111-1111-1111-111111111111'),
            new PhoneNumber('+34699010203')
        );

        $verification = $verification->generateCode($this->verificationCodeGenerator);

        $verification
            ->verify(new VerificationCode('AAA123'))
            ->verify(new VerificationCode('AAA123'));
    }
}