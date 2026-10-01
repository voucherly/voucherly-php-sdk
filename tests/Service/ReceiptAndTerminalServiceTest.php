<?php

namespace VoucherlyApi\Tests\Service;

use VoucherlyApi\Enum\TerminalStatus;
use VoucherlyApi\Model\Terminal;
use VoucherlyApi\Request\ListTerminalParams;
use VoucherlyApi\Tests\Support\ServiceTestCase;

final class ReceiptAndTerminalServiceTest extends ServiceTestCase
{
    public function testRetrieveReceipt(): void
    {
        $json = $this->respondWithSample('retrieve-receipt');

        $receipt = $this->client->receipts->retrieve('rcp_1');

        $this->assertOperation('retrieve-receipt', ['id' => 'rcp_1']);
        self::assertReadsEveryMember($json, $receipt);
    }

    public function testDownloadReceipt(): void
    {
        $this->transport->respond(200, "%PDF-1.7\n\xE2\xE3", ['content-type' => 'application/pdf']);

        $pdf = $this->client->receipts->download('rcp_1');

        $request = $this->assertOperation('download-receipt', ['id' => 'rcp_1']);
        self::assertSame('application/pdf', $request->getHeaders()['Accept']);
        self::assertSame("%PDF-1.7\n\xE2\xE3", $pdf);
    }

    public function testListTerminals(): void
    {
        $json = $this->respondWithSample('list-terminal');
        $params = new ListTerminalParams();
        $params->paymentGatewayAccountId = 'pga_1';
        $params->paymentGatewayId = 'NEXI';
        $params->storeId = 'sto_1';
        $params->status = TerminalStatus::ACTIVE;
        $params->length = 50;

        $page = $this->client->terminals->list($params);

        $this->assertOperation('list-terminal', [], 'paymentGatewayAccountId=pga_1&paymentGatewayId=NEXI&storeId=sto_1&status=Active&length=50');
        self::assertInstanceOf(Terminal::class, $page->items[0]);
        self::assertReadsEveryMember($json, $page);
    }

    public function testDeleteTerminal(): void
    {
        $this->respondWithSample('delete-terminal');

        $this->client->terminals->delete('ter_1');

        $this->assertOperation('delete-terminal', ['id' => 'ter_1']);
    }
}
