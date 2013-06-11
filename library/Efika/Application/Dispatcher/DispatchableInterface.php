<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Dispatcher;
use Efika\Http\HttpRequestInterface;
use Efika\Http\HttpResponseInterface;

/**
 * TODO: Rename to DispatchableInterface
 */
interface DispatchableInterface {
    /**
     * @internal param \Efika\Http\HttpRequestInterface $request
     * @internal param \Efika\Http\HttpResponseInterface $response
     * @return void
     */
    public function dispatch();
}