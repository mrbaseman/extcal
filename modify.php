<?php
/**
 *
 * @category        page
 * @package         External Calendar
 * @version         1.2.13
 * @authors         Martin Hecht
 * @copyright       (c) 2015 - 2025, Martin Hecht (mrbaseman)
 * @link            https://github.com/mrbaseman/extcal
 * @license         GNU General Public License
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.3 and higher and Curl
 *
 **/


/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if (!defined('WB_PATH')) {
        // Stop this file being access directly
        if (!headers_sent()) {
                 header("Location: ../index.php", true, 301);
        }
        die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */



$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

// Setup template object
$template = new Template(WB_PATH.'/modules/extcal');
$template->set_file('page', 'htt/modify.htt');
$template->set_block('page', 'main_block', 'main');

$is_advanced="no";

if(isset($_GET['advanced']) && $_GET['advanced'] == 'yes') {
    $is_advanced="yes";
}

if($is_advanced=="yes")
{
    $display_advanced = '';
    $advanced = 'yes';
    $advanced_button = '&lt;&lt; '.$TEXT['HIDE_ADVANCED'];
    $advanced_link = 'modify.php?advanced=no&page_id='.$page_id;
} else {
    $display_advanced = ' style="display: none;"';
    $advanced = 'no';
    $advanced_button = $TEXT['SHOW_ADVANCED'].' &gt;&gt;';
    $advanced_link = 'modify.php?advanced=yes&page_id='.$page_id;
}

$mode_switch_warning = $MESSAGE['SETTINGS_MODE_SWITCH_WARNING'];

// Get content

$query="SELECT "
    . " `cal_urls`,"
    . " `max_entries`,"
    . " `max_days`,"
    . " `time_zone`,"
    . " `dateformat`,"
    . " `date_end`,"
    . " `section_start`,"
    . " `section_end`,"
    . " `enable_cache`,"
    . " `refresh_time`,"
    . " `cache_time`,"
    . " `description_end`,"
    . " `entry_template`,"
    . " `description_start`,"
    . " `location_start`,"
    . " `location_end`,"
    . " `title_start`,"
    . " `title_end`,"
    . " `date_start`,"
    . " `confidential_text`,"
    . " `timeformat`,"
    . " `date_separator`,"
    . " `date_template`,"
    . " `optimize_date`,"
    . " `midnight_fix`, "
    . " `verify_peer`,"
    . " `keep_todays_events`,"
    . " `time_offset`,"
    . " `calendar_start`,"
    . " `calendar_end` "
    . " FROM `".TABLE_PREFIX."mod_extcal`"
    . " WHERE `section_id` = '$section_id'";

$get_content = $database->query($query);
$content = $get_content->fetchRow();

$cal_urls = htmlspecialchars($content['cal_urls']);
$max_days = intval($content['max_days']) or $max_days = 0;
$max_entries = intval($content['max_entries']) or $max_entries = 0;

$time_zone = $content['time_zone'];
$TZ=$LANG['frontend']['MOD_EXTCAL_TIMEZONE'];
if($TZ===NULL or $TZ==="{DEFAULT}") $TZ=date_default_timezone_get();
if($time_zone === "{DEFAULT}" or $time_zone===NULL)$time_zone=$TZ;

$dateformat = $content['dateformat'];
if($dateformat === "{DEFAULT}" or $dateformat===NULL)
    $dateformat=$LANG['frontend']['MOD_EXTCAL_DATEFORMAT'];

$date_end = $content['date_end'];
if($date_end === "{DEFAULT}" or $date_end===NULL)
    $date_end=$LANG['frontend']['MOD_EXTCAL_DATE_END'];

$section_start = $content['section_start'];
if($section_start === "{DEFAULT}" or $section_start===NULL)
    $section_start=$LANG['frontend']['MOD_EXTCAL_SECTION_START'];

$section_end = $content['section_end'];
if($section_end === "{DEFAULT}" or $section_end===NULL)
    $section_end=$LANG['frontend']['MOD_EXTCAL_SECTION_END'];

$enable_cache = $content['enable_cache'];
if($enable_cache != 0) $enable_cache="checked";
    else $enable_cache="{DEFAULT}";

$refresh_time = intval($content['refresh_time']) or $refresh_time = 0;

$cache_time = intval($content['cache_time']) or $cache_time = 0;

$description_end = $content['description_end'];
if($description_end === "{DEFAULT}" or $description_end===NULL)
    $description_end=$LANG['frontend']['MOD_EXTCAL_DESCRIPTION_END'];

$entry_template = $content['entry_template'];
if($entry_template === "{DEFAULT}" or $entry_template===NULL)
    $entry_template=$LANG['frontend']['MOD_EXTCAL_ENTRY_TEMPLATE'];

$description_start = $content['description_start'];
if($description_start === "{DEFAULT}" or $description_start===NULL)
    $description_start=$LANG['frontend']['MOD_EXTCAL_DESCRIPTION_START'];

$location_start = $content['location_start'];
if($location_start === "{DEFAULT}" or $location_start===NULL)
    $location_start=$LANG['frontend']['MOD_EXTCAL_LOCATION_START'];

$location_end = $content['location_end'];
if($location_end === "{DEFAULT}" or $location_end===NULL)
    $location_end=$LANG['frontend']['MOD_EXTCAL_LOCATION_END'];

$title_start = $content['title_start'];
if($title_start === "{DEFAULT}" or $title_start===NULL)
    $title_start=$LANG['frontend']['MOD_EXTCAL_TITLE_START'];

$title_end = $content['title_end'];
if($title_end === "{DEFAULT}" or $title_end===NULL)
    $title_end=$LANG['frontend']['MOD_EXTCAL_TITLE_END'];

$date_start = $content['date_start'];
if($date_start === "{DEFAULT}" or $date_start===NULL)
    $date_start=$LANG['frontend']['MOD_EXTCAL_DATE_START'];

$confidential_text = $content['confidential_text'];
if($confidential_text === "{DEFAULT}" or $confidential_text===NULL)
    $confidential_text=$LANG['frontend']['MOD_EXTCAL_CONFIDENTIAL_TEXT'];

$timeformat = $content['timeformat'];
if($timeformat === "{DEFAULT}" or $timeformat===NULL)
    $timeformat=$LANG['frontend']['MOD_EXTCAL_TIMEFORMAT'];

$date_separator = $content['date_separator'];
if($date_separator === "{DEFAULT}" or $date_separator===NULL)
    $date_separator=$LANG['frontend']['MOD_EXTCAL_DATE_SEPARATOR'];

$date_template = $content['date_template'];
if($date_template === "{DEFAULT}" or $date_template===NULL)
    $date_template=$LANG['frontend']['MOD_EXTCAL_DATE_TEMPLATE'];

$optimize_date = $content['optimize_date'];
if($optimize_date != 0) $optimize_date="checked";
    else $optimize_date="";

$midnight_fix = $content['midnight_fix'];
if($midnight_fix != 0) $midnight_fix="checked";
    else $midnight_fix="";

$verify_peer = $content['verify_peer'];
if($verify_peer != 0) $verify_peer="checked";
    else $verify_peer="";

$keep_todays_events = $content['keep_todays_events'];
if($keep_todays_events != 0) $keep_todays_events="checked";
    else $keep_todays_events="";

$time_offset = intval($content['time_offset']) or $time_offset = 0;

$calendar_start = $content['calendar_start'];
if($calendar_start === "{DEFAULT}" or $calendar_start===NULL)
    $calendar_start=$LANG['frontend']['MOD_EXTCAL_CALENDAR_START'];

$calendar_end = $content['calendar_end'];
if($calendar_end === "{DEFAULT}" or $calendar_end===NULL)
    $calendar_end=$LANG['frontend']['MOD_EXTCAL_CALENDAR_END'];



// calculate cache size
$list = glob(dirname(__FILE__).'/cache/*.ics');
$dir_size = 0;
if (is_array($list)) foreach ($list as $file_name) $dir_size += filesize($file_name);
$cache_size=round( $dir_size/1024,2 ).'&nbsp;kB';

// Insert vars
$template->set_var(
    array(  'LANGUAGE'                  => LANGUAGE,
        'PAGE_ID'                       => $page_id,
        'SECTION_ID'                    => $section_id,
        'WB_URL'                        => WB_URL,
        'TXT_EXTCAL_SETTINGS_URLS'      => $LANG['backend']['TXT_EXTCAL_SETTINGS_URLS'],
        'TXT_EXTCAL_MAX_DAYS'           => $LANG['backend']['TXT_EXTCAL_MAX_DAYS'],
        'TXT_EXTCAL_MAX_ENTRIES'        => $LANG['backend']['TXT_EXTCAL_MAX_ENTRIES'],
        'TXT_EXTCAL_TIME_ZONE'          => $LANG['backend']['TXT_EXTCAL_TIME_ZONE'],
        'TXT_EXTCAL_DATEFORMAT'         => $LANG['backend']['TXT_EXTCAL_DATEFORMAT'],
        'TXT_EXTCAL_DATE_END'           => $LANG['backend']['TXT_EXTCAL_DATE_END'],
        'TXT_EXTCAL_SECTION_START'      => $LANG['backend']['TXT_EXTCAL_SECTION_START'],
        'TXT_EXTCAL_SECTION_END'        => $LANG['backend']['TXT_EXTCAL_SECTION_END'],
        'TXT_EXTCAL_CACHE_SIZE'         => $LANG['backend']['TXT_EXTCAL_CACHE_SIZE'],
        'TXT_EXTCAL_EMPTY_CACHE'        => $LANG['backend']['TXT_EXTCAL_EMPTY_CACHE'],
        'TXT_EXTCAL_ENABLE_CACHE'       => $LANG['backend']['TXT_EXTCAL_ENABLE_CACHE'],
        'TXT_EXTCAL_REFRESH_TIME'       => $LANG['backend']['TXT_EXTCAL_REFRESH_TIME'],
        'TXT_EXTCAL_CACHE_TIME'         => $LANG['backend']['TXT_EXTCAL_CACHE_TIME'],
        'TXT_EXTCAL_DESCRIPTION_END'    => $LANG['backend']['TXT_EXTCAL_DESCRIPTION_END'],
        'TXT_EXTCAL_HELP_PAGE'          => $LANG['backend']['TXT_EXTCAL_HELP_PAGE'],
        'TXT_EXTCAL_ENTRY_TEMPLATE'     => $LANG['backend']['TXT_EXTCAL_ENTRY_TEMPLATE'],
        'TXT_EXTCAL_DESCRIPTION_START'  => $LANG['backend']['TXT_EXTCAL_DESCRIPTION_START'],
        'TXT_EXTCAL_LOCATION_START'     => $LANG['backend']['TXT_EXTCAL_LOCATION_START'],
        'TXT_EXTCAL_LOCATION_END'       => $LANG['backend']['TXT_EXTCAL_LOCATION_END'],
        'TXT_EXTCAL_TITLE_START'        => $LANG['backend']['TXT_EXTCAL_TITLE_START'],
        'TXT_EXTCAL_TITLE_END'          => $LANG['backend']['TXT_EXTCAL_TITLE_END'],
        'TXT_EXTCAL_DATE_START'         => $LANG['backend']['TXT_EXTCAL_DATE_START'],
        'TXT_EXTCAL_CONFIDENTIAL_TEXT'  => $LANG['backend']['TXT_EXTCAL_CONFIDENTIAL_TEXT'],
        'TXT_EXTCAL_TIMEFORMAT'         => $LANG['backend']['TXT_EXTCAL_TIMEFORMAT'],
        'TXT_EXTCAL_DATE_SEPARATOR'     => $LANG['backend']['TXT_EXTCAL_DATE_SEPARATOR'],
        'TXT_EXTCAL_DATE_TEMPLATE'      => $LANG['backend']['TXT_EXTCAL_DATE_TEMPLATE'],
        'TXT_EXTCAL_OPTIMIZE_DATE'      => $LANG['backend']['TXT_EXTCAL_OPTIMIZE_DATE'],
        'TXT_EXTCAL_MIDNIGHT_FIX'       => $LANG['backend']['TXT_EXTCAL_MIDNIGHT_FIX'],
        'TXT_EXTCAL_VERIFY_PEER'        => $LANG['backend']['TXT_EXTCAL_VERIFY_PEER'],
        'TXT_EXTCAL_KEEP_TODAYS_EVENTS' => $LANG['backend']['TXT_EXTCAL_KEEP_TODAYS_EVENTS'],
        'TXT_EXTCAL_TIME_OFFSET'        => $LANG['backend']['TXT_EXTCAL_TIME_OFFSET'],
        'TXT_EXTCAL_CALENDAR_START'     => $LANG['backend']['TXT_EXTCAL_CALENDAR_START'],
        'TXT_EXTCAL_CALENDAR_END'       => $LANG['backend']['TXT_EXTCAL_CALENDAR_END'],
        'TXT_EXTCAL_GENERAL_SETTINGS'   => $LANG['backend']['TXT_EXTCAL_GENERAL_SETTINGS'],
        'TXT_EXTCAL_DATE_FORMAT_SETTINGS' => $LANG['backend']['TXT_EXTCAL_DATE_FORMAT_SETTINGS'],
        'TXT_EXTCAL_FORMAT_SETTINGS'    => $LANG['backend']['TXT_EXTCAL_FORMAT_SETTINGS'],
        'TXT_EXTCAL_DIVERSE_SETTINGS'   => $LANG['backend']['TXT_EXTCAL_DIVERSE_SETTINGS'],
        'TXT_EXTCAL_CACHE_SETTINGS'     => $LANG['backend']['TXT_EXTCAL_CACHE_SETTINGS'],
        'DISPLAY_ADVANCED'              => $display_advanced,
        'ADVANCED'                      => $advanced,
        'ADVANCED_BUTTON'               => $advanced_button,
        'ADVANCED_LINK'                 => $advanced_link,
        'MODE_SWITCH_WARNING'           => $mode_switch_warning,
        'MOD_EXTCAL_CAL_URLS'           => htmlspecialchars($cal_urls),
        'MOD_EXTCAL_MAX_DAYS'           => htmlspecialchars($max_days),
        'MOD_EXTCAL_MAX_ENTRIES'        => htmlspecialchars($max_entries),
        'MOD_EXTCAL_TIME_ZONE'          => htmlspecialchars($time_zone),
        'MOD_EXTCAL_DATEFORMAT'         => htmlspecialchars($dateformat),
        'MOD_EXTCAL_DATE_END'           => htmlspecialchars($date_end),
        'MOD_EXTCAL_SECTION_START'      => htmlspecialchars($section_start),
        'MOD_EXTCAL_SECTION_END'        => htmlspecialchars($section_end),
        'MOD_EXTCAL_CACHE_SIZE'         => $cache_size,
        'MOD_EXTCAL_ENABLE_CACHE'       => $enable_cache,
        'MOD_EXTCAL_REFRESH_TIME'       => htmlspecialchars($refresh_time),
        'MOD_EXTCAL_CACHE_TIME'         => htmlspecialchars($cache_time),
        'MOD_EXTCAL_DESCRIPTION_END'    => htmlspecialchars($description_end),
        'MOD_EXTCAL_ENTRY_TEMPLATE'     => htmlspecialchars($entry_template),
        'MOD_EXTCAL_DESCRIPTION_START'  => htmlspecialchars($description_start),
        'MOD_EXTCAL_LOCATION_START'     => htmlspecialchars($location_start),
        'MOD_EXTCAL_LOCATION_END'       => htmlspecialchars($location_end),
        'MOD_EXTCAL_TITLE_START'        => htmlspecialchars($title_start),
        'MOD_EXTCAL_TITLE_END'          => htmlspecialchars($title_end),
        'MOD_EXTCAL_DATE_START'         => htmlspecialchars($date_start),
        'MOD_EXTCAL_CONFIDENTIAL_TEXT'  => htmlspecialchars($confidential_text),
        'MOD_EXTCAL_TIMEFORMAT'         => htmlspecialchars($timeformat),
        'MOD_EXTCAL_DATE_SEPARATOR'     => htmlspecialchars($date_separator),
        'MOD_EXTCAL_DATE_TEMPLATE'      => htmlspecialchars($date_template),
        'MOD_EXTCAL_OPTIMIZE_DATE'      => $optimize_date,
        'MOD_EXTCAL_MIDNIGHT_FIX'       => $midnight_fix,
        'MOD_EXTCAL_VERIFY_PEER'        => $verify_peer,
        'MOD_EXTCAL_KEEP_TODAYS_EVENTS' => $keep_todays_events,
        'MOD_EXTCAL_TIME_OFFSET'        => $time_offset,
        'MOD_EXTCAL_CALENDAR_START'     => htmlspecialchars($calendar_start),
        'MOD_EXTCAL_CALENDAR_END'       => htmlspecialchars($calendar_end),
        'TEXT_SAVE'                     => $TEXT['SAVE'],
        'TEXT_CANCEL'                   => $TEXT['CANCEL'],
        'FTAN'                          => $admin->getFTAN()
    )
);


echo "<h2>Extcal</h2><p>".$LANG['backend']['TXT_EXTCAL_SETTINGS']."</p>\n";


// include core functions of WB 2.7 to edit the optional module CSS files (frontend.css)
@include_once(WB_PATH .'/framework/module.functions.php');

// include the button to edit the optional module CSS files (function added with WB 2.7)
// Place this call outside of any <form></form> construct!!!
if(function_exists('edit_module_css'))
{
    edit_module_css('extcal');
}


// Parse template object
$template->set_unknowns('keep');
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page', false);
