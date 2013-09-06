<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;
use Efika\EventManager\EventManagerInterface;
use Efika\EventManager\EventResponseInterface;

/**
 * Factory for view management
 */
interface ViewInterface extends EventManagerInterface
{

    const DEFAULT_VIEW_FILE_EXTENSION = 'php';
    const DEFAULT_VIEW_FOLDER = 'views';
    const DEFAULT_EVENT = 'Efika\View\ViewEvent';
    const DEFAULT_EVENT_AGGREGATE = 'Efika\View\ViewEventAggregate';
    const DEFAULT_RESOLVER = 'Efika\View\ViewResolver';
    const DEFAULT_RENDERER = 'Efika\View\ViewRenderer';

    const ON_RESOLVE_BEFORE = 'view.resolve.before';
    const ON_RESOLVE = 'view.resolve';
    const ON_RESOLVE_AFTER = 'view.resolve.after';

    const ON_RENDER_BEFORE = 'view.render.before';
    const ON_RENDER = 'view.render';
    const ON_RENDER_AFTER = 'view.render.after';

    const ON_INIT = 'view.init';

    /**
     * @return EventResponseInterface
     */
    public function execute();

}
