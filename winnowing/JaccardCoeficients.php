<?php

class JaccardCoeficients{

    private $fingerprint1;
    private $fingerprint2;

    private $intersection;
    private $union;
    private $similarity;

    function __construct($fingerprint1, $fingerprint2){

        $this->fingerprint1 = $fingerprint1;
        $this->fingerprint2 = $fingerprint2;

        $this->calculateIntersection();
        $this->calculateUnion();
        $this->calculateSimilarity();

    }

    public function getResult(){

        return $this->similarity;

    }

    function calculateIntersection(){
        $fingerprint2 = $this->fingerprint2;
        $this->intersection = array_filter($this->fingerprint1, function($fingerprint) use($fingerprint2) {
            return array_search($fingerprint, $fingerprint2) != false;
        });
    }

    function calculateUnion(){

        $intersection = $this->intersection;

        $fingerprintExcludeIntersects1 = array_filter($this->fingerprint1, function($fingerprint) use($intersection) {
            return array_search($fingerprint, $intersection) == false;
        });

        $fingerprintExcludeIntersects2 = array_filter($this->fingerprint2, function($fingerprint) use($intersection) {
            return array_search($fingerprint, $intersection) == false;
        });

        $this->union = array_merge($fingerprintExcludeIntersects1, $fingerprintExcludeIntersects2, $this->intersection);

    }

    function calculateSimilarity(){

        // this.similarity = (this.intersection.length / this.union.length) * 100
        $this->similarity = (count($this->intersection) / count($this->union)) * 100;

    }

}