<?php declare(strict_types=1);

namespace App\Application;

use App\Domain\IncorrectVerificationCode;
use App\Domain\InvalidVerificationCode;
use App\Domain\InvalidVerificationId;
use App\Domain\MaximumNumberOfRetries;
use App\Domain\VerificationCode;
use App\Domain\VerificationDoesNotExists;
use App\Domain\VerificationId;
use App\Domain\VerificationIsExpired;
use App\Domain\VerificationRepositoryInterface;
use App\Domain\VerificationVerifiedAlready;

/**
 * Check a verification code service.
 *
 * @author: Pol Pereta Quintana <polpereta@gmail.com>
 * @since: 21/12/18
 */
class CheckVerificationCodeService
{
    /** @var VerificationRepositoryInterface */
    private $verificationRepository;

    /**
     * CheckVerificationCodeService constructor.
     *
     * @param VerificationRepositoryInterface $verificationRepository Verification repository.
     */
    public function __construct(VerificationRepositoryInterface $verificationRepository)
    {
        $this->verificationRepository = $verificationRepository;
    }

    /**
     * Checks a verification code for given verification.
     *
     * @param CheckVerificationCodeRequest $request
     *
     * @return CheckVerificationCodeResponse
     *
     * @throws InvalidVerificationCode
     * @throws VerificationDoesNotExists
     * @throws InvalidVerificationId
     * @throws VerificationIsExpired
     * @throws IncorrectVerificationCode
     * @throws VerificationVerifiedAlready
     * @throws MaximumNumberOfRetries
     */
    public function execute(CheckVerificationCodeRequest $request): CheckVerificationCodeResponse
    {
        $verificationId = new VerificationId($request->verificationId());
        $code           = new VerificationCode($request->code());

        $verification = $this->verificationRepository->ofIdOrFail($verificationId);

        try {
            $verification->verify($code);
        } catch (IncorrectVerificationCode $ex) {
            $this->verificationRepository->persist($verification);
            throw $ex;
        }

        $this->verificationRepository->persist($verification);

        return new CheckVerificationCodeResponse(
            $verification->phoneNumber()->phoneNumber(),
            $verification->verified()->isVerified()
        );
    }
}
