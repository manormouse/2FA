<?php declare(strict_types=1);

namespace App\Domain;

class Verification
{
    const CODE_EXPIRATION_IN_MINUTES = 5;

    const MAX_RETRIES = 3;

    /** @var VerificationId */
    private $id;

    /** @var PhoneNumber */
    private $phoneNumber;

    /** @var VerificationCode */
    private $code;

    /** @var Retries */
    private $retries;

    /** @var Verified */
    private $verified;

    /** @var CreatedAt */
    private $createdAt;

    /** @var ExpiresAt */
    private $expiresAt;

    public function __construct(VerificationId $aVerificationId, PhoneNumber $anPhoneNumber)
    {
        $this->id          = $aVerificationId;
        $this->phoneNumber = $anPhoneNumber;
        $this->retries     = new Retries(0);
        $this->verified    = new Verified(false);
        $this->createdAt   = new CreatedAt((new \DateTimeImmutable(CURRENT_DATETIME)));
    }

    /**
     * @return VerificationId
     */
    public function id(): VerificationId
    {
        return $this->id;
    }

    /**
     * @return VerificationCode
     */
    public function code(): VerificationCode
    {
        return $this->code;
    }

    public function phoneNumber(): PhoneNumber
    {
        return $this->phoneNumber;
    }

    public function verified(): Verified
    {
        return $this->verified;
    }

    public function generateCode(VerificationCodeGenerator $verificationCodeGenerator): self
    {
        if ($this->code !== null) {
            throw new \Exception('Code can be generated only once by verification');
        }

        $this->code = $verificationCodeGenerator->generate();

        $expiredDateTime = (new \DateTimeImmutable(CURRENT_DATETIME))
            ->modify('+' . self::CODE_EXPIRATION_IN_MINUTES . ' minutes');

        $this->expiresAt = new ExpiresAt($expiredDateTime);

        return $this;
    }

    private function assertIsNotExpired()
    {
        $currentDatetime = new \DateTimeImmutable(CURRENT_DATETIME);
        $expiredDateTime = new \DateTimeImmutable($this->expiresAt->expiresAt());

        if ($currentDatetime->diff($expiredDateTime)->invert === 1) {
            throw new \Exception("Code is expired already");
        }

        return $this;
    }

    private function assertIsVerified()
    {
        if ($this->verified->isVerified()) {
            throw new \Exception('Code verified already');
        }

        return $this;
    }

    private function assertNumberOfRetries()
    {
        if ($this->retries->isGreaterOrEquals(new Retries(self::MAX_RETRIES))) {
            throw new \Exception('You achieve the number of maximum retries for this code. Generate a new one');
        }

        return $this;
    }

    private function assertValidCode(VerificationCode $aVerificationCode)
    {
        if (!$this->code->isEquals($aVerificationCode)) {
            $this->retries = $this->retries->increment();

            throw new \Exception('Incorrect code');
        }

        return $this;
    }

    public function verify(VerificationCode $aVerificationCode)
    {
        $this
            ->assertIsVerified()
            ->assertIsNotExpired()
            ->assertNumberOfRetries()
            ->assertValidCode($aVerificationCode);

        $this->verified = new Verified(true);
        $this->retries  = $this->retries->increment();

        return $this;
    }
}