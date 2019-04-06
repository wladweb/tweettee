<?php

namespace Wladweb\Tweettee\Includes\Core\I;

interface SessionInterface
{
    public function hasTokens();
    public function getTokens();
    public function setTokens(array $token);
    public function destroy();
}
