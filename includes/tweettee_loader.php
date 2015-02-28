<?php

namespace Tweettee\Includes;

class Tweettee_Loader{
    private $actions;
    private $filters;
    public function __construct() {
        $this->actions = array();
        $this->filters = array();
    }
    
    public function add_action($hook, $instance, $method, $priority = 10, $num_args = 1){
        $this->actions = $this->add($this->actions, $hook, $instance, $method, $priority, $num_args);
    }
    
    public function add_filter($hook, $instance, $method, $priority = 10, $num_args = 1){
        $this->filters = $this->add($this->filters, $hook, $instance, $method, $priority, $num_args);
    }
    
    private function add($hooks, $hook, $instance, $method, $priority, $num_args){
        $hooks[] = array(
            'hook' => $hook,
            'instance' => $instance,
            'method' => $method,
            'priority' => $priority,
            'num_args' => $num_args
        );
        return $hooks;
    }
    
    public function load_start(){
        foreach ($this->actions as $action){
            add_action($action['hook'], array($action['instance'], $action['method']), $action['priority'], $action['num_args']);
        }
        
        foreach ($this->filters as $filter){
            add_filter($filter['hook'], array($filter['instance'], $filter['method']), $filter['priority'], $filter['num_args']);
        }
    }
}

