<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http;

interface HttpHeaderInterface
{

    public function __construct(array $headers = []);
    public function add($name,$value,$delimiter=':');
    public function remove($name);
    public function exists($name);

}
