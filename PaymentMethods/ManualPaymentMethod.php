<?php
// check for invalid entry point
if(!defined("HMS")) 
    die("Invalid entry point");

class ManualPaymentMethod extends PaymentMethodBase implements IPaymentMethod
{
    public function getName()
    {
        return "Manual";
    }
    
    public function isAutomatic()
    {
        return false;
    }

    /**
     * @return array The array of possible transitions in workflow
     */
    public function getPaymentWorkflow()
    {
        return array(
            PaymentStatus::NOT_PAID => array(PaymentStatus::PAID),
            PaymentStatus::PAID => array(PaymentStatus::REFUNDED, PaymentStatus::NOT_PAID),
            PaymentStatus::REFUNDED => array(PaymentStatus::PAID),
            );
    }
}
