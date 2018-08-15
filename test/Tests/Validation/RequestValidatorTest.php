<?php

namespace MaxBeckers\AmazonAlexa\Tests;

use MaxBeckers\AmazonAlexa\Exception\RequestInvalidSignatureException;
use MaxBeckers\AmazonAlexa\Exception\RequestInvalidTimestampException;
use MaxBeckers\AmazonAlexa\Request\Request;
use MaxBeckers\AmazonAlexa\Request\Request\Standard\IntentRequest;
use MaxBeckers\AmazonAlexa\Validation\RequestValidator;
use PHPUnit\Framework\TestCase;

/**
 * @author Maximilian Beckers <beckers.maximilian@gmail.com>
 */
class RequestValidatorTest extends TestCase
{
    public function testInvalidRequestTime()
    {
        $requestValidator = new RequestValidator();

        $intentRequest            = new IntentRequest();
        $intentRequest->type      = 'test';
        $intentRequest->timestamp = new \DateTime('-1 hour');
        $request                  = new Request();
        $request->request         = $intentRequest;

        $this->expectException(RequestInvalidTimestampException::class);
        $requestValidator->validate($request);
    }

    public function testInvalidSignatureCertChainUrl()
    {
        $requestValidator = new RequestValidator();

        $intentRequest                  = new IntentRequest();
        $intentRequest->type            = 'test';
        $intentRequest->timestamp       = new \DateTime();
        $request                        = new Request();
        $request->request               = $intentRequest;
        $request->signatureCertChainUrl = 'wrong path';
        $request->signature             = 'none';

        $this->expectException(RequestInvalidSignatureException::class);
        $requestValidator->validate($request);
    }

    public function testWrongSignatureCertChainUrl()
    {
        $requestValidator = new RequestValidator();

        $intentRequest                  = new IntentRequest();
        $intentRequest->type            = 'test';
        $intentRequest->timestamp       = new \DateTime();
        $request                        = new Request();
        $request->request               = $intentRequest;
        $request->signatureCertChainUrl = 'https://s3.amazonaws.com/echo.api/test.pem';
        $request->signature             = 'none';

        $this->expectException(RequestInvalidSignatureException::class);
        $requestValidator->validate($request);
    }
}
