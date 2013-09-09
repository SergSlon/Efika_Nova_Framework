<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;


use Efika\Di\DiContainer;
use Efika\EventManager\Event;
use Efika\EventManager\EventInterface;
use Efika\EventManager\EventManagerTrait;
use Efika\EventManager\EventResponse;
use Efika\EventManager\EventResponseInterface;
use Efika\View\Engines\RendererEngineException;
use Efika\View\Engines\ResolverEngineException;

class View implements ViewInterface, ViewModelAwareInterface
{

    use EventManagerTrait;

    protected $viewModel = null;

    /**
     * @var EventResponse
     */
    private $previousEventResponse = null;

    public function __construct()
    {
        $di = DiContainer::getInstance();
        $event = new ViewEvent();
        $event->setTarget($this);
//        $event->setRenderer($di->getService(self::DEFAULT_RENDERER)->applyInstance());
//        $event->setResolver($di->getService(self::DEFAULT_RESOLVER)->applyInstance());
        $this->setEventObject($event);
        new ViewEventAggregate();
        $this->attachEventHandlerAggregate($di->getClassAsService(self::DEFAULT_EVENT_AGGREGATE)->applyInstance());
    }

    public function setViewModel(ViewModelInterface $model)
    {
        $this->viewModel = $model;
    }

    /**
     * @return null
     */
    public function getViewModel()
    {
        return $this->viewModel;
    }

    /**
     * @return EventResponse
     */
    public function getPreviousEventResponse()
    {
        return $this->previousEventResponse;
    }

    /**
     * @param $previousEventResponse
     */
    protected function setPreviousEventResponse($previousEventResponse)
    {
        $this->previousEventResponse = $previousEventResponse;
    }

    /**
     * @param $id
     * @param $arguments
     * @param $callback
     */
    public function executeViewEvent($id, $arguments, $callback)
    {

        $previousEventResponse = $this->getPreviousEventResponse();
        $eventObject = $previousEventResponse !== null && $previousEventResponse instanceof EventResponseInterface ? $previousEventResponse->getEvent() : $this->getEventObject();

        $eventObject->setName($id);
        $eventObject->setTarget($this);
        $eventObject->setArguments($arguments);

        $this->setPreviousEventResponse($this->triggerEvent($eventObject, $callback));
    }

    protected function init(callable $callback)
    {

        $di = DiContainer::getInstance();
        $this->executeViewEvent(
            self::ON_INIT,
            [
                'viewModel' => $this->getViewModel(),
                'renderer' => $di->getService(self::DEFAULT_RENDERER)->applyInstance(),
                'resolver' => $di->getService(self::DEFAULT_RESOLVER)->applyInstance(),
            ],
            $callback
        );

    }

    protected function resolve(callable $callback)
    {
        $this->executeViewEvent(self::ON_RESOLVE_BEFORE, [], $callback);
        $this->executeViewEvent(self::ON_RESOLVE, [], $callback);
        $this->executeViewEvent(self::ON_RESOLVE_AFTER, [], $callback);
    }

    protected function render(callable $callback)
    {
        $this->executeViewEvent(self::ON_RENDER_BEFORE, [], $callback);
        $this->executeViewEvent(self::ON_RENDER, [], $callback);
        $this->executeViewEvent(self::ON_RENDER_AFTER, [], $callback);
    }

    public function execute()
    {

        try {
            $view = $this;

            /**
             * stop propagantion when error occurs
             * @param EventResponse $response
             * @return bool
             */
            $callback = function ($response) use ($view) {
                return !($response->hasEvent() && !array_key_exists('errors', $response->getEvent()->getArguments()));
            };

            $this->init($callback);
            $this->resolve($callback);
            $this->render($callback);

        } catch (ResolverEngineException $e) {
//            $this->getLogger()->info('Exception: ' . $e->getMessage());
            var_dump($e);
        } catch (RendererEngineException $e) {
//            $this->getLogger()->info('Exception: ' . $e->getMessage());
            var_dump($e);
        } catch (ViewException $e) {
//            $this->getLogger()->info('Exception: ' . $e->getMessage());
            var_dump($e);
        }

        return $this->getPreviousEventResponse();
    }
}