<?php /

/**
 * This page lists all the instances of twitter in a particular course
 *
 * @author  María Emilia Charnelli <mcharnelli@linti.unlp.edu.ar>
 * @version $Id: index.php,v 1.7.2.3 2009/08/31 22:00:00 mudrd8mz Exp $
 * @package mod/twitter
 */


require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = required_param('id', PARAM_INT);   // course

if (! $course = get_record('course', 'id', $id)) {
    error('Course ID is incorrect');
}

require_course_login($course);

add_to_log($course->id, 'twitter', 'view all', "index.php?id=$course->id", '');


/// Get all required stringstwitter

$strtwitters = get_string('modulenameplural', 'twitter');
$strtwitter  = get_string('modulename', 'twitter');


/// Print the header

$navlinks = array();
$navlinks[] = array('name' => $strtwitters, 'link' => '', 'type' => 'activity');
$navigation = build_navigation($navlinks);

print_header_simple($strtwitters, '', $navigation, '', '', true, '', navmenu($course));

/// Get all the appropriate data

if (! $twitters = get_all_instances_in_course('twitter', $course)) {
    notice('There are no instances of twitter', "../../course/view.php?id=$course->id");
    die;
}

/// Print the list of instances (your module will probably extend this)

$timenow  = time();
$strname  = get_string('name');
$strweek  = get_string('week');
$strtopic = get_string('topic');

if ($course->format == 'weeks') {
    $table->head  = array ($strweek, $strname);
    $table->align = array ('center', 'left');
} else if ($course->format == 'topics') {
    $table->head  = array ($strtopic, $strname);
    $table->align = array ('center', 'left', 'left', 'left');
} else {
    $table->head  = array ($strname);
    $table->align = array ('left', 'left', 'left');
}

foreach ($twitters as $twitter) {
    if (!$twitter->visible) {
        //Show dimmed if the mod is hidden
        $link = '<a class="dimmed" href="view.php?id='.$twitter->coursemodule.'">'.format_string($twitter->name).'</a>';
    } else {
        //Show normal if the mod is visible
        $link = '<a href="view.php?id='.$twitter->coursemodule.'">'.format_string($twitter->name).'</a>';
    }

    if ($course->format == 'weeks' or $course->format == 'topics') {
        $table->data[] = array ($twitter->section, $link);
    } else {
        $table->data[] = array ($link);
    }
}

print_heading($strtwitters);
print_table($table);

/// Finish the page

print_footer($course);

?>
