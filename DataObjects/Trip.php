<?php
// check for invalid entry point
if (!defined("HMS"))
    die("Invalid entry point");

class Trip extends DataObject
{
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
    protected $signupopen;
    protected $hasmeal;
    protected $driverplaces;

    public static function getArray()
    {
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

    function setStartDate($startdate)
    {
        global $cDisplayDateFormat;
        $this->startdate = DateTime::createFromFormat($cDisplayDateFormat, $startdate)->format("Y-m-d");
    }

    function getStartDate()
    {
        global $cDisplayDateFormat;
        return DateTime::createFromFormat("Y-m-d", $this->startdate)->format($cDisplayDateFormat);
    }

    function setEndDate($enddate)
    {
        global $cDisplayDateFormat;
        $this->enddate = DateTime::createFromFormat($cDisplayDateFormat, $enddate)->format("Y-m-d");
    }

    function getEndDate()
    {
        global $cDisplayDateFormat;
        return DateTime::createFromFormat("Y-m-d", $this->enddate)->format($cDisplayDateFormat);
    }

    function setSemester($semester)
    {
        $this->semester = $semester;
    }

    function getSemester()
    {
        return $this->semester;
    }

    function setYear($year)
    {
        $this->year = $year;
    }

    function getYear()
    {
        return $this->year;
    }

    function getYearDisplay()
    {
        return $this->year . "/" . ($this->year + 1);
    }

    function setWeek($week)
    {
        $this->week = $week;
    }

    function getWeek()
    {
        return $this->week;
    }

    function setLocation($location)
    {
        $this->location = $location;
    }

    function getLocation()
    {
        return $this->location;
    }

    function setDescription($description)
    {
        $this->description = $description;
    }

    function getDescription()
    {
        return $this->description;
    }

    function setPrice($price)
    {
        $this->price = $price;
    }

    function getPrice()
    {
        return $this->price;
    }

    function setSpaces($spaces)
    {
        $this->spaces = $spaces;
    }

    function getSpaces()
    {
        return $this->spaces;
    }

    function getDriverPlaces()
    {
        return $this->driverplaces;
    }

    function setDriverPlaces($driverplaces)
    {
        $this->driverplaces = $driverplaces;
    }

    function setStatus($status)
    {
        $this->status = $status;
    }

    function getStatus()
    {
        if($this->status == TripHardStatus::OPEN)
        {
            $closedate = new DateTime($this->signupclose);

            if($closedate->format('U') < time())
            {
                return TripHardStatus::CLOSED;
            }

            $opendate = new DateTime($this->signupopen);

            if($opendate->format('U') > time())
            {
                return TripHardStatus::PUBLISHED;
            }

        }

        return $this->status;
    }

    function getRealStatus()
    {
        return $this->status;
    }

    function setSignupClose($signupclose)
    {
        global $cDisplayDateTimeFormatNoTz;
        $rawdate = DateTime::createFromFormat($cDisplayDateTimeFormatNoTz, $signupclose);
        if($rawdate == false)
        {
            global $cDisplayDateTimeFormat;
            $rawdate = DateTime::createFromFormat($cDisplayDateTimeFormat, $signupclose);
        }

        $this->signupclose = $rawdate->format("Y-m-d H:i:s");
    }

    function getSignupClose()
    {
        global $cDisplayDateTimeFormat;
        return DateTime::createFromFormat("Y-m-d H:i:s", $this->signupclose)->format($cDisplayDateTimeFormat);
    }

    function setSignupOpen($signupopen)
    {
        global $cDisplayDateTimeFormatNoTz;
        $date = DateTime::createFromFormat($cDisplayDateTimeFormatNoTz, $signupopen);
        if($date == false)
        {
            global $cDisplayDateTimeFormat;
            $date = DateTime::createFromFormat($cDisplayDateTimeFormat, $signupopen);
        }

        if($date == false)
        {
            $this->signupopen = null;
        }
        else
        {
            $this->signupopen = $date->format("Y-m-d H:i:s");
        }
    }

    function getSignupOpen()
    {
        global $cDisplayDateTimeFormat;
        $date = DateTime::createFromFormat("Y-m-d H:i:s", $this->signupopen);
        if($date == false)
        {
            return "";
        }

        return $date->format($cDisplayDateTimeFormat);
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
            $statement = $gDatabase->prepare("INSERT INTO `" . strtolower( get_called_class() ) . "` VALUES (null, :startdate, :enddate, :semester, :week, :year, :location, :description, :price, :spaces, :status, :signupclose, :hasmeal, :driverplaces, :signupopen);");
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
            $statement->bindParam(":driverplaces", $this->driverplaces);
            $statement->bindParam(":signupopen", $this->signupopen);

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
            $statement = $gDatabase->prepare("UPDATE `" . strtolower( get_called_class() ) . "` SET startdate = :startdate, enddate = :enddate, semester = :semester, year = :year, week = :week, location = :location, description = :description, price = :price, spaces = :spaces, status = :status, signupclose = :signupclose, hasmeal = :hasmeal, driverplaces = :driverplaces, signupopen = :signupopen WHERE id = :id LIMIT 1;");
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
            $statement->bindParam(":driverplaces", $this->driverplaces);
            $statement->bindParam(":signupopen", $this->signupopen);

            if(!$statement->execute())
            {
                throw new SaveFailedException();
            }
        }
    }

    public function canDelete()
    {
        if( $this->status == TripHardStatus::NEWTRIP )
        {
            return true;
        }

        if( $this->status == TripHardStatus::PUBLISHED )
        {
            return true;
        }

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