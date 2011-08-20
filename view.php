<?php 
/**
 * This page prints a particular instance of twitter
 *
 * @author  María Emilia Charnelli <mcharnelli@linti.unlp.edu.ar>
 * @version $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $
 * @package mod/twitter
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$a  = optional_param('a', 0, PARAM_INT);  // twitter instance ID

if ($id) {
    if (! $cm = get_coursemodule_from_id('twitter', $id)) {
        error('Course Module ID was incorrect');
    }

    if (! $course = get_record('course', 'id', $cm->course)) {
        error('Course is misconfigured');
    }

    if (! $twitter = get_record('twitter', 'id', $cm->instance)) {
        error('Course module is incorrect');
    }

} else if ($a) {
    if (! $twitter = get_record('twitter', 'id', $a)) {
        error('Course module is incorrect');
    }
    if (! $course = get_record('course', 'id', $twitter->course)) {
        error('Course is misconfigured');
    }
    if (! $cm = get_coursemodule_from_instance('twitter', $twitter->id, $course->id)) {
        error('Course Module ID was incorrect');
    }

} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

add_to_log($course->id, "twitter", "view", "view.php?id=$cm->id", "$twitter->id");

/// Print the page header
$strtwitters = get_string('modulenameplural', 'twitter');
$strtwitter  = get_string('modulename', 'twitter');

$navlinks = array();
$navlinks[] = array('name' => $strtwitters, 'link' => "index.php?id=$course->id", 'type' => 'activity');
$navlinks[] = array('name' => format_string($twitter->name), 'link' => '', 'type' => 'activityinstance');

$navigation = build_navigation($navlinks);

print_header_simple(format_string($twitter->name), '', $navigation, '', '', true,
              update_module_button($cm->id, $course->id, $strtwitter), navmenu($course, $cm));

/// Print the main part of the page

echo "Módulo Twitter";


/// Finish the page
print_footer($course);

?>
