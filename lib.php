<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Library of interface functions and constants for module twitter
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 * All the twitter specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod
 * @subpackage twitter
 * @copyright  2012 LINTI, Mari­a Emilia Charnelli <mcharnelli@linti.unlp.edu.ar>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once ('twitteroauth.php');
defined('MOODLE_INTERNAL') || die();


////////////////////////////////////////////////////////////////////////////////
// Moodle core API                                                            //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the information on whether the module supports a feature
 *
 * @see plugin_supports() in lib/moodlelib.php
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function twitter_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_INTRO:         return true;
        default:                        return null;
    }
}

/**
 * Saves a new instance of the twitter into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $twitter An object from the form in mod_form.php
 * @param mod_twitter_mod_form $mform
 * @return int The id of the newly inserted twitter record
 */
function twitter_add_instance(stdClass $twitter, mod_twitter_mod_form $mform = null) {
    global $DB;

    $twitter->timecreated = time();

    # You may have to add extra stuff in here #

    return $DB->insert_record('twitter', $twitter);
}

/**
 * Updates an instance of the twitter in the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $twitter An object from the form in mod_form.php
 * @param mod_twitter_mod_form $mform
 * @return boolean Success/Fail
 */
function twitter_update_instance(stdClass $twitter, mod_twitter_mod_form $mform = null) {
    global $DB;

    $twitter->timemodified = time();
    $twitter->id = $twitter->instance;

    # You may have to add extra stuff in here #

    return $DB->update_record('twitter', $twitter);
}

/**
 * Removes an instance of the twitter from the database
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function twitter_delete_instance($id) {
    global $DB;

    if (! $twitter = $DB->get_record('twitter', array('id' => $id))) {
        return false;
    }

    # Delete any dependent records here #

    $DB->delete_records('twitter', array('id' => $twitter->id));

    return true;
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return stdClass|null
 */
function twitter_user_outline($course, $user, $mod, $twitter) {

    $return = new stdClass();
    $return->time = 0;
    $return->info = '';
    return $return;
}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @param stdClass $course the current course record
 * @param stdClass $user the record of the user we are generating report for
 * @param cm_info $mod course module info
 * @param stdClass $twitter the module instance record
 * @return void, is supposed to echp directly
 */
function twitter_user_complete($course, $user, $mod, $twitter) {
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in twitter activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @return boolean
 */
function twitter_print_recent_activity($course, $viewfullnames, $timestart) {
    return false;  //  True if anything was printed, otherwise false
}

/**
 * Prepares the recent activity data
 *
 * This callback function is supposed to populate the passed array with
 * custom activity records. These records are then rendered into HTML via
 * {@link twitter_print_recent_mod_activity()}.
 *
 * @param array $activities sequentially indexed array of objects with the 'cmid' property
 * @param int $index the index in the $activities to use for the next record
 * @param int $timestart append activity since this time
 * @param int $courseid the id of the course we produce the report for
 * @param int $cmid course module id
 * @param int $userid check for a particular user's activity only, defaults to 0 (all users)
 * @param int $groupid check for a particular group's activity only, defaults to 0 (all groups)
 * @return void adds items into $activities and increases $index
 */
function twitter_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid=0, $groupid=0) {
}

/**
 * Prints single activity item prepared by {@see twitter_get_recent_mod_activity()}

 * @return void
 */
function twitter_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function twitter_cron () {
    return true;
}

/**
 * Returns all other caps used in the module
 *
 * @example return array('moodle/site:accessallgroups');
 * @return array
 */
function twitter_get_extra_capabilities() {
    return array();
}

////////////////////////////////////////////////////////////////////////////////
// Gradebook API                                                              //
////////////////////////////////////////////////////////////////////////////////

/**
 * Is a given scale used by the instance of twitter?
 *
 * This function returns if a scale is being used by one twitter
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $twitterid ID of an instance of this module
 * @return bool true if the scale is used by the given twitter instance
 */
