<?php
include 'index.model.php';
include 'index.controller.php';

$model = new MonthlyPaymentCalculator();
$controller=new MonthlyPaymentController($model);
$controller->FindMonthlyAmount();
?>