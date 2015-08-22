<?php

/**
 * NullPaymentMethod short summary.
 *
 * NullPaymentMethod description.
 *
 * @version 1.0
 * @author stwalkerster
 */
class NullPaymentMethod extends PaymentMethodBase implements IPaymentMethod
{
    public function getName()
    {
        return "None";
    }
    
    /**
     * Summary of isAutomatic
     * @return bool
     */
    public function isAutomatic()
    {
        return false;
    }

    public function getPaymentWorkflow()
    {
        return array();
    }

    public function createPayment(Signup $signup, $amount)
    {
        $payment = new Payment();
        $payment->setSignup($signup->getId());
        $payment->setMethod(null);
        $payment->setStatus(PaymentStatus::UNKNOWN);
        $payment->setAmount(0);

        return $payment;
    }
}
