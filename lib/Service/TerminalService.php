<?php

namespace VoucherlyApi\Service;

use VoucherlyApi\Model\Page;
use VoucherlyApi\Model\Terminal;
use VoucherlyApi\Request\ListTerminalParams;

final class TerminalService extends AbstractService
{
    /**
     * List all Terminals.
     *
     * @return Page<Terminal>
     */
    public function list(?ListTerminalParams $params = null): Page
    {
        return Page::constructPage($this->requestJson('GET', '/v1/terminals', null, $params), Terminal::class);
    }

    /**
     * Delete a Terminal.
     */
    public function delete(string $id): void
    {
        $this->requestNoContent('DELETE', self::path('/v1/terminals/%s', $id));
    }
}
