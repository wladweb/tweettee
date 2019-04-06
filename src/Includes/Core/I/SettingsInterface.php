<?php

namespace Wladweb\Tweettee\Includes\Core\I;

/**
 *
 * @author wlad
 */
interface SettingsInterface
{
    public function getOption($name);
    public function hasOption($name);
    public function setOption($name, $value);
}
