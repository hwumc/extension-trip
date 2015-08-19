<?php

/**
 * PaymentMethodBase short summary.
 *
 * PaymentMethodBase description.
 *
 * @version 1.0
 * @author stwalkerster
 */
abstract class PaymentMethodBase implements IPaymentMethod
{
    public function createPayment(Signup $signup, $amount)
    {
        $payment = new Payment();
        $payment->setSignup($signup->getId());
        $payment->setMethod(get_class($this));
        $payment->setStatus(PaymentStatus::NOT_PAID);
        $payment->setAmount($amount);
        $payment->save();

        return $payment;
    }

    public function getNextWorkflowState($paymentStatus)
    {
        $workflow = $this->getPaymentWorkflow();
        return $workflow[$paymentStatus];
    }

    public function markAsPaid(Payment $payment)
    {
        $payment->setStatus(PaymentStatus::PAID);
        $payment->save();

        return true;
    }
    
    public function markAsNotPaid(Payment $payment)
    {
        $payment->setStatus(PaymentStatus::NOT_PAID);
        $payment->save();

        return true;
    }

    public function markAsCancelled(Payment $payment)
    {
        $payment->setStatus(PaymentStatus::CANCELLED);
        $payment->save();

        return true;
    }

    public function markAsAuthorised(Payment $payment)
    {
        $payment->setStatus(PaymentStatus::AUTHORISED);
        $payment->save();

        return true;
    }

    public function markAsRefunded(Payment $payment)
    {
        $payment->setStatus(PaymentStatus::REFUNDED);
        $payment->save();

        return true;
    }

    public function transition(Payment $payment, $nextWorkflowStage)
    {
        if(!in_array($nextWorkflowStage, $this->getNextWorkflowState($payment->getStatus())))
        {
            return false;
        }

        $method = PaymentStatus::getTransition($nextWorkflowStage);
        if($method !== null)
        {
            return $this->$method($payment);
        }

        return false;
    }
}
