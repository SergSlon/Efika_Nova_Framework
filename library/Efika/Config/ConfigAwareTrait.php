<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Config;


trait ConfigAwareTrait {
    /**
     * @var array
     */
    private $config = [];

    /**
     * @return Config
     */
    public function getConfig()
    {
        if (!($this->config instanceof Config)) {
            $this->setConfig();
        }
        return $this->config;
    }

    /**
     * @param null $config
     * @return mixed|void
     */
    public function setConfig(array $config = [])
    {
        if (!($config instanceof Config)) {
            $this->config = new Config($config);
        } else {
            $this->config = $config;
        }
    }
}