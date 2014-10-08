<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class SignupStatus
{
    const DRIVER = "DRIVER";
    const NORMAL = "NORMAL";
    const WAITINGLIST = "WAITINGLIST";
    const MISSING = "MISSING";
}
