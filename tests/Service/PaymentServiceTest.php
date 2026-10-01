<?php

namespace VoucherlyApi\Tests\Service;

use VoucherlyApi\Enum\PaymentInclude;
use VoucherlyApi\Enum\PaymentStatus;
use VoucherlyApi\Exception\ConflictException;
use VoucherlyApi\Exception\UnprocessableEntityException;
use VoucherlyApi\Model\Transaction;
use VoucherlyApi\Request\ConfirmPaymentRequest;
use VoucherlyApi\Request\CreatePaymentRequest;
use VoucherlyApi\Request\RefundPaymentRequest;
use VoucherlyApi\Request\RetrievePaymentParams;
use VoucherlyApi\Tests\Support\ServiceTestCase;
use VoucherlyApi\Tests\Support\SpecExamples;

final class PaymentServiceTest extends ServiceTestCase
{
    private const PAYMENT_ID = 'pay_01kg2gestgepdbmsn7hs6bsrwp';

    /**
     * @dataProvider provideCreateCases
     *
     * @param array<string, mixed> $example
     */
    public function testCreate(array $example): void
    {
        $json = $this->respondWithSample('create-payment');

        $payment = $this->client->payments->create(CreatePaymentRequest::fromArray($example));

        $this->assertOperation('create-payment', [], '', $example);
        self::assertReadsEveryMember($json, $payment);
        self::assertInstanceOf(Transaction::class, $payment->transactions[0]);
    }

    /**
     * @return iterable<string, array{array<string, mixed>}>
     */
    public static function provideCreateCases(): iterable
    {
        foreach (SpecExamples::requestExamples('create-payment') as $name => $example) {
            yield $name => [$example];
        }
    }

    public function testRetrieveWaitsWithTheWaitTimeHeader(): void
    {
        $json = $this->respondWithSample('retrieve-payment');
        $params = new RetrievePaymentParams();
        $params->include = [PaymentInclude::LINES, PaymentInclude::TRANSACTIONS];
        $params->waitTime = 30;

        $payment = $this->client->payments->retrieve(self::PAYMENT_ID, $params);

        $request = $this->assertOperation('retrieve-payment', ['id' => self::PAYMENT_ID], 'include=Lines&include=Transactions');
        self::assertSame('30', $request->getHeaders()['Voucherly-Wait-Time']);
        self::assertReadsEveryMember($json, $payment);
    }

    public function testRetrieveWithoutParametersSendsNoWaitTime(): void
    {
        $this->respondWithSample('retrieve-payment');

        $this->client->payments->retrieve(self::PAYMENT_ID);

        $request = $this->assertOperation('retrieve-payment', ['id' => self::PAYMENT_ID]);
        self::assertArrayNotHasKey('Voucherly-Wait-Time', $request->getHeaders());
    }

    /**
     * @dataProvider provideConfirmCases
     *
     * @param array<string, mixed> $example
     */
    public function testConfirm(array $example): void
    {
        $json = $this->respondWithSample('confirm-payment');

        $payment = $this->client->payments->confirm(self::PAYMENT_ID, ConfirmPaymentRequest::fromArray($example));

        $this->assertOperation('confirm-payment', ['id' => self::PAYMENT_ID], '', $example);
        self::assertReadsEveryMember($json, $payment);
    }

    /**
     * @return iterable<string, array{array<string, mixed>}>
     */
    public static function provideConfirmCases(): iterable
    {
        foreach (SpecExamples::requestExamples('confirm-payment') as $name => $example) {
            yield $name => [$example];
        }
    }

    public function testConfirmWithoutRequestSendsAnEmptyObject(): void
    {
        $this->respondWithSample('confirm-payment');

        $this->client->payments->confirm(self::PAYMENT_ID);

        $this->assertOperation('confirm-payment', ['id' => self::PAYMENT_ID], '', []);
    }

    public function testConfirmThatConflictsExposesThePaymentStatus(): void
    {
        $this->transport->respond(409, ['title' => 'This operation is already processed.', 'status' => 409, 'code' => 'ALREADY_CONFIRMED', 'paymentStatus' => 'Confirmed']);

        try {
            $this->client->payments->confirm(self::PAYMENT_ID);
            self::fail('No ConflictException was thrown.');
        } catch (ConflictException $exception) {
            self::assertSame('ALREADY_CONFIRMED', $exception->getErrorCode());
            self::assertSame(PaymentStatus::CONFIRMED, $exception->getPaymentStatus());
        }
    }

    public function testConfirmThatCannotBeProcessedExposesTheOperationsAlreadyDone(): void
    {
        $this->transport->respond(422, ['title' => 'The Payment can\'t be confirmed at the requested amounts.', 'status' => 422, 'code' => 'IMPOSSIBLE_REFUND', 'operations' => [['transactionId' => 'tr_1', 'paymentGatewayId' => 'STRIPE', 'amount' => 3000]]]);

        try {
            $this->client->payments->confirm(self::PAYMENT_ID);
            self::fail('No UnprocessableEntityException was thrown.');
        } catch (UnprocessableEntityException $exception) {
            self::assertSame('IMPOSSIBLE_REFUND', $exception->getErrorCode());
            self::assertSame(3000, $exception->getExtensions()['operations'][0]['amount']);
        }
    }

    /**
     * @dataProvider provideRefundCases
     *
     * @param array<string, mixed> $example
     */
    public function testRefund(array $example): void
    {
        $json = $this->respondWithSample('refund-payment');

        $payment = $this->client->payments->refund(self::PAYMENT_ID, RefundPaymentRequest::fromArray($example));

        $this->assertOperation('refund-payment', ['id' => self::PAYMENT_ID], '', $example);
        self::assertReadsEveryMember($json, $payment);
    }

    /**
     * @return iterable<string, array{array<string, mixed>}>
     */
    public static function provideRefundCases(): iterable
    {
        foreach (SpecExamples::requestExamples('refund-payment') as $name => $example) {
            yield $name => [$example];
        }
    }

    public function testRefundWithoutRequestSendsAnEmptyObject(): void
    {
        $this->respondWithSample('refund-payment');

        $this->client->payments->refund(self::PAYMENT_ID);

        $this->assertOperation('refund-payment', ['id' => self::PAYMENT_ID], '', []);
    }

    public function testVoid(): void
    {
        $json = $this->respondWithSample('void-payment');

        $payment = $this->client->payments->void(self::PAYMENT_ID);

        $this->assertOperation('void-payment', ['id' => self::PAYMENT_ID], '', []);
        self::assertReadsEveryMember($json, $payment);
    }

    public function testDownloadReceipt(): void
    {
        $this->transport->respond(200, "%PDF-1.7\n\xE2\xE3", ['content-type' => 'application/pdf']);

        $pdf = $this->client->payments->downloadReceipt(self::PAYMENT_ID);

        $request = $this->assertOperation('download-payment-receipt', ['id' => self::PAYMENT_ID]);
        self::assertSame('application/pdf', $request->getHeaders()['Accept']);
        self::assertSame("%PDF-1.7\n\xE2\xE3", $pdf);
    }

    public function testDownloadRefundReceipt(): void
    {
        $this->transport->respond(200, "%PDF-1.7\n\xE2\xE3", ['content-type' => 'application/pdf']);

        $pdf = $this->client->payments->downloadRefundReceipt(self::PAYMENT_ID);

        $request = $this->assertOperation('download-payment-refund-receipt', ['id' => self::PAYMENT_ID]);
        self::assertSame('application/pdf', $request->getHeaders()['Accept']);
        self::assertSame("%PDF-1.7\n\xE2\xE3", $pdf);
    }
}
