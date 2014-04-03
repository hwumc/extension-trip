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
		try	{
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
        
        $this->mSmarty->assign("allowViewList", 'true');
        
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
            
            $driver = WebRequest::post( "driver" );
            $s->setDriver( $driver == 'on' ? 1 : 0 );
            
            $meal = WebRequest::post( "meal" );
            $s->setMeal( $meal == 'on' ? 1 : 0 );
            
			$s->save();
            
			$this->mHeaders[] = ( "Location: " . $cScriptPath . "/Trips/list/" . $data[ 1 ] );
		} else {
            $signup = $g->isUserSignedUp( $user->getId() );
            if( $signup !== false )
            {
                $this->mSmarty->assign( "borrowgear", $signup->getBorrowGear() );
                $this->mSmarty->assign( "actionplan", $signup->getActionPlan() );
                $this->mSmarty->assign( "confirmcheck", "checked" );
                $this->mSmarty->assign( "meal", $signup->getMeal() ? "checked" : "");
                $this->mSmarty->assign( "driver", $signup->getDriver() ? "checked" : "");
            }
            else 
            {
                $this->mSmarty->assign( "borrowgear", "" );
                $this->mSmarty->assign( "actionplan", "" );
                $this->mSmarty->assign( "confirmcheck", "" );
                $this->mSmarty->assign( "meal", "checked" );
                $this->mSmarty->assign( "driver", $user->getIsDriver() ? "checked" : "");
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
			$this->mSmarty->assign( "userisdriver", $user->getIsDriver() );
            $this->mSmarty->assign( "userisdriverexpired", $user->getDriverExpiry() !== null && DateTime::createFromFormat($cDisplayDateFormat, $user->getDriverExpiry()) < DateTime::createFromFormat($cDisplayDateFormat, $g->getEndDate() ) );
			
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
