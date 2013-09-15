<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageManageTrips extends PageBase
{
	public function __construct()
	{
		$this->mPageUseRight = "tripmanager-view";
		$this->mMenuGroup = "Trips";
		$this->mPageRegisteredRights = array( "tripmanager-edit", "tripmanager-create", "tripmanager-delete", "tripmanager-signup" );
		
	}

	protected function runPage()
	{
		$data = explode( "/", WebRequest::pathInfoExtension() );
		if( isset( $data[0] ) ) {
			switch( $data[0] ) {
				case "edit":
					$this->editMode( $data );
					return;
					break;
				case "delete":
					$this->deleteMode( $data );
					return;
					break;
				case "create":
					$this->createMode( $data );
					return;
					break;
				case "workflow":
					$this->workflowMode( $data );
					return;
					break;
                case "signup":
                    $this->signupMode( $data );
					return;
					break;
                case "deletesignup":
                    $this->deleteSignupMode( $data );
					return;
					break;
			}
	
		}
		
		// try to get more access than we may have.
		try	{
			self::checkAccess('tripmanager-create');
			$this->mSmarty->assign("allowCreate", 'true');
		} catch(AccessDeniedException $ex) { 
			$this->mSmarty->assign("allowCreate", 'false');
		} 
		try {
			self::checkAccess('tripmanager-delete');
			$this->mSmarty->assign("allowDelete", 'true');
		} catch(AccessDeniedException $ex) { 
			$this->mSmarty->assign("allowDelete", 'false');
		}
		try {
			self::checkAccess('tripmanager-edit');
			$this->mSmarty->assign("allowEdit", 'true');
		}
        catch(AccessDeniedException $ex) { 
			$this->mSmarty->assign("allowEdit", 'false');
		}
		try {
			self::checkAccess('tripmanager-signup');
			$this->mSmarty->assign("allowSignup", 'true');
		}
        catch(AccessDeniedException $ex) { 
			$this->mSmarty->assign("allowSignup", 'false');
		}
		
		$this->mBasePage = "managetrips/list.tpl";
		$trips = Trip::getArray();
		$this->mSmarty->assign("triplist", $trips );
	}
	
	private function editMode( $data ) {
        $allowEdit = "false";
		try {
			self::checkAccess('tripmanager-edit');
			$allowEdit = "true";
		} catch(AccessDeniedException $ex) { 
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
            
            global $cWebPath;
            $this->mScripts[] = $cWebPath . '/scripts/bootstrap-datepicker.js';
            $this->mStyles[] = $cWebPath . '/style/datepicker.css';
		}
	}
	
    private function workflowMode( $data ) {
	    self::checkAccess('tripmanager-edit');
        
		$g = Trip::getById( $data[ 1 ] );
        
		if( WebRequest::wasPosted() ) {			
            $g->setStatus( WebRequest::post( "status" ) );
			$g->save();
			
			global $cScriptPath;
			$this->mHeaders[] = ( "Location: " . $cScriptPath . "/ManageTrips" );
		} else {
			$this->mBasePage = "managetrips/tripworkflow.tpl";
            $this->mSmarty->assign( "startdate", $g->getStartDate() );
            $this->mSmarty->assign( "enddate", $g->getEndDate() );
            $this->mSmarty->assign( "semester", $g->getSemester() );
            $this->mSmarty->assign( "year", $g->getYear() );
            $this->mSmarty->assign( "week", $g->getWeek() );
            $this->mSmarty->assign( "location", $g->getLocation() );
            $this->mSmarty->assign( "signupclose", $g->getSignupClose() );
            
            
            
            $this->mSmarty->assign( "isnew", $g->getRealStatus() == TripHardStatus::NEWTRIP );
            $this->mSmarty->assign( "cannew", false );
            
            $this->mSmarty->assign( "ispublished", $g->getRealStatus() == TripHardStatus::PUBLISHED );
            $this->mSmarty->assign( "canpublished", $g->getRealStatus() == TripHardStatus::NEWTRIP );
            
            $this->mSmarty->assign( "isopen", $g->getRealStatus() == TripHardStatus::OPEN );
            $this->mSmarty->assign( "canopen", $g->getRealStatus() == TripHardStatus::NEWTRIP 
                                            || $g->getRealStatus() == TripHardStatus::PUBLISHED );
            
            $this->mSmarty->assign( "isclosed", $g->getRealStatus() == TripHardStatus::CLOSED );
            $this->mSmarty->assign( "canclosed", $g->getRealStatus() == TripHardStatus::OPEN );
            
            $this->mSmarty->assign( "iscancelled", $g->getRealStatus() == TripHardStatus::CANCELLED );
            $this->mSmarty->assign( "cancancelled", $g->getRealStatus() == TripHardStatus::OPEN 
                                            || $g->getRealStatus() == TripHardStatus::CLOSED );
            
            $this->mSmarty->assign( "iscompleted", $g->getRealStatus() == TripHardStatus::COMPLETED );
            $this->mSmarty->assign( "cancompleted", $g->getRealStatus() == TripHardStatus::CLOSED );
		}
	}
    
	private function deleteMode( $data ) {
		self::checkAccess( "tripmanager-delete" );
	
		if( WebRequest::wasPosted() ) {
			$g = Trip::getById( $data[1] );
			if( $g !== false ) {
				if( WebRequest::post( "confirm" ) == "confirmed" ) {
					$g->delete();
					$this->mSmarty->assign("content", "deleted" );
				}
			}
			
			global $cScriptPath;
			$this->mHeaders[] =  "Location: " . $cScriptPath . "/ManageTrips";
			
			
		} else {
			$this->mBasePage = "managetrips/tripdelete.tpl";
		}
	}
	
	private function createMode( $data ) {
		self::checkAccess( "tripmanager-create" );
		$this->mSmarty->assign("allowEdit", 'true');
	
		if( WebRequest::wasPosted() ) {
			$g = new Trip();
            $g->setStatus( TripHardStatus::NEWTRIP );
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
            
			$this->mHeaders[] =  "Location: " . $cScriptPath . "/ManageTrips";
		} else {
		
			$this->mBasePage = "managetrips/tripcreate.tpl";
            $this->mSmarty->assign( "startdate", "" );
            $this->mSmarty->assign( "enddate", "" );
            $this->mSmarty->assign( "semester", "" );
            $this->mSmarty->assign( "year", "" );
            $this->mSmarty->assign( "week", "" );
            $this->mSmarty->assign( "location", "" );
            $this->mSmarty->assign( "description", "" );
            $this->mSmarty->assign( "price", "" );
            $this->mSmarty->assign( "spaces", "" );
            $this->mSmarty->assign( "signupclose", "" );
            
            global $cWebPath;
            $this->mScripts[] = $cWebPath . '/scripts/bootstrap-datepicker.js';
            $this->mStyles[] = $cWebPath . '/style/datepicker.css';
		}
	}

    private function signupMode( $data ) {
	    self::checkAccess('tripmanager-signup');
        
		$g = Trip::getById( $data[ 1 ] );
        $signups = Signup::getByTrip( $g->getId() );
        
		$this->mBasePage = "managetrips/tripsignup.tpl";
        $this->mSmarty->assign( "trip", $g );
        $this->mSmarty->assign( "signups", $signups );
	}
    
    
	private function deleteSignupMode( $data ) {
		self::checkAccess( "tripmanager-signup" );
        
		if( WebRequest::wasPosted() ) {
			$g = Signup::getById( $data[1] );
			if( $g !== false ) {
				if( WebRequest::post( "confirm" ) == "confirmed" ) {
					$g->delete();
					$this->mSmarty->assign("content", "deleted" );
				}
			}
			
			global $cScriptPath;
			$this->mHeaders[] =  "Location: " . $cScriptPath . "/ManageTrips";
			
			
		} else {
			$this->mBasePage = "managetrips/tripdeletesignup.tpl";
		}
	}

}
