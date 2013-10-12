<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http\Filter;


use Efika\Di\DiContainerAwareTrait;

class FilterClassProvider {

    use DiContainerAwareTrait;

    const DEFAULT_FILTER_CHAIN = 'Efika\Application\Filter\FilterChain';
    const PRE_FILTER_CHAIN = 'Efika\Application\Filter\PreFilterChain';
    const POST_FILTER_CHAIN = 'Efika\Application\Filter\PostFilterChain';

    public function getFilterChain(){
        return $this->getDiContainer()->getClassAsService(self::DEFAULT_FILTER_CHAIN)->applyInstance();
    }

    public function getPreFilterChain(){
        return $this->getDiContainer()->getClassAsService(self::PRE_FILTER_CHAIN,$this->getFilterChain())->applyInstance();
    }

    public function getPostFilterChain(){
        return $this->getDiContainer()->getClassAsService(self::POST_FILTER_CHAIN,$this->getFilterChain())->applyInstance();
    }

}