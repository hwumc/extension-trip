<?php
// check for invalid entry point
if (!defined("HMS"))
    die("Invalid entry point");

class Payment extends DataObject 
{
    protected $method;
    protected $status;
    protected $signup;
    protected $reference;
    protected $amount;
    
    public static function getBySignup(Signup $signup)
    {
        global $gDatabase;
        $statement = $gDatabase->prepare("SELECT * FROM `" . strtolower( get_called_class() ) . "` WHERE signup = :id;");
        $statement->bindValue(":id", $signup->getId());

        $statement->execute();

        $resultObject = $statement->fetchObject( get_called_class() );
        
        if($resultObject === false)
        {
            $nullPayment = new NullPaymentMethod();
            $resultObject = $nullPayment->createPayment($signup, 0);
        }
        
        $resultObject->isNew = false;

        return $resultObject;
    }
    
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Summary of getMethodObject
     * @return IPaymentMethod
     */
    public function getMethodObject()
    {
        return new $this->method();
    }

    public function setMethod($method)
    {
        if($method == false)
        {
            global $cTripModuleDefaultPaymentMethod;
            
            if(!isset($cTripModuleDefaultPaymentMethod))
            {
                $cTripModuleDefaultPaymentMethod = "ManualPaymentMethod";    
            }
            
            $method = $cTripModuleDefaultPaymentMethod;
        }
        
        $this->method = $method;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getSignup()
    {
        return $this->signup;
    }

    public function setSignup($signup)
    {
        $this->signup = $signup;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function save()
    {
        global $gDatabase;
        
        if($this->isNew)
        {
            // insert
            $statement = $gDatabase->prepare("INSERT INTO `payment` (method, status, signup, reference, amount) VALUES (:method, :status, :signup, :reference, :amount);");
            $statement->bindParam(":method", $this->method);
            $statement->bindParam(":status", $this->status);
            $statement->bindParam(":signup", $this->signup);
            $statement->bindParam(":reference", $this->reference);
            $statement->bindParam(":amount", $this->amount);
            
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
            $statement = $gDatabase->prepare("UPDATE `" . strtolower( get_called_class() ) . "` SET status = :status, reference = :reference WHERE id = :id;");
            $statement->bindParam(":id", $this->id);
            $statement->bindParam(":status", $this->status);
            $statement->bindParam(":reference", $this->reference);
            
            if(!$statement->execute())
            {
                throw new SaveFailedException();
            }
        }
    }
    
    public function canDelete()
    {
        return true;   
    }
}
