<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageTrips extends PageBase
{
	public function __construct()
	{
		$this->mPageUseRight = "trips-view";
		$this->mMenuGroup = "Trips";
        $this->mPageRegisteredRights = array( "trips-signup" );
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
        $allowEdit = "false";
		try {
			self::checkAccess('tripmanager-edit');
			$allowEdit = "true";
		}
        catch(AccessDeniedException $ex) { 
            $allowEdit = "false";
		}
        
		$g = Trip::getById( $data[ 1 ] );

        $this->mSmarty->assign("allowEdit", $allowEdit);
        
		if( WebRequest::wasPosted() ) {
			if( ! $allowEdit ) throw new AccessDeniedException();
			
            $g->setLocation( WebRequest::post( "location" ) );
            $g->setDescription( WebRequest::post( "description" ) );
            $g->setYear( WebRequest::post( "year" ) );
            $g->setSemester( WebRequest::post( "semester" ) );
            $g->setWeek( WebRequest::post( "week" ) );
            $g->setStartDate( WebRequest::post( "startdate" ) );
            $g->setEndDate( WebRequest::post( "enddate" ) );
            $g->setPrice( WebRequest::post( "price" ) );
            $g->setSpaces( WebRequest::post( "spaces" ) );
            $g->setSignupClose( WebRequest::post( "signupclose" ) );
			$g->save();
			
			global $cScriptPath;
			$this->mHeaders[] = ( "Location: " . $cScriptPath . "/ManageTrips" );
		} else {
			$this->mBasePage = "managetrips/tripcreate.tpl";
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
            
       }
    }
}
