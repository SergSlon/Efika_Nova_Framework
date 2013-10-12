<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Common;

/**
 * Class Logger
 * @package Efika\Common
 */
class Logger

{
    use SingletonTrait;

    /**
     *
     */
    const DEFAULT_SCOPE_ID = 'global';
    const MESSAGE_TYPE_INFO = 'info';
    const MESSAGE_TYPE_WARN = 'warn';
    const MESSAGE_TYPE_ERROR = 'error';

    /**
     * @var array
     */
    private $scopes = [];
    /**
     * @var string
     */
    private $scopeId = self::DEFAULT_SCOPE_ID;
    /**
     * @var null
     */
    private static $messageCollection = [];

    /**
     * @var int
     */
    private static $duration = 0;
    /**
     * @var int
     */
    private static $memory = 0;

    /**
     * @param $message
     * @param null $object
     * @param null $file
     * @param null $line
     * @return $this
     */
    public function info($message)
    {
        self::addMessageRaw($message, self::MESSAGE_TYPE_INFO, $this->getScopeId());
        return $this;
    }


    /**
     * @param $message
     * @param $type
     * @param $scope
     * @return $this
     */
    public static function addMessageRaw($message, $type, $scope)
    {

        $data = [
            'message' => $message,
            'scope' => $scope
        ];

        self::$messageCollection[$type][] = $data;
    }

    /**
     * @param $id
     * @return Logger
     */
    public function scope($id)
    {
        if (array_key_exists($id, $this->scopes)) {
            $scope = $this->scopes[$id];
        } else {
            $scope = new self();
            $scope->setScopeId($id);
            $this->scopes[$id] = $scope;
        }

        return $scope;
    }

    /**
     * @return string
     */
    public function toText()
    {
        $data = self::$messageCollection;
        $lines = [];

        foreach($data as $key => $items){
            $lines[] = sprintf('<b>%s</b>',$key);
            foreach($items as $lineElement){
                $lines[] = sprintf(
                    '%s: %s',
                    $lineElement['scope'],
                    $lineElement['message']
                );
            }
        }

        return implode("\n",$lines);
    }

    /**
     *
     */
    public function toArray()
    {
        return self::$messageCollection;
    }

    /**
     *
     */
    public function toFile()
    {

    }

    /**
     * @return string
     */
    public function __toString(){
        return $this->toText();
    }

    /**
     * @return string
     */
    public function getScopeId()
    {
        return $this->scopeId;
    }

    /**
     * @param $id
     */
    public function setScopeId($id)
    {
        $this->scopeId = $id;
    }

    /**
     * @return array
     */
    public function getScopes()
    {
        return $this->scopes;
    }
}