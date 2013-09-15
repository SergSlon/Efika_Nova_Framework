<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Dispatcher;
use Efika\Http\HttpRequestInterface;
use Efika\Http\HttpResponseInterface;
use Efika\Http\PhpEnvironment\Request;
use Efika\Http\PhpEnvironment\Response;

/**
 * TODO: Rename to DispatchableInterface
 */
interface DispatchableInterface {
    /**
     * @param \Efika\Http\PhpEnvironment\Request $request
     * @param \Efika\Http\PhpEnvironment\Response $response
     * @return void
     */
    public function dispatch(Request $request, Response $response);
}