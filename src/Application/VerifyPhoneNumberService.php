<?php declare(strict_types=1);

namespace App\Application;

use App\Domain\InvalidPhoneNumber;
use App\Domain\PhoneNumber;
use App\Domain\Sender;
use App\Domain\Verification;
use App\Domain\VerificationCodeGeneratedAlready;
use App\Domain\VerificationCodeGenerator;
use App\Domain\VerificationRepositoryInterface;

/**
 * Verify phone number service.
 *
 * @author: Pol Pereta Quintana <polpereta@gmail.com>
 * @since: 21/12/18
 */
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
     *
     * @throws InvalidPhoneNumber
     * @throws VerificationCodeGeneratedAlready
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
