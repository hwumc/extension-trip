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
}
