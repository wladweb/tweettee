<?php

namespace Tweettee\Includes\Core;

abstract class Tweettee_Builder{
    
    protected $option;
    
    public function __construct(){
        $this->option = get_option('tweettee');
    }
    
    
    
    abstract public function draw_tweettee();
}

