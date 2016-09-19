<?php

if (isset($_GET['amount']) && isset($_GET['payback'])){
	$amount=$_GET['amount'];
	$payback=$_GET['payback'];
}else{
	echo 'Please set the parameters';
}

$interest_per_year=0.035;
$freq_per_year=12;

function flat_rate($amt,$payb,$int,$freq){
	$all_interest = $amt*$int*$payb;
	$ans = ($amt+$all_interest)/($freq*$payb);
	return $ans;
}


function effective_rate($amt,$payb,$int,$freq){
	$int_per_month=$int/12;
	$denominator=(1-(1/pow((1+$int_per_month),$payb*$freq)))/$int_per_month;
	$ans = $amt/$denominator;

    $int_paid=array();
    $prin_paid=array();
    $prin_left=array();
    for($x=1; $x<=$payb*$freq;$x++){
        array_push($prin_left,array('Month'=>$x,'THB'=>$amt));
        $int_temp=($amt*$int*30)/360;
        array_push($int_paid,array('Month'=>$x,'THB'=>$int_temp));

        $prin_temp=$ans-$int_temp;
        array_push($prin_paid,array('Month'=>$x,'THB'=>$prin_temp));
        $amt=$amt-$prin_temp;
    }

	return array('monthly_payback_amount'=>$ans,'interest_per_month'=>$int_paid,'pricipal_per_month'=>$prin_paid,'prin_left_per_month'=>$prin_left);
}

$test=effective_rate($amount,$payback,$interest_per_year,$freq_per_year);
$answer=array('amount'=>$amount,'payback'=>$payback,'monthly_payback'=>$test);
exit(json_encode($answer));

?>

