<?php
// check for invalid entry point
if (!defined("HMS"))
    die("Invalid entry point");

class Signup extends DataObject {
   
    protected $trip;
    protected $user;
    protected $time;
        
    public static function getByTrip($id) {
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT * FROM `" . strtolower( get_called_class() ) . "` WHERE trip = :id LIMIT 1;");
		$statement->bindParam(":id", $id);

		$statement->execute();

		$resultObject = $statement->fetchObject( get_called_class() );

		if($resultObject != false)
		{
			$resultObject->isNew = false;
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
    
    function getSignupTime() {
        return DateTime::createFromFormat("Y-m-d", $this->time)->format("d/m/Y");
    }
    
    function setTrip($trip) {
        $this->trip = $trip;
    }
    
    function getTrip() {
        return $this->trip;
    }
    
    function getUser($user) {
        $this->user = $user;
    }
    
    function getUser() {
        return $this->user;
    }
    
    public function save()
    {
        global $gDatabase;

		if($this->isNew)
		{ // insert
			$statement = $gDatabase->prepare("INSERT INTO `" . strtolower( get_called_class() ) . "` VALUES (null, :user, :trip, null);");
            $statement->bindParam(":user", $this->user);
            $statement->bindParam(":trip", $this->trip);
            
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
            throw new YouShouldntBeDoingThatException();
		}
    }

    public function canDelete() {
        return true;
    }
}