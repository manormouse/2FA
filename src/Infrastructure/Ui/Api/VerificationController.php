<?php declare(strict_types=1);

namespace App\Infrastructure\Ui\Api;

use App\Application\CheckVerificationCodeRequest;
use App\Application\CheckVerificationCodeService;
use App\Application\VerifyPhoneNumberRequest;
use App\Application\VerifyPhoneNumberService;
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
     * @param Request $request
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

        $verifyPhoneNumberResponse = $this->verifyPhoneNumberService->execute($verifyPhoneNumberRequest);

        $responseData = ['id' => $verifyPhoneNumberResponse->verificationId()];

        return $this->buildSuccess($responseData, Response::HTTP_CREATED);
    }

    /**
     * Check verification code
     *
     * @param Request $request
     * @param $verificationId
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

        $checkVerificationCodeResponse = $this->checkVerificationCodeService->execute($checkVerificationCodeRequest);

        $responseData = [
            'phoneNumber' => $checkVerificationCodeResponse->phoneNumber(),
            'verified'    => $checkVerificationCodeResponse->verified()
        ];

        return $this->buildSuccess($responseData, Response::HTTP_OK);
    }
}
