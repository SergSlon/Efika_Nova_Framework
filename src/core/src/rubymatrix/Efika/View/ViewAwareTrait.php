<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;


trait ViewAwareTrait {

    protected $view = null;

    /**
     * @param \Efika\View\ViewInterface|null $view
     */
    public function setView(ViewInterface $view)
    {
        $this->view = $view;
    }

    /**
     * @return \Efika\View\ViewInterface|\Efika\View\View|null
     */
    public function getView()
    {
        return $this->view;
    }
}