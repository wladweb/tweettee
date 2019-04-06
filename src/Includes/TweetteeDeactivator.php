<?php

namespace Wladweb\Tweettee\Includes;

use Wladweb\Tweettee\Includes\Core\TweetteeCache;

class TweetteeDeactivator
{

    public static function deactivate()
    {
        delete_option('tweettee');
        TweetteeCache::deleteTable();
    }

}
