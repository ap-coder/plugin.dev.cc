<?php

namespace WPSentry\ScopedVendor\Http\Message;

use WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface;
/**
 * Formats a request and/or a response as a string.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Formatter
{
    /**
     * Formats a request.
     *
     * @param RequestInterface $request
     *
     * @return string
     */
    public function formatRequest(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request);
    /**
     * Formats a response.
     *
     * @param ResponseInterface $response
     *
     * @return string
     */
    public function formatResponse(\WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface $response);
}
