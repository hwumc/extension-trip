<?php

/**
 * PagePaymentCallback short summary.
 *
 * PagePaymentCallback description.
 *
 * @version 1.0
 * @author stwalkerster
 */
class PagePaymentCallback extends PageBase
{
    public function __construct()
    {
        $this->mPageUseRight = "trips-signup";
        $this->mPageRegisteredRights = array( "trips-signup" );
        $this->mIsSpecialPage = true;
    }

    protected function runPage()
    {
        $data = explode( "/", WebRequest::pathInfoExtension() );
        
        if( !isset( $data[0] ) || !isset( $data[1] ) ) 
        {
            return;
        }

        $paymentMethod = $data[0];
        $approval = $data[1] == "approved";

        global $cPaymentMethods;

        if(!in_array($paymentMethod, $cPaymentMethods))
        {
            return;
        }

        $method = new $paymentMethod();
        
        $redirect = $method->callback($approval);

        $this->mHeaders[] = ( "Location: " . $redirect );
        $this->mIsRedirecting = true;
    }
}
