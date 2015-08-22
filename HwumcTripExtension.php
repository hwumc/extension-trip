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
            
            "Trip"                      => "DataObjects/Trip.php",
            "Signup"                    => "DataObjects/Signup.php",
            "Payment"                   => "DataObjects/Payment.php",
            "TripPaymentMethod"         => "DataObjects/TripPaymentMethod.php",
            
            "TripHardStatus"            => "TripHardStatus.php",
            "PaymentStatus"             => "PaymentStatus.php",
            
            "SignupListHelper"          => "SignupListHelper.php",
            "SignupStatus"              => "SignupStatus.php",
            
            "IPaymentMethod"            => "PaymentMethods/IPaymentMethod.php",
            "PaymentMethodBase"         => "PaymentMethods/PaymentMethodBase.php",
            "NullPaymentMethod"         => "PaymentMethods/NullPaymentMethod.php",
            "ManualPaymentMethod"       => "PaymentMethods/ManualPaymentMethod.php",
            "FakePayPalPaymentMethod"   => "PaymentMethods/FakePayPalPaymentMethod.php",
        );

        return array_key_exists($class, $files) ? $files[$class] : null;
    }

    protected function registerHooks()
    {
        Hooks::register( "BuildPageSearchPaths", array("HwumcTripHooks","buildPageSearchPaths"));
        Hooks::register( "PostSetupSmarty", array("HwumcTripHooks","smartySetup"));
        Hooks::register( "PreCreatePersonalMenu", array("HwumcTripHooks","addPersonalMenuItems"));
    }

}