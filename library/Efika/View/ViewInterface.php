<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;
use Efika\EventManager\EventManagerInterface;

/**
 * Factory for view management
 */
interface ViewInterface extends EventManagerInterface
{

    const DEFAULT_VIEW_FILE_EXTENSION = 'php';
    const DEFAULT_EVENT = 'Efika\View\ViewEvent';
    const DEFAULT_RESOLVER = 'Efika\View\ViewResolver';
    const DEFAULT_RENDERER = 'Efika\View\ViewRenderer';
    const BEFORE_RENDER_VIEW = 'before.render';
    const WHILE_RENDER_VIEW = 'while.render';
    const AFTER_RENDER_VIEW = 'after.render';
    const BEFORE_RESPONSE_VIEW = 'before.response';
    const WHILE_RESPONSE_VIEW = 'while.response';
    const AFTER_RESPONSE_VIEW = 'after.response';

    public function render();
    public function resolve();

}
