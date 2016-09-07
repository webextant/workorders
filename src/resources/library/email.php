<?php
    set_include_path(dirname(__FILE__).'/../');
    require_once('config/appconfig.php');

    /**
     * Link generation utility. appconfig.php contains settings for base url and defult script names used in links.
     */
    class LinkHelper
    {
        public static function getApproverLink($workorder)
        {
            return Config::BaseUrl . Config::WorkorderApproverScript . '?id=' . $workorder->id . '&key=' . $workorder->approverKey;
        }
        
        public static function getViewOnlyLink($workorder)
        {
            return Config::BaseUrl . Config::WorkorderViewOnlyScript . '?id=' . $workorder->id . '&key=' . $workorder->viewOnlyKey;           
        }
    }
    

?>