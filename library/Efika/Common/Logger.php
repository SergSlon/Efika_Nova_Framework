<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Common;


use SplQueue;

class Logger extends SplQueue

{
    use SingletonTrait;

    const DEFAULT_SCOPE_ID = 'global';

    private $scopes = [];
    private $scopeId = self::DEFAULT_SCOPE_ID;

    public function addMessage($message, $object = null, $file = null, $line = null, $id = null)
    {
        $data = [
            'message' => $message,
            'object' => $object,
            'file' => $file,
            'line' => $line,
        ];

        return $this->addMessageRaw($data, $id);

    }

    public function addMessageRaw($data, $id = null)
    {
        $id === null ? $this->push($data) : $this->offsetSet($id, $data);
        return $this;
    }

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

    public function processData($data = []){

        foreach($this as $item){
            $data[] = [
                'data' => $item,
                'scope' => $this->getScopeId(),
            ];
        }

        if(count($this->getScopes()) > 0){
            foreach($this->getScopes() as $scope){
                $data = $scope->processData($data);
            }
        }

        return $data;

    }

    public function toText()
    {
        $data = $this->processData();
        $lines = [];


        foreach($data as $lineElement){
            $line = $lineElement['scope'];
            $line .= ': ';
            $line .= $lineElement['data']['message'];
            $lines[] = $line;
        }

        return implode("\n",$lines);
    }

    public function toArray()
    {

    }

    public function toFile()
    {

    }

    public function getScopeId()
    {
        return $this->scopeId;
    }

    public function setScopeId($id)
    {
        $this->scopeId = $id;
    }

    public function getScopes()
    {
        return $this->scopes;
    }
}