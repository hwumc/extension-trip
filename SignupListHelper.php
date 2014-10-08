<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class SignupListHelper
{
    private $trip;
    private $signups = null;

    public function __construct($trip)
    {
        $this->trip = $trip;
    }

    public function getRealSignups()
    {
        if($this->signups === null)
        {
            $this->signups = Signup::getByTrip($this->trip->getId());
        }

        return $this->signups;
    }

    public function getPrioritisedSignups()
    {
        global $cDisplayDateFormat;
        $signups = $this->getRealSignups();

        $driversrequired = $this->trip->getDriverPlaces();

        if($driversrequired == 0)
        {
            return $signups;
        }

        $drivers = array();
        $nondrivers = array();
        foreach( $signups as $value)
        {
            if(count($drivers) < $driversrequired)
            {
                if($value->getUserObject()->isDriver() && $value->getDriver())
                {
                    $driverExpiry = DateTime::createFromFormat($cDisplayDateFormat, $value->getUserObject()->getDriverExpiry());
                    if($driverExpiry >= DateTime::createFromFormat($cDisplayDateFormat, $this->trip->getEndDate()))
                    {
                        $drivers[] = $value;
                        $value->driverpos = true;

                        continue;
                    }
                }
            }

            $nondrivers[] = $value;
        }

        if(count($drivers) < $driversrequired)
        {
            $drivers = array_pad($drivers, $driversrequired, Signup::getAnonymous($this->trip->getId()));
        }

        foreach ($nondrivers as $value)
        {
            $drivers[] = $value;
        }


        return $drivers;
    }
    
    public function getSignupStatus(User $user)
    {
        $driversrequired = $this->trip->getDriverPlaces();
        $spaces = $this->trip->getSpaces();
        
        $prioritisedSignups = $this->getPrioritisedSignups();
        
        foreach ($prioritisedSignups as $signup)
        {
            if($driversrequired > 0)
            {
                $type = SignupStatus::DRIVER;
                $driversrequired--;
                $spaces--;
            } 
            elseif ($spaces > 0) 
            {
                $spaces--;
                $type = SignupStatus::NORMAL;
            }
            else
            {
                $type = SignupStatus::WAITINGLIST;   
            }
            
            if($signup->getUser() == $user->getId())
            {
                return $type;
            }
        }
        
        return SignupStatus::MISSING;
    }
}
