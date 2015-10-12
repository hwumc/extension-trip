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
    protected $handlingcharge;

    private $paymentMethodCache;
    
    /**
     * Summary of getBySignup
     * @param Signup $signup 
     * @return Payment
     */
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
    
    /**
     * Summary of getByReference
     * @param string $reference 
     * @return Payment
     */
    public static function getByReference($reference)
    {
        global $gDatabase;
        $statement = $gDatabase->prepare("SELECT * FROM `" . strtolower( get_called_class() ) . "` WHERE reference = :id;");
        $statement->bindValue(":id", $reference);

        $statement->execute();

        $resultObject = $statement->fetchObject( get_called_class() );
        
        if($resultObject === false)
        {
            $nullPayment = new NullPaymentMethod();
            $resultObject = $nullPayment->createPayment($signup, 0);
            $resultObject->setReference($reference);
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
        if($this->paymentMethodCache == null)
        {
            if($this->method == null)
            {
                $this->paymentMethodCache = new NullPaymentMethod();
            }
            else
            {
                $paymentMethod = TripPaymentMethod::getById($this->method)->getMethod();
                $this->paymentMethodCache = new $paymentMethod();
            }
        }
        
        return $this->paymentMethodCache;
    }

    /**
     * This one's a bitchy one to figure out, so we force the caller to figure it out and pass the object.
     * @param TripPaymentMethod $method 
     */
    public function setMethod(TripPaymentMethod $method = null)
    {
        if($method == null)
        {
            $this->method = null;
        }
        else
        {
            $this->method = $method->getId();
        }

        // clear the cache
        $this->paymentMethodCache = null;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * signup id
     * @return int
     */
    public function getSignup()
    {
        return $this->signup;
    }

    /**
     * Summary of setSignup
     * @param int $signup signup od
     */
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

    public function getHandlingCharge()
    {
        return $this->handlingcharge === null ? 0 : $this->handlingcharge;
    }

    public function setHandlingCharge($amount)
    {
        $this->handlingcharge = $amount;
    }

    public function getTotal()
    {
        return $this->amount + $this->handlingcharge;
    }

    public function save()
    {
        global $gDatabase;
        
        if($this->isNew)
        {
            // insert
            $statement = $gDatabase->prepare("INSERT INTO `payment` (method, status, signup, reference, amount, handlingcharge) VALUES (:method, :status, :signup, :reference, :amount, :handlingcharge);");
            $statement->bindParam(":method", $this->method);
            $statement->bindParam(":status", $this->status);
            $statement->bindParam(":signup", $this->signup);
            $statement->bindParam(":reference", $this->reference);
            $statement->bindParam(":amount", $this->amount);
            $statement->bindParam(":handlingcharge", $this->handlingcharge);
            
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
            $statement = $gDatabase->prepare("UPDATE `" . strtolower( get_called_class() ) . "` SET status = :status, reference = :reference, method = :method, handlingcharge = :handlingcharge WHERE id = :id;");
            $statement->bindParam(":id", $this->id);
            $statement->bindParam(":status", $this->status);
            $statement->bindParam(":method", $this->method);
            $statement->bindParam(":reference", $this->reference);
            $statement->bindParam(":handlingcharge", $this->handlingcharge);
            
            if(!$statement->execute())
            {
                throw new SaveFailedException();
            }
        }
    }
    
    public function canDelete()
    {
        return PaymentStatus::isDeletable($this->status);
    }
}
