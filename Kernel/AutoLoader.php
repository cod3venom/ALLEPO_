<?php

spl_autoload_register('MyAutoLoader');

function MyAutoLoader($className)
{
    $path = 'Kernel/Class.'.$className.'.php';
    if(file_exists($path))
    {
        require_once $path;
    }
}

?>