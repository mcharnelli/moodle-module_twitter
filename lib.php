<?php  

/** 
 * Library of functions and constants for module twitter
 * This file should have two well differenced parts:
 *   - All the core Moodle functions, neeeded to allow
 *     the module to work integrated in Moodle.
 *   - All the twitter specific functions, needed
 *     to implement all the module logic. Please, note
 *     that, if the module become complex and this lib
 *     grows a lot, it's HIGHLY recommended to move all
 *     these module specific functions to a new php file,
 *     called "locallib.php" (see forum, quiz...). This will
 *     help to save some memory when Moodle is performing
 *     actions across all modules.
 */

$twitter_EXAMPLE_CONSTANT = 42;    

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $twitter An object from the form in mod_form.php
 * @return int The id of the newly inserted twitter record
 */
require_once ('twitteroauth.php');

function twitter_add_instance($twitter) {

    $twitter->timecreated = time();


    return insert_record('twitter', $twitter);
}


/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $twitter An object from the form in mod_form.php
 * @return boolean Success/Fail
 */
function twitter_update_instance($twitter) {

    $twitter->timemodified = time();
    $twitter->id = $twitter->instance;

    return update_record('twitter', $twitter);
}


/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function twitter_delete_instance($id) {

    if (! $twitter = get_record('twitter', 'id', $id)) {
        return false;
    }

    $result = true;

    if (! delete_records('twitter', 'id', $twitter->id)) {
        $result = false;
    }

    return $result;
}


/**
 * Return a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 * @todo Finish documenting this function
 */
function twitter_user_outline($course, $user, $mod, $twitter) {
    return $return;
}


/**
 * Print a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 */
function twitter_user_complete($course, $user, $mod, $twitter) {
    return true;
}


/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in twitter activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @return boolean
 * @todo Finish documenting this function
 */
function twitter_print_recent_activity($course, $isteacher, $timestart) {
    return false; 
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
 * Must return an array of user records (all data) who are participants
 * for a given instance of twitter. Must include every user involved
 * in the instance, independient of his role (student, teacher, admin...)
 * See other modules as example.
 *
 * @param int $twitterid ID of an instance of this module
 * @return mixed boolean/array of students
 */
function twitter_get_participants($twitterid) {
    return false;
}


/**
 * This function returns if a scale is being used by one twitter
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $twitterid ID of an instance of this module
 * @return mixed
 * @todo Finish documenting this function
 */
function twitter_scale_used($twitterid, $scaleid) {
    $return = false;

    return $return;
}


/**
 * Checks if scale is being used by any instance of twitter.
 * This function was added in 1.9
 *
 * This is used to find out if scale used anywhere
 * @param $scaleid int
 * @return boolean True if the scale is used by any twitter
 */
function twitter_scale_used_anywhere($scaleid) {
    if ($scaleid and record_exists('twitter', 'grade', -$scaleid)) {
        return true;
    } else {
        return false;
    }
}


/**
 * Execute post-install custom actions for the module
 * This function was added in 1.9
 *
 * @return boolean true if success, false on error
 */
function twitter_install() {
    return true;
}


/**
 * Execute post-uninstall custom actions for the module
 * This function was added in 1.9
 *
 * @return boolean true if success, false on error
 */
function twitter_uninstall() {
    return true;
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
 * course updated event handler
 *
 * @param object $mod full $MOD object
 */
function twitter_course_updated($mod) 
{ 
    require('config.php');
  
    if (isset($mod->subject))   {

        if ($mod->subject=='actualizado')
            $post='Se actualizó ';
        else
            $post='Se creó ';
    
        $tipo=$mod->modulename;
        $nombre=$mod->name;
        $curso= get_record('course', 'id', $mod->courseid);
        $curso=$curso->fullname;

        $post=$post. $tipo. ' '. $nombre. ' en '. $curso;

        $post_split=split_words($post,140);

        $twitters= get_records('twitter', 'course', $mod->courseid);
        foreach ($twitters as $twitter){
            
            $oauth = new TwitterOAuth($consumer_key, $consumer_secret,$twitter->access_token,$twitter->access_token_secret);
            $content = $oauth->get('account/verify_credentials');
            
            for($i=0; $i<count($post_split); $i++){
                $oauth->post('statuses/update', array('status' => $post_split[$i]));
            }
        }
    }   
    return true;  
}

?>
