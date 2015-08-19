<?php
// check for invalid entry point
if(!defined("HMS")) 
    die("Invalid entry point");

interface IPaymentMethod
{
    /**
     * Summary of getName
     * @return string
     */
    public function getName();
    
    /**
     * Summary of isAutomatic
     * @return bool
     */
    public function isAutomatic();

    /**
     * Summary of createPayment
     * @param Signup $signup 
     * @param double $amount 
     * @return Payment
     */
    public function createPayment(Signup $signup, $amount);

    public function getPaymentWorkflow();
    public function getNextWorkflowState($paymentStatus);

    public function markAsPaid(Payment $payment);
    public function markAsNotPaid(Payment $payment);
    public function markAsCancelled(Payment $payment);
    public function markAsAuthorised(Payment $payment);
    public function markAsRefunded(Payment $payment);

    public function transition(Payment $payment, $nextWorkflowStage);
}
