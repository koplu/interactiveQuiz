<?php
spl_autoload_register('loader');

function loader($className){
    $prefix = dirname(__FILE__);

    $controler = $prefix . '/controler/' . $className . '.cont.php';
    $model = $prefix . '/model/' . $className . '.class.php';
    $view = $prefix . '/view/' . $className . '.view.php';

    if (file_exists($controler)) {
        require_once $controler;
    }
    elseif (file_exists($model)) {
        require_once $model;
    
    }
    elseif (file_exists($view)) {
        require_once $view ;
    }
}
