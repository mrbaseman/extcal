<?php
/**
 *
 * @category        page
 * @package         External Calendar
 * @version         1.1.0
 * @authors         Martin Hecht
 * @copyright       (c) 2015 - 2016, Martin Hecht (mrbaseman)
 * @link            http://forum.websitebaker.org/index.php/topic,28493.0.html
 * @link            https://github.com/WebsiteBaker-modules/extcal
 * @license         GNU General Public License
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.3 and higher and Curl 
 *
 **/

/*
 *        CHANGELOG
 *        1.1.0        2016-02-11        - correctly handle and prefilter reoccurring events 
 *        1.0.9        2016-02-07        - add settings array to the user functions
 *        1.0.8        2016-02-05        - allow to add an offset to the server time 
 *        1.0.7        2016-02-04        - another fix for 12/24h handling
 *        1.0.6        2016-02-04        - set up time zone before evaluating starttime
 *        1.0.5        2016-02-03        - suppress error message for empty calendars
 *        1.0.4        2016-02-03        - allow to hide events that already have ended earlier today
 *        1.0.3        2016-01-30        - further improve link example in user functions
 *        1.0.2        2016-01-29        - do not change already html-formatted links
 *        1.0.1        2016-01-29        - do not display events after defined endtime
 *        1.0.0        2016-01-28        - add user function hooks
 *        0.9.9        2016-01-26        - support digest authentication for get requests
 *        0.9.8        2016-01-25        - set user agent to Extcal instead of real one
 *        0.9.7        2016-01-25        - better handle ssl in get requests (skip verification if requestetd)
 *        0.9.6        2016-01-15        - correctly show long lasting current appointments
 *        0.9.5        2016-01-15        - do not overwrite style file upon upgrade anymore
 *        0.9.4        2016-01-15        - improved the placeholders in template for allday events
 *        0.9.3        2016-01-14        - send user agent for advanced authentication
 *        0.9.2        2016-01-11        - add the option to skip ssl certificate verification
 *        0.9.1        2015-07-29        - fix start time check for wholeday events
 *        0.9.0        2015-07-22        - add checkbox to control the midnight shift
 *        0.8.11        2015-07-22        - properly treat calendar urls, which are commented out
 *        0.8.10        2015-07-22        - fixed events first ocurring before 1970,thanks to Alex
 *        0.8.9        2015-07-16        - updated url, fixed regression for date end at midnight
 *        0.8.8        2015-07-16        - fixed one minute shift of endtime
 *        0.8.7        2015-07-14        - re-added a description for the urls input in the backend
 *        0.8.6        2015-04-10        - fixed limit in max_days for not reoccurring events
 *        0.8.5        2015-04-02        - hide advanced settings by default (inspired by Ruud)
 *        0.8.4        2015-04-02        - optimize-date: drop end time for multiday-events
 *        0.8.3        2015-03-30        - allow date-place holders in entry-template 
 *        0.8.2        2015-03-30        - improve css template, thanks to Ruud for proposing 
 *        0.8.1        2015-03-30        - bugfix in update script, thanks to Ruud for reporting 
 *        0.8.0        2015-03-28        - added css support, update help pages 
 *        0.7.2        2015-03-24        - several bugfixes, cleanup from leftover code 
 *        0.7.1        2015-03-18        - added separate date template 
 *        0.7.0        2015-03-16        - changed backend to template solution
 *        0.6.7        2015-03-12        - improve update script, fix format of multiday entries
 *        0.6.6        2015-03-11        - add formatting block for the end of description
 *        0.6.5        2015-03-11        - updated help page, also translate button
 *        0.6.4        2015-03-10        - rename strings in LANG to avoid collisions 
 *        0.6.3        2015-03-09        - add div around entries as default setting
 *        0.6.2        2015-03-08        - replace editor by a simple text area
 *        0.6.1        2015-03-08        - htmlentities and nl2br for description, title etc.
 *        0.6.0        2015-03-06        - further cleanup code, make module ready for publishing
 *        0.5.4        2015-03-05        - translate cache success message
 *        0.5.3        2015-03-05        - updated save to better sanitize input, cleanup code
 *        0.5.2        2015-03-05        - added link description of help page to LANG
 *        0.5.1        2015-03-05        - updated name of sql column location_separator 
 *        0.5.0        2015-03-04        - added Italian language support,minor things in view.php 
 *        0.4.0        2015-03-01        - added French language support
 *        0.3.0        2015-02-28        - filled the help page with lots of details for DE and EN
 *        0.2.6        2015-02-28        - added help page and moved description from info to help
 *        0.2.5        2015-02-27        - cache times configurable, allow displaying entry details
 *        0.2.4        2015-02-23        - added cache for calendars - short time (5 Min)
 *        0.2.3        2015-02-21        - added cache for caldav entries (up to 1 Week)
 *        0.2.2        2015-02-21        - added section start and end fields
 *        0.2.1        2015-02-16        - bugfix view split urls correctly between calendars
 *        0.2.0        2015-02-13        - updated description, added example, fixed uninstall
 *        0.1.0        2015-02-12        - updated description, documentation, clean up code
 *        0.0.15        2015-02-11        - bugfix for accessing CalDAV
 *        0.0.14        2015-02-11        - added CalDAV support with SabreDAV
 *        0.0.13        2015-02-11        - added lots of formatting options to backend
 *        0.0.12        2015-02-11        - fixed install and extended upgrade
 *        0.0.11        2015-02-11        - fixed some php issues in SG_iCal, view, and timezone 
 *        0.0.10        2015-02-08        - added time zone setting to backend
 *        0.0.9        2015-02-05        - handling of privacy settings in calendar entries
 *        0.0.8        2015-02-01        - moved TZ settings to lang package, prevent dir listings
 *        0.0.7        2015-02-01        - added German language support in backend
 *        0.0.6        2015-02-01        - fixed most of the daylight saving issues
 *        0.0.5        2015-01-29        - some more bugfixes in the handling of dates
 *        0.0.4        2015-01-29        - fixed sql in save
 *        0.0.3        2015-01-29        - fixed modify and save
 *      0.0.2   2015-01-29      - implemented most things using SG_iCalendar
 *      0.0.1        2015-01-27      - first version of this module (Martin Hecht)
 *
 */


/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
        // Stop this file being access directly
        die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */


$module_directory        = 'extcal';
$module_name                = 'External Calendar';
$module_function        = 'page';
$module_version                = '1.1.0';
$module_platform        = 'WebsiteBaker 2.8.x';
$module_author                = 'Martin Hecht';
$module_license                = 'GNU General Public License';
$module_description        = 'The module External Calendar allows you to include external calendars (DavCAL or ics) into a WebsiteBaker page.';



