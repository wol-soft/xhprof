<?php

function debug($message)
{
    if (getenv('XHPROF_DEBUG')) {
        echo $message . PHP_EOL;
    }
}

function getExtensionName()
{
    if (extension_loaded('tideways'))
    {
        return 'tideways';
    }elseif(extension_loaded('tideways_xhprof'))
    {
        return 'tideways_xhprof';
    }elseif(extension_loaded('xhprof'))
    {
        return 'xhprof';
    }
    return false;
}