<?php
class MonthlyPaymentCalculator {
    //define inputs
    private $amount = 0;
    private $paybackPeriod=0;
    private $intPerYear = 0;
    private $freqPerYear = 0;

    public function set($amt,$pb,$int=0.035,$freq=12){
        if($amt > 0 && $pb > 0 && $int > 0 && $freq > 0){
            $this->amount = $amt;
            $this->paybackPeriod = $pb;
            $this->intPerYear = $int;
            $this->freqPerYear = $freq;
        }else{
            exit('Please set the correct amount and payback period');
        }
    }

    // for housing loan
    private function getEffectiveRate(){
        //localize variable
        $paybackPeriod=$this->paybackPeriod;
        $freqPerYear=$this->freqPerYear;
        $amount=$this->amount;

        //calculation
        $intPerMonth = $this->intPerYear/12;
        $denominator = (1-(1/pow((1+$intPerMonth),$paybackPeriod*$freqPerYear)))/$intPerMonth;
        $answer = $amount/$denominator;

        return $answer;
    }

    private function getGraphData(){
        //localize variable
        $paybackPeriod=$this->paybackPeriod;
        $freqPerYear=$this->freqPerYear;
        $amount=$this->amount;
        $intPerYear=$this->intPerYear;
        $payPerMonth = $this->getEffectiveRate();

        //initiate answer arrays
        $intPaid=array();
        $prinPaid=array();
        $prinLeft=array();

        //calculation
        for($x=1;$x<=$paybackPeriod*$freqPerYear;$x++){
            array_push($prinLeft,array('Month'=>$x,'THB'=>$amount));

            $intTemp=($amount*$intPerYear*30)/360;
            array_push($intPaid,array('Month'=>$x,'THB'=>$intTemp));

            $prinTemp=$payPerMonth-$intTemp;
            array_push($prinPaid,array('Month'=>$x,'THB'=>$prinTemp));
            
            $amount=$amount-$prinTemp;
        }

        $answerArray=array('interestPerMonth'=>$intPaid,'principalPerMonth'=>$prinPaid,'principalLeftPerMonth'=>$prinLeft);

        return $answerArray;
    } 

    public function getResult(){
        $amountPerMonth=$this->getEffectiveRate();
        $graph=$this->getGraphData();

        $answerArray=array('amountPerMonth'=>$amountPerMonth,'graph'=>$graph);

        return json_encode($answerArray);
    }

    //for car loan,
    // public function getFlatRate(){
    //     if(isset($this->amount) && isset($this->paybackPeriod)){
    //         $allInterest = $this->amount * $this->intPerYear * $this->paybackPeriod;
    //         $answer = ($this->amount + $allInterest)/($this->freqPerYear*$this->paybackPeriod);
    //         return $answer;
    //     }else{
    //         return 'Please set the amount and payback period';
    //     }
    // }
}
?>