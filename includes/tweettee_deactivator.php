<?php

namespace Tweettee\Includes;

class Tweettee_Deactivator{
    public static function deactivate(){
        delete_option('tweettee');
    }
}

