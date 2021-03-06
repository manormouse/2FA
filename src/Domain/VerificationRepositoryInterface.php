<?php declare(strict_types=1);

namespace App\Domain;

interface VerificationRepositoryInterface
{
    public function ofId(VerificationId $verificationId):? Verification;

    /**
     * @param VerificationId $verificationId
     * @return Verification
     * @throws VerificationDoesNotExists
     */
    public function ofIdOrFail(VerificationId $verificationId): Verification;

    public function persist(Verification $verification): Verification;

    public function generateId(): VerificationId;
}
