<?php

class TfIdf {
    private q;
    private d;
    private df;
    private text1;
    private text2;

    function __construct($text1, $text2){
        $this->text1 = $text1;
        $this->text2 = $text2;
    }

    public function toArray()
    {
        $this->q = explode(" ",$this->text1);
        $this->d = explode(" ",$this->text2);
    }

    public function tf()
    {
        
    }
}
