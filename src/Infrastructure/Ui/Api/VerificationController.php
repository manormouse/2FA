<?php declare(strict_types=1);

namespace App\Infrastructure\Ui\Api;

use App\Application\CheckVerificationCodeRequest;
use App\Application\CheckVerificationCodeService;
use App\Application\VerifyPhoneNumberRequest;
use App\Application\VerifyPhoneNumberService;
use App\Domain\IncorrectVerificationCode;
use App\Domain\InvalidPhoneNumber;
use App\Domain\InvalidVerificationCode;
use App\Domain\InvalidVerificationId;
use App\Domain\MaximumNumberOfRetries;
use App\Domain\VerificationCodeGeneratedAlready;
use App\Domain\VerificationDoesNotExists;
use App\Domain\VerificationIsExpired;
use App\Domain\VerificationVerifiedAlready;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificationController
{
    /** @var VerifyPhoneNumberService */
    private $verifyPhoneNumberService;

    /** @var CheckVerificationCodeService */
    private $checkVerificationCodeService;

    public function __construct(
        VerifyPhoneNumberService $verifyPhoneNumberService,
        CheckVerificationCodeService $checkVerificationCodeService
    ) {
        $this->verifyPhoneNumberService     = $verifyPhoneNumberService;
        $this->checkVerificationCodeService = $checkVerificationCodeService;
    }

    /**
     * Returns the missing parameters required for the given input data.
     *
     * @param array $requestData
     * @param array $mandatoryParameters
     *
     * @throws InvalidParametersException
     */
    private function assertMandatoryParameters(array $requestData, array $mandatoryParameters)
    {
        $missingParameters = [];

        foreach ($mandatoryParameters as $mandatoryParameter) {
            if (!in_array($mandatoryParameter, array_keys($requestData))) {
                $missingParameters[] = $mandatoryParameter;
            }
        }

        if (!empty($missingParameters)) {
            throw new InvalidParametersException('Missing mandatory parameters: ' . implode(', ', $missingParameters));
        }
    }

    /**
     * @param null $data
     * @param int $code
     * @return JsonResponse
     */
    private function buildSuccess($data = null, $code = Response::HTTP_OK)
    {
        return new JsonResponse($data, $code);
    }

    /**
     * @param int $errorCode
     * @param string $errorMessage
     * @return JsonResponse
     */
    private function buildError($errorCode = Response::HTTP_BAD_REQUEST, $errorMessage = '')
    {
        return new JsonResponse(['errorCode' => $errorCode, 'errorMessage' => $errorMessage], $errorCode);
    }

    /**
     * Sends verification code.
     *
     * @param Request $request Http request.
     *
     * @return JsonResponse
     */
    public function verify(Request $request)
    {
        try {
            $this->assertMandatoryParameters($request->request->all(), ['phoneNumber']);
        } catch (InvalidParametersException $ex) {
            return $this->buildError(Response::HTTP_BAD_REQUEST, $ex->getMessage());
        }

        $verifyPhoneNumberRequest = new VerifyPhoneNumberRequest($request->get('phoneNumber'));

        try {
            $verifyPhoneNumberResponse = $this->verifyPhoneNumberService->execute($verifyPhoneNumberRequest);
        } catch (InvalidPhoneNumber | VerificationCodeGeneratedAlready $ex) {
            return $this->buildError(Response::HTTP_BAD_REQUEST, $ex->getMessage());
        }
        $responseData = [
            'id'   => $verifyPhoneNumberResponse->verificationId(),
            'code' => $verifyPhoneNumberResponse->code()
        ];

        return $this->buildSuccess($responseData, Response::HTTP_CREATED);
    }

    /**
     * Check verification code
     *
     * @param Request $request        Http request.
     * @param string  $verificationId Verification code.
     *
     * @return JsonResponse
     */
    public function check(Request $request, $verificationId)
    {
        try {
            $this->assertMandatoryParameters($request->request->all(), ['code']);
        } catch (InvalidParametersException $ex) {
            return $this->buildError(Response::HTTP_BAD_REQUEST, $ex->getMessage());
        }

        $checkVerificationCodeRequest = new CheckVerificationCodeRequest($verificationId, $request->get('code'));

        try {
            $checkVerificationCodeResponse = $this->checkVerificationCodeService->execute($checkVerificationCodeRequest);
        } catch (InvalidVerificationId $ex) {
            return $this->buildError(Response::HTTP_NOT_FOUND, $ex->getMessage());
        } catch (InvalidVerificationCode $ex) {
            return $this->buildError(Response::HTTP_BAD_REQUEST, $ex->getMessage());
        } catch (VerificationDoesNotExists $ex) {
            return $this->buildError(Response::HTTP_NOT_FOUND, $ex->getMessage());
        } catch (VerificationIsExpired | IncorrectVerificationCode | VerificationVerifiedAlready $ex) {
            return $this->buildError(Response::HTTP_BAD_REQUEST, $ex->getMessage());
        } catch (MaximumNumberOfRetries $ex) {
            return $this->buildError(
                Response::HTTP_BAD_REQUEST,
                'You achieved the maximum number of retries allowed. Generate a new verification'
            );
        }

        $responseData = [
            'phoneNumber' => $checkVerificationCodeResponse->phoneNumber(),
            'verified'    => $checkVerificationCodeResponse->verified()
        ];

        return $this->buildSuccess($responseData, Response::HTTP_OK);
    }
}
