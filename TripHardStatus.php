<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class TripHardStatus
{
    const NEWTRIP = "new";
    const PUBLISHED = "published";
    const OPEN = "open";
    const CLOSED = "closed";
    const CANCELLED = "cancelled";
    const COMPLETED = "completed";
}
