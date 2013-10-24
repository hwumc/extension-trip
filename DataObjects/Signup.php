<?php
// check for invalid entry point
if (!defined("HMS"))
    die("Invalid entry point");

class Signup extends DataObject {
   
    protected $trip;
    protected $user;
    protected $time;
    protected $borrowgear;
    protected $actionplan;
    protected $meal;
        
    // this is *NOT* a stored variable.
    public $driverpos;
    
    public static function getAnonymous($tripid)
    {
        $s = new Signup();
        $s->user = 0;
        $s->trip = $tripid;
        $s->time ="1970-01-01 00:00:00";
        $s->borrowgear = "";
        $s->actionplan = "";
        $s->meal = false;
        $s->driverpos = true;
        $s->isNew = false;
        
        return $s;
    }
    
    public static function getByTrip($id) {
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT * FROM `" . strtolower( get_called_class() ) . "` WHERE trip = :id;");
		$statement->bindParam(":id", $id);

		$statement->execute();

		$resultObject = $statement->fetchAll( PDO::FETCH_CLASS , get_called_class() );

        foreach ($resultObject as $r)
        {
            $r->isNew = false;
        }
        
		return $resultObject;
	}    
    
    public static function getByUser($id) {
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT * FROM `" . strtolower( get_called_class() ) . "` WHERE user = :id LIMIT 1;");
		$statement->bindParam(":id", $id);

		$statement->execute();

		$resultObject = $statement->fetchObject( get_called_class() );

		if($resultObject != false)
		{
			$resultObject->isNew = false;
		}

		return $resultObject;
	}
    
    function getTime() {
        $fmt = DateTime::createFromFormat("Y-m-d H:i:s", $this->time);
        
        return $fmt->format("H:i:s d/m/Y");
    }
    
    function setTrip($trip) {
        $this->trip = $trip;
    }
    
    function getTrip() {
        return $this->trip;
    }
    
    function getTripObject()
    {
        return Trip::getById( $this->trip );   
    }
    
    function setUser($user) {
        $this->user = $user;
    }
    
    function getUser() {
        return $this->user;
    }
    
    function getUserObject()
    {
        $user = User::getById( $this->user );
        if($user === false)
        {
            $user = new AnonymousUser();   
        }
        return $user;   
    }
    
    function getBorrowGear()
    {
        return $this->borrowgear;   
    }
    
    function setBorrowGear( $gear )
    {
        $this->borrowgear = $gear;   
    }
    
    function getActionPlan()
    {
        return $this->actionplan;   
    }
    
    function setActionPlan( $plan )
    {
        $this->actionplan = $plan;   
    }
    
    function getMeal()
    {
        return $this->meal;
    }
    
    function getMealText()
    {
        return $this->meal == 1 ? "Yes" : "No";
    }
    
    function setMeal( $meal )
    {
        $this->meal = $meal;   
    }
    
    public function save()
    {
        global $gDatabase;

		if($this->isNew)
		{ // insert
			$statement = $gDatabase->prepare("INSERT INTO `" . strtolower( get_called_class() ) . "` VALUES (null, :user, :trip, null, :gear, :plan, :meal);");
            $statement->bindParam(":user", $this->user);
            $statement->bindParam(":trip", $this->trip);
            $statement->bindParam(":gear", $this->borrowgear);
            $statement->bindParam(":plan", $this->actionplan);
            $statement->bindParam(":meal", $this->meal);
            
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
            $statement = $gDatabase->prepare("UPDATE `" . strtolower( get_called_class() ) . "` SET borrowgear = :gear, actionplan = :plan, meal = :meal WHERE id = :id;");
            $statement->bindParam(":id", $this->id);
            $statement->bindParam(":gear", $this->borrowgear);
            $statement->bindParam(":plan", $this->actionplan);
            $statement->bindParam(":meal", $this->meal);
            
			if(!$statement->execute())
			{
				throw new SaveFailedException();
			}
		}
    }

    public function canDelete() {
        return true;
    }
}