<?php


class MonthlyPaymentController{
    public $model;

    public function __construct($model){
        $this->model = $model;
    }

    public function FindMonthlyAmount(){
            $this->model->set($_GET['amt'],$_GET['pb'],$_GET['int'],$_GET['freq']);
            echo $this->model->getResult();
    }
}
?>
