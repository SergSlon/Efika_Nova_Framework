<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;
use Efika\Common\Collection;

/**
 * manage view-data
 * combines var-collection, renderer and view resolver
 */
interface ViewModelInterface
{

    public function viewHelper($id);

    /**
     * Uses ResolverEngineInterface to resolve und set the view path
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
     * @param string $extension
     * @return mixed
     */
    public function setView($file,$extension=ViewInterface::DEFAULT_VIEW_FILE_EXTENSION);

    /**
     * @abstract
     * @return mixed
     */
    public function getView();

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
     * @return Collection
     */
    public function getVarCollection();

}
