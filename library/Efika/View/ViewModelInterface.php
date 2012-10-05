<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;

/**
 * manage view-data
 * combines var-collection, renderer and view resolver
 */
interface ViewModelInterface
{
    /**
     * Uses ViewResolverInterface to resolve und set the view path
     * @abstract
     * @param $path
     * @return mixed
     */
    public function setViewPath($path);

    /**
     * @abstract
     * @return mixed
     */
    public function getViewPath();

    /**
     * Set a template. Name represents path/to/filename starting with view-path
     * and without file-extension
     * @abstract
     * @param $file
     * @return mixed
     */
    public function setTemplate($file);

    /**
     * @abstract
     * @return mixed
     */
    public function getTemplate();

    /**
     * Assign a var to current template
     * @abstract
     * @param $name
     * @param $value
     * @return mixed
     */
    public function assignVar($name,$value);

    /**
     * Revoke var from Template
     * @abstract
     * @param $name
     * @return mixed
     */
    public function revokeVar($name);

    public function setVarCollection($object);

    /**
     * If no varCollection has been set, the varCollection will get by DI
     * @abstract
     * @return mixed
     */
    public function getVarCollection();
    public function setRenderer($object);

    /**
     * If no renderer has been set, the renderer will get by DI
     * @abstract
     * @return mixed
     */
    public function getRenderer();

    public function setResolver($object);

    /**
     * If no resolver has been set, the resolver will get by DI
     * @abstract
     * @return mixed
     */
    public function getResolver();
}
