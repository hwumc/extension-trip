<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageTrips extends PageBase
{
	public function __construct()
	{
        $this->mPageUseRight = "trips-view";
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
		}
        catch(AccessDeniedException $ex) { 
			$this->mSmarty->assign("allowSignup", 'false');
		} 
        
		$this->mBasePage = "trips/list.tpl";
		$trips = Trip::getArray();
        $filteredtrips = array();
        foreach ($trips as $t)
        {
            if($t->getStatus() != TripHardStatus::NEWTRIP)
            {
                $filteredtrips[] = $t;
            }
        }
        
		$this->mSmarty->assign("triplist", $filteredtrips );
	}

    protected function signupMode( $data )
    {
		self::checkAccess('trips-signup');
        
		$g = Trip::getById( $data[ 1 ] );
        $user = User::getLoggedIn();
        
		if( WebRequest::wasPosted() ) {
			$s = new Signup();
            $s->setTrip($g->getId());
            $s->setUser($user->getId());
			$s->save();
            
            
			
			global $cScriptPath;
			$this->mHeaders[] = ( "Location: " . $cScriptPath . "/Trips/list/" . $data[ 1 ] );
		} else {
			$this->mBasePage = "trips/tripsignup.tpl";
            $this->mSmarty->assign( "startdate", $g->getStartDate() );
            $this->mSmarty->assign( "enddate", $g->getEndDate() );
            $this->mSmarty->assign( "semester", $g->getSemester() );
            $this->mSmarty->assign( "year", $g->getYear() );
            $this->mSmarty->assign( "week", $g->getWeek() );
            $this->mSmarty->assign( "location", $g->getLocation() );
            $this->mSmarty->assign( "description", $g->getDescription() );
            $this->mSmarty->assign( "price", $g->getPrice() );
            $this->mSmarty->assign( "spaces", $g->getSpaces() );
            $this->mSmarty->assign( "signupclose", $g->getSignupClose() );
            
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
        
        $this->mBasePage = "trips/signuplist.tpl";
    }
}
