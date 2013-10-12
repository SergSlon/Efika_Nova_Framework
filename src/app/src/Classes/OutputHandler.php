<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication;

/**
 * sample class to handle output
 */
class OutputHandler
{

    public $content = [];
    protected $htmlMode = ENT_HTML5;
    protected $encoding = 'UTF-8';

    public function addContent($content)
    {
        $this->content[] = $content;
    }

    public function display($lineBreak = "\n")
    {
        foreach ($this->content as $content) {
            echo $content . $lineBreak;
        }
    }

    public function setHtmlMode($htmlMode = ENT_HTML5)
    {
        $this->htmlMode = $htmlMode;
    }

    public function getHtmlMode()
    {
        return $this->htmlMode;
    }

    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    public function getEncoding()
    {
        return $this->encoding;
    }

}