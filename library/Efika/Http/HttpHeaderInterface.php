<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http;

interface HttpHeaderInterface
{

    /**
     * Initialize headers with optional headers key-value-pair. Key represents
     * field-name and value represents field-value
     * @param array $headers
     */
    public function __construct(array $headers = []);

    /**
     * @abstract
     * @param string $name
     * @param string $value
     * @param string $delimiter
     * @return HttpHeaderInterface
     */
    public function add($name, $value, $delimiter = ':');

    /**
     * @abstract
     * @param $name
     * @return HttpHeaderInterface
     */
    public function remove($name);

    /**
     * @abstract
     * @param $name
     * @return HttpHeaderInterface
     */
    public function exists($name);

    /**
     * @return HttpHeaderInterface
     */
    public function getHeaders();

}