function twitter_scale_used($twitterid, $scaleid) {
    global $DB;

    /** @example */
    if ($scaleid and $DB->record_exists('twitter', array('id' => $twitterid, 'grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if scale is being used by any instance of twitter.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param $scaleid int
 * @return boolean true if the scale is used by any twitter instance
 */
function twitter_scale_used_anywhere($scaleid) {
    global $DB;

    /** @example */
    if ($scaleid and $DB->record_exists('twitter', array('grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Creates or updates grade item for the give twitter instance
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $twitter instance object with extra cmidnumber and modname property
 * @return void
 */
function twitter_grade_item_update(stdClass $twitter) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    /** @example */
    $item = array();
    $item['itemname'] = clean_param($twitter->name, PARAM_NOTAGS);
    $item['gradetype'] = GRADE_TYPE_VALUE;
    $item['grademax']  = $twitter->grade;
    $item['grademin']  = 0;

    grade_update('mod/twitter', $twitter->course, 'mod', 'twitter', $twitter->id, 0, null, $item);
}

/**
 * Update twitter grades in the gradebook
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $twitter instance object with extra cmidnumber and modname property
 * @param int $userid update grade of specific user only, 0 means all participants
 * @return void
 */
function twitter_update_grades(stdClass $twitter, $userid = 0) {
    global $CFG, $DB;
    require_once($CFG->libdir.'/gradelib.php');

    /** @example */
    $grades = array(); // populate array of grade objects indexed by userid

    grade_update('mod/twitter', $twitter->course, 'mod', 'twitter', $twitter->id, 0, $grades);
}

////////////////////////////////////////////////////////////////////////////////
// File API                                                                   //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the lists of all browsable file areas within the given module context
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by {@link file_browser::get_file_info_context_module()}
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function twitter_get_file_areas($course, $cm, $context) {
    return array();
}

/**
 * File browsing support for twitter file areas
 *
 * @package mod_twitter
 * @category files
 *
 * @param file_browser $browser
 * @param array $areas
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param int $itemid
 * @param string $filepath
 * @param string $filename
 * @return file_info instance or null if not found
 */
function twitter_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
    return null;
}

/**
 * Serves the files from the twitter file areas
 *
 * @package mod_twitter
 * @category files
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the twitter's context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 */
function twitter_pluginfile($course, $cm, $context, $filearea, array $args, $forcedownload, array $options=array()) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }

    require_login($course, true, $cm);

    send_file_not_found();
}

////////////////////////////////////////////////////////////////////////////////
// Navigation API                                                             //
////////////////////////////////////////////////////////////////////////////////

/**
 * Extends the global navigation tree by adding twitter nodes if there is a relevant content
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $navref An object representing the navigation tree node of the twitter module instance
 * @param stdClass $course
 * @param stdClass $module
 * @param cm_info $cm
 */
function twitter_extend_navigation(navigation_node $navref, stdclass $course, stdclass $module, cm_info $cm) {
}

/**
 * Extends the settings navigation with the twitter settings
 *
 * This function is called when the context for the page is a twitter module. This is not called by AJAX
 * so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav {@link settings_navigation}
 * @param navigation_node $twitternode {@link navigation_node}
 */
function twitter_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $twitternode=null) {
}

/** 
 * Split a string into groups of words with a line no longer than $max 
 * characters. 
 * 
 * @param string $string 
 * @param integer $max 
 * @return array 
 **/ 
function split_words($string, $max = 1) 
{ 
    $words = preg_split('/\s/', $string); 
    $lines = array(); 
    $line = ''; 
    
    foreach ($words as $k => $word) { 
        $length = strlen($line . ' ' . $word); 
        if ($length <= $max) { 
            $line .= ' ' . $word; 
        } else if ($length > $max) { 
            if (!empty($line)) $lines[] = trim($line); 
            $line = $word; 
        } else { 
            $lines[] = trim($line) . ' ' . $word; 
            $line = ''; 
        } 
    } 
    $lines[] = ($line = trim($line)) ? $line : $word; 

    return $lines; 
}

/**
 * mod updated event handler
 *
 * @param object $mod full $MOD object
 */
 
function twitter_course_updated($mod) 
{ 
    $post_format=get_string('updatemodmessage', 'twitter');

    twitter_post($mod, $post_format); 
    return true;  
}
/**
 * mod created event handler
 *
 * @param object $mod full $MOD object
 */
function twitter_course_created($mod) 
{         
    $post_format=get_string('createmodmessage', 'twitter'); 
    twitter_post($mod, $post_format);
    return true;  
}
/**
 * handler helper
 *
 * @param object $mod full $MOD object
 * @param object $post full $POST object
 */
function twitter_post($mod, $post_format) {
        require('config.php');
        global $DB;
        $type=get_string('modulename', $mod->modulename);
        $type= strtolower($type);
        $name=$mod->name;
        $course= $DB->get_record('course', array('id'=>$mod->courseid));
        $course=$course->fullname;
        $url=new moodle_url('/mod/'.$mod->modulename.'/view.php', array('id'=>$mod->cmid));
            
        $post=sprintf($post_format, $type, $name, $course, $url);
        $post_split=split_words($post,140);
        $twitters= $DB->get_records('twitter', array('course'=>$mod->courseid));
        foreach ($twitters as $twitter){
            
            $oauth = new TwitterOAuth($consumer_key, $consumer_secret,$twitter->access_token,$twitter->access_token_secret);
            $content = $oauth->get('account/verify_credentials');
            for($i=count($post_split)-1; $i>=0; $i--){
                $oauth->post('statuses/update', array('status' => html_entity_decode($post_split[$i] , ENT_COMPAT   , 'UTF-8')));
            }
            
            
        }
}
