<?php declare(strict_types=1);

namespace App\Application;

use App\Domain\VerificationCode;
use App\Domain\VerificationId;
use App\Domain\VerificationRepositoryInterface;

class CheckVerificationCodeService
{
    /** @var VerificationRepositoryInterface */
    private $verificationRepository;

    public function __construct(VerificationRepositoryInterface $verificationRepository)
    {
        $this->verificationRepository = $verificationRepository;
    }

    public function execute(CheckVerificationCodeRequest $request): CheckVerificationCodeResponse
    {
        $verificationId = new VerificationId($request->verificationId());
        $code           = new VerificationCode($request->code());

        $verification = $this->verificationRepository->ofIdOrFail($verificationId);

        try {
            $verification->verify($code);
        } catch (\Exception $ex) {
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
