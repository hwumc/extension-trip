<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class HwumcTripExtension extends Extension
{
	public function getExtensionInformation()
	{
		return array(
			"name" => "HWUMC Trip Module",
			"gitviewer" => "https://gerrit.stwalkerster.co.uk/gitweb?p=siteframework/extensions/hwumc-trip.git;a=tree;h=",
			"description" => "HWUMC Trip Module",
			"filepath" => dirname(__FILE__),
		);
	}
	
	protected function autoload( $class )
	{
        $files = array(
            "HwumcTripHooks" => "HwumcTripHooks.php",
            "Trip" => "DataObjects/Trip.php",
            "TripHardStatus" => "TripHardStatus.php",
        );
		
		return array_key_exists($class, $files) ? $files[$class] : null;
	}
	
	protected function registerHooks()
	{
        Hooks::register( "BuildPageSearchPaths", array("HwumcTripHooks","buildPageSearchPaths"));
        Hooks::register( "PostSetupSmarty", array("HwumcTripHooks","smartySetup"));
	}
	

}