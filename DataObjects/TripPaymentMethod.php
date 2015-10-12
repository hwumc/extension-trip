<?php

/**
 * TripPaymentMethod short summary.
 *
 * TripPaymentMethod description.
 *
 * @version 1.0
 * @author stwalkerster
 */
class TripPaymentMethod extends DataObject
{
    protected $trip;
    protected $method;
    protected $visible;

    public function __construct()
    {
     //   $this->visible = 0;
    }

    public static function getByTrip(Trip $trip)
    {
        global $gDatabase;
        $statement = $gDatabase->prepare("SELECT * FROM `" . strtolower( get_called_class() ) . "` WHERE trip = :id;");
        $statement->bindValue(":id", $trip->getId());

        $statement->execute();

        $resultObject = $statement->fetchAll( PDO::FETCH_CLASS , get_called_class() );

        foreach ($resultObject as $r)
        {
            $r->isNew = false;
        }

        return $resultObject;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getVisible()
    {
        return $this->visible;
    }

    public function setVisible($visible)
    {
        $this->visible = $visible;
    }

    public function getTripId()
    {
        return $this->trip;
    }

    public function setTripId($id)
    {
        $this->trip = $id;
    }

    public function save()
    {
        global $gDatabase;
        
        if($this->isNew)
        {
            // insert
            $statement = $gDatabase->prepare("INSERT INTO `trippaymentmethod` (method, trip, visible) VALUES (:method, :trip, :visible);");
            $statement->bindParam(":method", $this->method);
            $statement->bindParam(":trip", $this->trip);
            $statement->bindParam(":visible", $this->visible);
            
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
        {
            //update
            $statement = $gDatabase->prepare("UPDATE `" . strtolower( get_called_class() ) . "` SET method = :method, trip = :trip, visible = :visible WHERE id = :id;");
            $statement->bindParam(":id", $this->id);
            $statement->bindParam(":trip", $this->trip);
            $statement->bindParam(":method", $this->method);
            $statement->bindParam(":visible", $this->visible);
            
            if(!$statement->execute())
            {
                throw new SaveFailedException();
            }
        }
    }
}
