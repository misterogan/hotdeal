<?php

use App\Helpers\OrderDetails;

Class OrderDetailsBuilder{

    private $invoiceNumber;
    private $status;

    public function setInvoiceNumber($invoiceNumber){
        $this->invoiceNumber = $invoiceNumber;
        return $this;
    }

    public function setStatus($status){
        $this->invoiceNumber = $status;
        return $this;
    }

    public function build() : OrderDetails {
        return new OrderDetails(
            $this->invoiceNumber,
            $this->status
        );
    }   

}