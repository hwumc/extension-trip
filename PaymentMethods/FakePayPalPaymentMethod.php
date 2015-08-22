<?php

/**
 * FakePayPalPaymentMethod short summary.
 *
 * FakePayPalPaymentMethod description.
 *
 * @version 1.0
 * @author stwalkerster
 */
class FakePayPalPaymentMethod extends PaymentMethodBase implements IPaymentMethod
{
    public function getName()
    {
        return "Fake PayPal";
    }

    public function calculateHandlingCharge($amount)
    {
        // PayPal's standard transaction fee is 3.4% + 20p per transaction
        return round(($amount * 0.034) + 0.20, 2);
    }

    public function getAuthorisation(Payment $payment)
    {
        $payment->setReference(sha1(microtime()));
        $payment->save();

        $reference = $payment->getReference();

        $querystring = urlencode("?paymentId=$reference&token=EC-60U79048BN7719609&PayerID=7E7MGXCWTTKK2");
        $module = get_class($this);

        return "http://scimon.stwalkerster.net/paypal.php?module=$module&query=$querystring";
    }
    
    public function isAutomatic()
    {
        return true;
    }

    public function getPaymentWorkflow()
    {
        return array(
            PaymentStatus::AUTHORISED => array(PaymentStatus::CANCELLED, PaymentStatus::PAID),
            PaymentStatus::PAID => array(PaymentStatus::REFUNDED)
            );
    }

    public function markAsNotPaid(Payment $payment)
    {
        // Replace the payment with a manual payment

        $signup = Signup::getById($payment->getSignup());
        
        $paymentMethod = new ManualPaymentMethod();
        $payment->delete();

        // Use the old amount
        $payment = $paymentMethod->createPayment($signup, $payment->getAmount());
        $payment->save();

        return true;
    }

    public function callback($approved)
    {
        $paymentReference = $_GET['paymentId'];
        $payment = Payment::getByReference($paymentReference);

        if($approved)
        {
            $payment->setStatus(PaymentStatus::AUTHORISED);
            $payment->save();
        }
        else
        {
            Session::appendError("payment-failed");

            $payment->setStatus(PaymentStatus::NOT_PAID);
            $payment->save();
        }

        $tripId = Signup::getById($payment->getSignup())->getTrip();

        global $cScriptPath;
        return $cScriptPath . "/Trips/list/" . $tripId;
    }
}
