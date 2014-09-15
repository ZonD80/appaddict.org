<?php

/**
 * Smarty extender aka template class
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Doodstrap
 */
class TEMPLATE extends Smarty {

    private $API = null;

    /**
     * Class constructor
     * @param object $API API instance
     */
    function __construct($API) {
        parent::__construct();
        $this->API = $API;
    }

    /**
     * Renders template
     * @param string $mode Site mode
     * @param string $action Site action
     * @return string HTML code
     */
    function render($mode = null, $action = null) {
        if (!$mode)
            $mode = $this->API->MODE;
        if (!$action)
            $action = $this->API->ACTION;
        $this->assign('mode', $mode);
        $this->assign('action', $action);
        $file = $this->API->CONFIG['TEMPLATE_PATH'] . DS . $mode . DS . $action . '.tpl';
        return $this->fetch($file);
    }

    /**
     * Outputs rendered template to browser
     * @param string $mode Site mode
     * @param string $action Site action
     */
    function output($mode = null, $action = null) {
        print $this->render($mode, $action);
    }

}

?>