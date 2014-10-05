<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class HwumcTripHooks
{
    public static function buildPageSearchPaths($args)
    {
        $paths = $args[0];
        $paths[] = dirname(__FILE__) . "/Page/";
        return $paths;
    }

    public static function smartySetup($args)
    {
        $smarty = $args[0];

        $smarty->addTemplateDir(dirname(__FILE__) . "/Templates/");

        return $smarty;
    }

    public static function addPersonalMenuItems( $menu ) {
        global $cScriptPath;

        $menu = $menu[0];

        if( !isset($menu['trip']) )
        {
            $menu['trip'] = array();
        }

        $menu['trip']['mytrips'] = array(
            "displayname" => "trips-mytrips",
            "link" => $cScriptPath . "/MyTrips",
            "icon" => "icon-road"
            );

        return $menu;
    }
}