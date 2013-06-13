<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http;

/**
 * @see http://de.wikipedia.org/wiki/Uniform_Resource_Locator
 */
interface HttpUrlInterface
{
    /**
     * @param null $url
     */
    public function __construct($url = null);

    /**
     * @abstract
     * @return string
     */
    public function getFragment();

    /**
     * @abstract
     * @return mixed
     */
    public function getHost();

    /**
     * @abstract
     * @return mixed
     */
    public function getPassword();

    /**
     * @abstract
     * @return mixed
     */
    public function getPort();

    /**
     * @abstract
     * @return mixed
     */
    public function getScheme();

    /**
     * @abstract
     * @return mixed
     */
    public function getQuery();

    /**
     * @abstract
     * @return mixed
     */
    public function getUrlPath();

    /**
     * @abstract
     * @param string $value
     * @return HttpUrlInterface
     */
    public function setFragment($fragment);

    /**
     * @abstract
     * @param string $value
     * @return mixed
     */
    public function setHost($host);

    /**
     * @abstract
     * @param string $value
     * @return mixed
     */
    public function setPassword($password);

    /**
     * @abstract
     * @param string | int $value
     * @return mixed
     */
    public function setPort($port);

    /**
     * @abstract
     * @param string $value
     * @param string $delimiter
     * @return mixed
     */
    public function setScheme($scheme, $delimiter = '://');

    /**
     * @abstract
     * @param string | array $value
     * @return mixed
     */
    public function setQuery($searchPart);

    /**
     * @abstract
     * @param string | array $value
     * @return mixed
     */
    public function setUrlPath($urlPath);
}
