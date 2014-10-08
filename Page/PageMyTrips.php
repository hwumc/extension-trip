<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageMyTrips extends PageBase
{
    public function __construct()
    {
        $this->mIsSpecialPage = true;
    }

    protected function runPage()
    {
        $this->mSmarty->assign("allowSignup", 'true');
        $this->mSmarty->assign("allowViewList", 'true');

        $this->mBasePage = "mytrips/list.tpl";
        $signups = Signup::getByUser(Session::getLoggedInUser());

        $this->mSmarty->assign("triplist", $signups );
    }

    protected function signupMode( $data )
    {
        global $cScriptPath;
        self::checkAccess('trips-signup');

        $g = Trip::getById( $data[ 1 ] );
        $user = User::getLoggedIn();

        if($g->getStatus() != "open" )
        {
            throw new AccessDeniedException();
        }

        if( WebRequest::wasPosted() ) {
            $s = $g->isUserSignedUp( $user->getId() );

            if( $s === false )
            {
                $s = new Signup();
                $s->setTrip($g->getId());
                $s->setUser($user->getId());
            }

            $s->setActionPlan( WebRequest::post( "actionplan" ) );
            $s->setBorrowGear( WebRequest::post( "borrowgear" ) );

            $meal = WebRequest::post( "meal" );
            $s->setMeal( $meal == 'on' ? 1 : 0 );

            $s->save();

            $this->mHeaders[] = ( "Location: " . $cScriptPath . "/Trips/list/" . $data[ 1 ] );
            $this->mIsRedirecting = true;
        } else {
            $signup = $g->isUserSignedUp( $user->getId() );
            if( $signup !== false )
            {
                $this->mSmarty->assign( "borrowgear", $signup->getBorrowGear() );
                $this->mSmarty->assign( "actionplan", $signup->getActionPlan() );
                $this->mSmarty->assign( "confirmcheck", "checked" );
                $this->mSmarty->assign( "meal", $signup->getMeal() ? "checked" : "");
            }
            else
            {
                $this->mSmarty->assign( "borrowgear", "" );
                $this->mSmarty->assign( "actionplan", "" );
                $this->mSmarty->assign( "confirmcheck", "" );
                $this->mSmarty->assign( "meal", "checked" );
            }

            $this->mBasePage = "trips/tripsignup.tpl";
            $this->mSmarty->assign( "trip", $g );
            $this->mSmarty->assign( "hasmeal", $g->getHasMeal() );

            $this->mSmarty->assign( "realname", $user->getFullName() );
            $this->mSmarty->assign( "mobile", $user->getMobile() );
            $this->mSmarty->assign( "experience", $user->getExperience() );
            $this->mSmarty->assign( "medicalcheck", ($user->getMedical() == "" ? "" : 'checked="true"') );
            $this->mSmarty->assign( "medical", $user->getMedical() );
            $this->mSmarty->assign( "contactname", $user->getEmergencyContact() );
            $this->mSmarty->assign( "contactphone", $user->getEmergencyContactPhone() );

       }
    }

    protected function listMode( $data )
    {
        self::checkAccess('trips-list');

        $g = Trip::getById( $data[ 1 ] );
        $this->mBasePage = "trips/signuplist.tpl";

        $helper = new SignupListHelper($g);
        $signups = $helper->getPrioritisedSignups();

        $this->mSmarty->assign( "trip", $g );
        $this->mSmarty->assign( "signups", $signups );
    }
}
