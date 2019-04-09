<?php

namespace Wladweb\Tweettee\Includes;

use Wladweb\Tweettee\Includes\Core\TweetteeCache;
use Wladweb\Tweettee\Includes\Core\Log\Logger;

class TweetteeDeactivator
{

    public static function deactivate()
    {
        delete_option('tweettee');
        TweetteeCache::deleteTable();
        Logger::write('Plugin was deactivated. Plugin options & cache table were deleted.');
    }

}
