<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageTrips extends PageBase
{
    public function __construct()
    {
        $this->mMenuGroup = "Trips";
        $this->mPageRegisteredRights = array( "trips-signup", "trips-list" );
    }

    protected function runPage()
    {
        $data = explode( "/", WebRequest::pathInfoExtension() );
        if( isset( $data[0] ) ) {
            switch( $data[0] ) {
                case "signup":
                    $this->signupMode( $data );
                    return;
                    break;
                case "list":
                    $this->listMode( $data );
                    return;
                    break;
            }
        }

        // try to get more access than we may have.
        try {
            self::checkAccess('trips-signup');
            $this->mSmarty->assign("allowSignup", 'true');
            $this->mSmarty->assign("allowViewList", 'true');
        }
        catch(AccessDeniedException $ex) {
            $this->mSmarty->assign("allowSignup", 'false');
            $this->mSmarty->assign("allowViewList", 'false');
        }

        $this->mBasePage = "trips/list.tpl";
        $trips = Trip::getArray();
        $filteredtrips = array();
        foreach ($trips as $t)
        {
            if($t->getStatus() != TripHardStatus::NEWTRIP && $t->getStatus() != TripHardStatus::ARCHIVED )
            {
                $filteredtrips[] = $t;
            }
        }

        $this->mSmarty->assign("triplist", $filteredtrips );
    }

    protected function signupMode( $data )
    {
        global $cDisplayDateFormat;
        global $cScriptPath;
        self::checkAccess('trips-signup');

        global $cWebPath;
        $this->mStyles[] = $cWebPath . '/style/bootstrap-datetimepicker.min.css';
        $this->mScripts[] = $cWebPath . '/scripts/bootstrap-datetimepicker.min.js';
        
        $this->mSmarty->assign("allowViewList", 'true');

        $g = Trip::getById( $data[ 1 ] );
        $user = User::getLoggedIn();

        $allowedPaymentMethods = array();
        foreach(TripPaymentMethod::getByTrip($g) as $tpm)
        {
            if($tpm->getVisible() == 1) 
            {
                $methodName = $tpm->getMethod();
                $allowedPaymentMethods[$methodName] = array(
                    'check'  => false, 
                    'method' => new $methodName(),
                    'name'   => $methodName
                );
            }
        }

        if($g->getStatus() != "open" )
        {
            throw new AccessDeniedException();
        }

        if( WebRequest::wasPosted() ) {
            global $gDatabase;
            
            $s = $g->isUserSignedUp( $user->getId() );

            $creatingNew = false;

            if( $s === false )
            {
                $creatingNew = true;
                $s = new Signup();
                $s->setTrip($g->getId());
                $s->setUser($user->getId());
            }

            $s->setActionPlan( WebRequest::post( "actionplan" ) );
            $s->setBorrowGear( WebRequest::post( "borrowgear" ) );
            
            if($g->getShowLeaveFrom())
            {
                $s->setLeaveFrom( WebRequest::post( "leavefrom" ) );
            }
            
            $driver = WebRequest::post( "driver" );
            $s->setDriver( $driver == 'on' ? 1 : 0 );

            $meal = WebRequest::post( "meal" );
            $s->setMeal( $meal == 'on' ? 1 : 0 );
            
            $s->save();

            $requestedPaymentMethod = WebRequest::post( "paymentMethod" );

            if($requestedPaymentMethod === false)
            {
                if(count($allowedPaymentMethods) == 1)
                {
                    $m = current($allowedPaymentMethods);
                    $requestedPaymentMethod = $m['name'];
                }
                else
                {
                    $requestedPaymentMethod = "NullPaymentMethod";
                }
            }

            $paymentRequestedRedirect = false;

            if($creatingNew)
            {
                if(array_key_exists($requestedPaymentMethod, $allowedPaymentMethods))
                {
                    $paymentMethod = new $requestedPaymentMethod();
                }
                else
                {
                    $paymentMethod = new ManualPaymentMethod();
                }
                
                $payment = $paymentMethod->createPayment($s, $g->getPrice());
                $paymentRequestedRedirect = $paymentMethod->getAuthorisation($payment);
            }
            else
            {
                $payment = Payment::getBySignup($s);
                if(get_class($payment->getMethodObject()) != $requestedPaymentMethod
                    || $payment->getStatus() == PaymentStatus::REFUNDED
                    || $payment->getStatus() == PaymentStatus::CANCELLED
                    || $payment->getStatus() == PaymentStatus::NOT_PAID
                    )
                {
                    // OK, the user wants to change their method of payment (or it's been refunded/cancelled).
                    // This *should* be possible if they've not paid.
                    if($payment->canDelete())
                    {
                        $paymentMethod = new $requestedPaymentMethod();
                        $payment->delete();

                        $payment = $paymentMethod->createPayment($s, $g->getPrice());
                        $paymentRequestedRedirect = $paymentMethod->getAuthorisation($payment);
                    }
                    else
                    {
                        Session::appendError("payment-method-change-not-allowed");
                    }
                }
            }
            
            $helper = new SignupListHelper($g);
            $status = $helper->getSignupStatus($user);
            
            if($status == SignupStatus::DRIVER)
            {
                Session::appendSuccess("Signup-success-normal");   
                Session::appendInfo("Signup-success-driver");   
            }
            
            if($status == SignupStatus::WAITINGLIST)
            {
                Session::appendWarning("Signup-success-waitinglist");   
            }
            
            if($status == SignupStatus::NORMAL)
            {
                Session::appendSuccess("Signup-success-normal");   
            }

            if($paymentRequestedRedirect !== false)
            {
                $this->mHeaders[] = ( "Location: " . $paymentRequestedRedirect );
            }
            else 
            {
                $this->mHeaders[] = ( "Location: " . $cScriptPath . "/Trips/list/" . $data[ 1 ] );
            }
            
            $this->mIsRedirecting = true;
        } else {
            $signup = $g->isUserSignedUp( $user->getId() );
            if( $signup !== false )
            {
                $this->mSmarty->assign( "borrowgear", $signup->getBorrowGear() );
                $this->mSmarty->assign( "actionplan", $signup->getActionPlan() );
                $this->mSmarty->assign( "confirmcheck", "checked disabled" );
                $this->mSmarty->assign( "legalagreementcheck", "checked disabled" );
                $this->mSmarty->assign( "meal", $signup->getMeal() ? "checked" : "");
                $this->mSmarty->assign( "driver", $signup->getDriver() ? "checked" : "");
                $this->mSmarty->assign( "leavefrom", $signup->getLeaveFrom() );

                $payment = Payment::getBySignup($signup);
                $method = get_class($payment->getMethodObject());
                if($method != "NullPaymentMethod")
                {
                    if(!array_key_exists($method, $allowedPaymentMethods))
                    {
                        $allowedPaymentMethods[$method] = array(
                            'name' => $method,
                            'method' => new $method(),
                            'check' => false,
                        );
                    }

                    $allowedPaymentMethods[$method]['check'] = true;
                }

                $this->mSmarty->assign( "showPaymentMethods", ( count($allowedPaymentMethods) > 1 && $payment->canDelete() ) );
            }
            else
            {
                $this->mSmarty->assign( "borrowgear", "" );
                $this->mSmarty->assign( "actionplan", "" );
                $this->mSmarty->assign( "confirmcheck", "" );
                $this->mSmarty->assign( "legalagreementcheck", "" );
                $this->mSmarty->assign( "meal", "checked" );
                $this->mSmarty->assign( "driver", $user->getIsDriver() ? "checked" : "");
                $this->mSmarty->assign( "leavefrom", "");
                $this->mSmarty->assign( "showPaymentMethods", count($allowedPaymentMethods) > 1 );
            }

            $this->mBasePage = "trips/tripsignup.tpl";
            $this->mSmarty->assign( "trip", $g );
            $this->mSmarty->assign( "hasmeal", $g->getHasMeal() );
            
            $this->mSmarty->assign( "showleavefrom", $g->getShowLeaveFrom() );

            $this->mSmarty->assign( "realname", $user->getFullName() );
            $this->mSmarty->assign( "mobile", $user->getMobile() );
            $this->mSmarty->assign( "experience", $user->getExperience() );
            $this->mSmarty->assign( "medicalcheck", ($user->getMedical() == "" ? "" : 'checked="true"') );
            $this->mSmarty->assign( "medical", $user->getMedical() );
            $this->mSmarty->assign( "contactname", $user->getEmergencyContact() );
            $this->mSmarty->assign( "contactphone", $user->getEmergencyContactPhone() );
            $this->mSmarty->assign( "userisdriver", $user->getIsDriver() );
            $this->mSmarty->assign( "userisdriverexpired", $user->getDriverExpiry() !== null && DateTime::createFromFormat($cDisplayDateFormat, $user->getDriverExpiry()) < DateTime::createFromFormat($cDisplayDateFormat, $g->getEndDate() ) );

            $this->mSmarty->assign( "allowedPaymentMethods", $allowedPaymentMethods );
       }
    }

    protected function listMode( $data )
    {
        self::checkAccess('trips-list');

        $this->mSmarty->assign("allowViewList", 'false');

        $g = Trip::getById( $data[ 1 ] );
        $this->mBasePage = "trips/signuplist.tpl";

        $helper = new SignupListHelper($g);
        $signups = $helper->getPrioritisedSignups();

        $this->mSmarty->assign( "trip", $g );
        $this->mSmarty->assign( "signups", $signups );
    }
}
