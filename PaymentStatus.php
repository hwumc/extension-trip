<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

/**
 * PaymentStatus short summary.
 *
 * PaymentStatus description.
 *
 * @version 1.0
 * @author stwalkerster
 */
class PaymentStatus
{
    const NOT_PAID = "not-paid";
    const AUTHORISED = "payment-authorized";
    const PAID = "paid";
    const REFUNDED = "payment-refunded";
    const CANCELLED = "payment-cancelled";
    const UNKNOWN = "payment-status-unknown";

    private static function getData()
    {
        return array(
                PaymentStatus::NOT_PAID => array( 
                    "icon"              => "icon-remove",
                    "deletable"         => true,
                    "button"            => "btn-danger",
                    "transitionMethod"  => "markAsNotPaid",
                    ),
                PaymentStatus::AUTHORISED => array( 
                    "icon"              => "icon-ok",
                    "deletable"         => false,
                    "button"            => "btn-primary",
                    "transitionMethod"  => "markAsAuthorised",
                    ),
                PaymentStatus::PAID => array( 
                    "icon"              => "icon-ok",
                    "deletable"         => false,
                    "button"            => "btn-success",
                    "transitionMethod"  => "markAsPaid",
                    ),
                PaymentStatus::REFUNDED => array( 
                    "icon"              => "icon-hand-left",
                    "deletable"         => true,
                    "button"            => "btn-warning",
                    "transitionMethod"  => "markAsRefunded",
                    ),
                PaymentStatus::CANCELLED => array( 
                    "icon"              => "icon-minus-sign",
                    "deletable"         => true,
                    "button"            => "btn-warning",
                    "transitionMethod"  => "markAsCancelled",
                    ),
                PaymentStatus::UNKNOWN => array( 
                    "icon"              => "icon-question-sign",
                    "deletable"         => true,
                    "button"            => "btn-inverse",
                    "transitionMethod"  => null,
                    )                
                );
    }

    public static function getIcon($status)
    {
        $data = self::getData();
        return $data[$status]["icon"];
    }

    public static function isDeletable($status)
    {
        $data = self::getData();
        return $data[$status]["deletable"];
    }

    public static function getButton($status)
    {
        $data = self::getData();
        return $data[$status]["button"];
    }

    public static function getTransition($status)
    {
        $data = self::getData();
        return $data[$status]["transitionMethod"];
    }
}
