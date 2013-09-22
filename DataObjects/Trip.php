<?php
// check for invalid entry point
if (!defined("HMS"))
    die("Invalid entry point");

class Trip extends DataObject {
   
    protected $startdate;
    protected $enddate;
    protected $semester;
    protected $year;
    protected $week;
    protected $location;
    protected $description;
    protected $price;
    protected $spaces;
    protected $status;
    protected $signupclose;
    protected $hasmeal;
    
    public static function getArray() {
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT * FROM `trip` ORDER BY `year`, `semester`, `week`, `startdate`;");
		$statement->execute();

		$tempresult = $statement->fetchAll( PDO::FETCH_CLASS, "Trip" );
        
        $result = array();
        foreach ($tempresult as $r)
        {
            $r->isNew = false;
            $result[$r->getId() . ""] = $r;
        }
        
		return $result;
	}
    
    function setStartDate($startdate) {
        $this->startdate = DateTime::createFromFormat("d/m/Y", $startdate)->format("Y-m-d");
    }
    
    function getStartDate() {
        return DateTime::createFromFormat("Y-m-d", $this->startdate)->format("d/m/Y");
    }
    
    function setEndDate($enddate) {
        $this->enddate = DateTime::createFromFormat("d/m/Y", $enddate)->format("Y-m-d");
    }
    
    function getEndDate() {
        return DateTime::createFromFormat("Y-m-d", $this->enddate)->format("d/m/Y");
    }
    
    function setSemester($semester) {
        $this->semester = $semester;
    }
    
    function getSemester() {
        return $this->semester;
    }
    
    function setYear($year) {
        $this->year = $year;
    }
    
    function getYear() {
        return $this->year;
    }
    
    function setWeek($week) {
        $this->week = $week;
    }
    
    function getWeek() {
        return $this->week;
    }
    
    function setLocation($location) {
        $this->location = $location;
    }
    
    function getLocation() {
        return $this->location;
    }
    
    function setDescription($description) {
        $this->description = $description;
    }
    
    function getDescription() {
        return $this->description;
    }
    
    function setPrice($price) {
        $this->price = $price;
    }
    
    function getPrice() {
        return $this->price;
    }
    
    function setSpaces($spaces) {
        $this->spaces = $spaces;
    }
    
    function getSpaces() {
        return $this->spaces;
    }
    
    function setStatus($status) {
        $this->status = $status;
    }
    
    function getStatus() {
        if($this->status == TripHardStatus::OPEN)
        {
            $date = new DateTime($this->signupclose);
            $date->add(DateInterval::createFromDateString('1 day'));
            
            if($date->format('U') < time())
            {
                return TripHardStatus::CLOSED;
            }
        }
        
        return $this->status;
    }
    
    function getRealStatus() {
        return $this->status;
    }
    
    function setSignupClose($signupclose) {
        $this->signupclose = DateTime::createFromFormat("d/m/Y", $signupclose)->format("Y-m-d");
    }
    
    function getSignupClose() {
        return DateTime::createFromFormat("Y-m-d", $this->signupclose)->format("d/m/Y");
    }
     
    function getHasMeal()
    {
        return $this->hasmeal;
    }
    
    function setHasMeal( $meal )
    {
        $this->hasmeal = $meal;
    }
        
    public function save()
    {
        global $gDatabase;

		if($this->isNew)
		{ // insert
			$statement = $gDatabase->prepare("INSERT INTO `" . strtolower( get_called_class() ) . "` VALUES (null, :startdate, :enddate, :semester, :week, :year, :location, :description, :price, :spaces, :status, :signupclose, :hasmeal);");
            $statement->bindParam(":startdate", $this->startdate);
            $statement->bindParam(":enddate", $this->enddate);
            $statement->bindParam(":semester", $this->semester);
            $statement->bindParam(":year", $this->year);
            $statement->bindParam(":week", $this->week);
            $statement->bindParam(":location", $this->location);
            $statement->bindParam(":description", $this->description);
            $statement->bindParam(":price", $this->price);
            $statement->bindParam(":spaces", $this->spaces);
            $statement->bindParam(":status", $this->status);
            $statement->bindParam(":signupclose", $this->signupclose);
            $statement->bindParam(":hasmeal", $this->hasmeal);
            
			if($statement->execute())
			{
				$this->isNew = false;
				$this->id = $gDatabase->lastInsertId();
			}
			else
			{
				throw new SaveFailedException();
			}
		}
		else
		{ // update
            $statement = $gDatabase->prepare("UPDATE `" . strtolower( get_called_class() ) . "` SET startdate = :startdate, enddate = :enddate, semester = :semester, year = :year, week = :week, location = :location, description = :description, price = :price, spaces = :spaces, status = :status, signupclose = :signupclose, hasmeal = :hasmeal WHERE id = :id LIMIT 1;");
			$statement->bindParam(":id", $this->id);
            $statement->bindParam(":startdate", $this->startdate);
            $statement->bindParam(":enddate", $this->enddate);
            $statement->bindParam(":semester", $this->semester);
            $statement->bindParam(":year", $this->year);
            $statement->bindParam(":week", $this->week);
            $statement->bindParam(":location", $this->location);
            $statement->bindParam(":description", $this->description);
            $statement->bindParam(":price", $this->price);
            $statement->bindParam(":spaces", $this->spaces);
            $statement->bindParam(":status", $this->status);
            $statement->bindParam(":signupclose", $this->signupclose);
            $statement->bindParam(":hasmeal", $this->hasmeal);

			if(!$statement->execute())
			{
				throw new SaveFailedException();
			}
		}
    }

    public function canDelete() {
        if( $this->status == TripHardStatus::NEWTRIP )
            return true;
        if( $this->status == TripHardStatus::PUBLISHED )
            return true;
        if( $this->status == TripHardStatus::CANCELLED )
            return true;
        if( $this->status == TripHardStatus::COMPLETED )
            return true;
        return false;
    }

    public function isUserSignedUp( $userid )
    {
        foreach ( Signup::getByTrip( $this->getId() ) as $signup )
        {
            if( $signup->getUser() == $userid )
            {
                return $signup;   
            }
        }
        
        return false;
    }
}