<?php
/**
 *
 * @category        page
 * @package         External Calendar
 * @authors         Martin Hecht
 * @copyright       2004-2015, Website Baker Org. e.V.
 * @link            http://forum.websitebaker.org/index.php/topic,28493.0.html
 * @license         GNU General Public License
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 *
*/


/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
        require_once(dirname(dirname(__FILE__)).'/framework/globalExceptionHandler.php');
        throw new IllegalFileException();
}
/* -------------------------------------------------------- */


if(defined('WB_URL'))
{

        // Drop preexisting table
        $mod_extcal = "DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_extcal`";
        $database->query($mod_extcal);

        // Create table
        $mod_extcal = "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."mod_extcal` ("
                . " `section_id` INT NOT NULL DEFAULT '0',"
                . " `page_id` INT NOT NULL DEFAULT '0',"
                . " `cal_urls` TEXT NOT NULL DEFAULT '',"
                . " `max_entries` INT DEFAULT '0',"
                . " `max_days` INT DEFAULT '0',"
                . " `time_zone` TEXT NOT NULL DEFAULT '',"
                . " `dateformat` TEXT NOT NULL DEFAULT '',"                
                . " `date_end` TEXT NOT NULL DEFAULT '',"                 
                . " `section_start` TEXT NOT NULL DEFAULT '',"
                . " `section_end` TEXT NOT NULL DEFAULT '',"
                . " `enable_cache` INT DEFAULT '1',"
                . " `refresh_time` INT DEFAULT '0'," 
                . " `cache_time` INT DEFAULT '0'," 
                . " `description_end` TEXT NOT NULL DEFAULT '',"
                . " `entry_template` TEXT NOT NULL DEFAULT '',"
                . " `description_start` TEXT NOT NULL DEFAULT '',"
                . " `location_start` TEXT NOT NULL DEFAULT '',"
                . " `location_end` TEXT NOT NULL DEFAULT '',"
                . " `title_start` TEXT NOT NULL DEFAULT '',"
                . " `title_end` TEXT NOT NULL DEFAULT '',"
                . " `date_start` TEXT NOT NULL DEFAULT '',"
                . " `confidential_text` TEXT NOT NULL DEFAULT '',"                
                . " `timeformat` TEXT NOT NULL DEFAULT '',"         
                . " `date_separator` TEXT NOT NULL DEFAULT '',"        
                . " `date_template` TEXT NOT NULL DEFAULT '',"        
                . " `optimize_date` INT DEFAULT '1',"
                . " `midnight_fix` INT DEFAULT '1',"
                . " PRIMARY KEY ( `section_id` )"
                . " ) ENGINE='MyISAM' DEFAULT CHARSET='utf8' COLLATE='utf8_unicode_ci'";
        $database->query($mod_extcal);
}
