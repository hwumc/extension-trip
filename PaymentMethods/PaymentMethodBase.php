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
    /**
     * Summary of createPayment
     * @param Signup $signup 
     * @param double $amount 
     * @return Payment
     */
    public function createPayment(Signup $signup, $amount)
    {
        $paymentMethods = TripPaymentMethod::getByTrip($signup->getTripObject());

        // get the method ID. We need this for the referential integrity below.
        $method = null;
        foreach($paymentMethods as $v)
        {
            if($v->getMethod() == get_class($this))
            {
                $method = $v;
                break;
            }
        }

        // So we've just requested a payment creation using a disallowed method.
        // We create the allowance here, but leave it invisible, so whatever back-end
        // operation caused this will still function, but the user can't select this
        // to pay with still, respecting the treasurer's wishes.
        // An example is when the treasurer marks a payment as paid, when it'll
        // automatically change to a manual payment from whatever automatic payment
        // method it previously was.
        // We explicitly exclude null here, as this is handled specially elsewhere.
        if($method == null && get_class($this) !== "NullPaymentMethod")
        {
            $method = new TripPaymentMethod();
            $method->setVisible(0);
            $method->setTripId($signup->getTrip());
            $method->setMethod(get_class($this));

            $method->save();
        }

        // set up the payment
        $payment = new Payment();
        $payment->setSignup($signup->getId());
        $payment->setMethod($method);
        $payment->setStatus(PaymentStatus::NOT_PAID);
        $payment->setAmount($amount);
        $payment->setHandlingCharge($this->calculateHandlingCharge($amount));
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

    /**
     * Summary of transition
     * @param Payment $payment 
     * @param string $nextWorkflowStage 
     * @return boolean
     */
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

    public function calculateHandlingCharge($amount)
    {
        return 0;
    }

    public function getAuthorisation(Payment $payment)
    {
        return false;
    }

    public function callback($approved)
    {
        // without a getAuth redirect, this is pointless.
        return false;
    }
}
