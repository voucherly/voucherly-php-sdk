<?php

namespace VoucherlyApi\Http;

use VoucherlyApi\Exception\ConnectionException;

interface TransportInterface
{
    /**
     * Returns every response the server sends, whatever its status code.
     *
     * @throws ConnectionException when no response is received
     */
    public function send(HttpRequest $request): HttpResponse;
}
