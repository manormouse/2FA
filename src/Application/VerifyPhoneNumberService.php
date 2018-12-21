<?php declare(strict_types=1);

namespace App\Application;

use App\Domain\PhoneNumber;
use App\Domain\Sender;
use App\Domain\Verification;
use App\Domain\VerificationCodeGenerator;
use App\Domain\VerificationRepositoryInterface;

class VerifyPhoneNumberService
{
    /** @var VerificationRepositoryInterface */
    private $verificationRepository;

    /** @var VerificationCodeGenerator */
    private $verificationCodeGenerator;

    /** @var Sender */
    private $sender;

    /**
     * VerifyPhoneNumberService constructor.
     *
     * @param VerificationRepositoryInterface $verificationRepository    Verification repository.
     * @param VerificationCodeGenerator       $verificationCodeGenerator Verification code generator.
     * @param Sender                          $sender                    Sender for code.
     */
    public function __construct(
        VerificationRepositoryInterface $verificationRepository,
        VerificationCodeGenerator $verificationCodeGenerator,
        Sender $sender
    ) {
        $this->verificationRepository    = $verificationRepository;
        $this->verificationCodeGenerator = $verificationCodeGenerator;
        $this->sender                    = $sender;
    }

    /**
     * Verifies phone number sending a code to this phone.
     *
     * @param VerifyPhoneNumberRequest $request Service request.
     *
     * @return VerifyPhoneNumberResponse
     */
    public function execute(VerifyPhoneNumberRequest $request): VerifyPhoneNumberResponse
    {
        $phoneNumber    = new PhoneNumber($request->phoneNumber());
        $verificationId = $this->verificationRepository->generateId();

        $verification = new Verification($verificationId, $phoneNumber);
        $verification->generateCode($this->verificationCodeGenerator);

        $verification = $this->verificationRepository->persist($verification);

        $this->sender->send($phoneNumber, $verification->code());

        return new VerifyPhoneNumberResponse($verification->id()->id(), $verification->code()->code());
    }
}
