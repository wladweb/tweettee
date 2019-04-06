<?php

namespace Wladweb\Tweettee\Includes\Core\I;

use Wladweb\Tweettee\Includes\Core\I\SettingsInterface;

/**
 *
 * @author wlad
 */
interface AccessInterface
{
    public function isVerified();
    public function process(SettingsInterface $options);
}
