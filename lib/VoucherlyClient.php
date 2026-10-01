<?php

namespace VoucherlyApi;

use VoucherlyApi\Http\ApiRequestor;
use VoucherlyApi\Http\CurlTransport;
use VoucherlyApi\Http\TransportInterface;
use VoucherlyApi\Service\CompanyService;
use VoucherlyApi\Service\ConceptStoreService;
use VoucherlyApi\Service\CustomerService;
use VoucherlyApi\Service\PaymentGatewayService;
use VoucherlyApi\Service\PaymentMethodService;
use VoucherlyApi\Service\PaymentService;
use VoucherlyApi\Service\ReceiptService;
use VoucherlyApi\Service\ReportService;
use VoucherlyApi\Service\StoreAreaService;
use VoucherlyApi\Service\StoreService;
use VoucherlyApi\Service\TerminalService;

final class VoucherlyClient
{
    public const VERSION = '2.0.0';

    public const DEFAULT_BASE_URL = 'https://api.voucherly.it';

    private const TELEMETRY_HEADERS = [
        'os' => 'x-voucherly-os',
        'osVersion' => 'x-voucherly-osversion',
        'osFramework' => 'x-voucherly-osframework',
        'app' => 'x-voucherly-app',
        'appVersion' => 'x-voucherly-appversion',
        'appHouse' => 'x-voucherly-apphouse',
        'deviceType' => 'x-voucherly-devicetype',
    ];

    private const OPTIONS = ['apiKey', 'merchantId', 'tenant', 'baseUrl', 'connectTimeout', 'timeout', 'transport', 'os', 'osVersion', 'osFramework', 'app', 'appVersion', 'appHouse', 'deviceType'];

    public CompanyService $companies;

    public CustomerService $customers;

    public PaymentMethodService $paymentMethods;

    public PaymentService $payments;

    public PaymentGatewayService $paymentGateways;

    public ReceiptService $receipts;

    public TerminalService $terminals;

    public StoreService $stores;

    public ConceptStoreService $conceptStores;

    public StoreAreaService $storeAreas;

    public ReportService $reports;

    private ApiRequestor $requestor;

    /**
     * @param array{
     *     apiKey: string,
     *     merchantId?: ?string,
     *     tenant?: ?string,
     *     baseUrl?: string,
     *     connectTimeout?: float,
     *     timeout?: float,
     *     transport?: ?TransportInterface,
     *     os?: ?string,
     *     osVersion?: ?string,
     *     osFramework?: ?string,
     *     app?: ?string,
     *     appVersion?: ?string,
     *     appHouse?: ?string,
     *     deviceType?: ?string
     * } $options merchantId and tenant are for platform keys (`ik_`) only; connectTimeout and timeout are in seconds, 10 and 30 by default, and apply to the default cURL transport only
     */
    public function __construct(array $options)
    {
        $unknown = array_diff(array_keys($options), self::OPTIONS);
        if ([] !== $unknown) {
            throw new \InvalidArgumentException('Unknown VoucherlyClient option: ' . implode(', ', $unknown) . '.');
        }

        $apiKey = $options['apiKey'] ?? null;
        if (!\is_string($apiKey) || '' === $apiKey) {
            throw new \InvalidArgumentException('The apiKey option is required.');
        }

        $transport = $options['transport'] ?? null;
        if (null === $transport) {
            $transport = new CurlTransport($options['connectTimeout'] ?? 10, $options['timeout'] ?? 30);
        } elseif (!$transport instanceof TransportInterface) {
            throw new \InvalidArgumentException('The transport option must implement ' . TransportInterface::class . '.');
        } elseif (isset($options['connectTimeout']) || isset($options['timeout'])) {
            throw new \InvalidArgumentException('The connectTimeout and timeout options apply to the default transport only: set them on your transport.');
        }

        $headers = [
            'Voucherly-API-Key' => $apiKey,
            'User-Agent' => 'VoucherlyApiPhpSdk/' . self::VERSION,
        ];
        $optionalHeaders = ['merchantId' => 'Voucherly-Merchant-Id', 'tenant' => 'Voucherly-Tenant'] + self::TELEMETRY_HEADERS;
        foreach ($optionalHeaders as $option => $header) {
            $value = $options[$option] ?? null;
            if (null !== $value && '' !== (string) $value) {
                $headers[$header] = (string) $value;
            }
        }

        $this->requestor = new ApiRequestor($options['baseUrl'] ?? self::DEFAULT_BASE_URL, $headers, $transport);
        $this->companies = new CompanyService($this->requestor);
        $this->customers = new CustomerService($this->requestor);
        $this->paymentMethods = new PaymentMethodService($this->requestor);
        $this->payments = new PaymentService($this->requestor);
        $this->paymentGateways = new PaymentGatewayService($this->requestor);
        $this->receipts = new ReceiptService($this->requestor);
        $this->terminals = new TerminalService($this->requestor);
        $this->stores = new StoreService($this->requestor);
        $this->conceptStores = new ConceptStoreService($this->requestor);
        $this->storeAreas = new StoreAreaService($this->requestor);
        $this->reports = new ReportService($this->requestor);
    }
}
