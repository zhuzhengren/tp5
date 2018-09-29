<?php

namespace app\common;


class Temp {
    private $name;    
    
    public function __construct($name = "zhu") {
        $this->name = $name;
    }

    public function getName(){
        return '方法是: '.__METHOD__.'属性是： '.$this->name;
    }
    public function setName($name){
        $this->name = $name;
    }
}
