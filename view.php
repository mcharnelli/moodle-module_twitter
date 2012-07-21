<?php
/**
 * This page prints a particular instance of twitter
 *
 * @author  Mari­a Emilia Charnelli <mcharnelli@linti.unlp.edu.ar>
 * @version $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $
 * @package mod/twitter
 */


require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // twitter instance ID - it should be named as the first character of the module

if ($id) {
    $cm         = get_coursemodule_from_id('twitter', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $twitter  = $DB->get_record('twitter', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $twitter  = $DB->get_record('twitter', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $twitter->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('twitter', $twitter->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);

add_to_log($course->id, 'twitter', 'view', "view.php?id={$cm->id}", $twitter->name, $cm->id);

/// Print the page header

$PAGE->set_url('/mod/twitter/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($twitter->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');
//$PAGE->add_body_class('twitter-'.$somevar);

// Output starts here
echo $OUTPUT->header();

if ($twitter->intro) { // Conditions to show the intro can change to look for own settings or whatever
    echo $OUTPUT->box(format_module_intro('twitter', $twitter, $cm->id), 'generalbox mod_introbox', 'twitterintro');
}

// Replace the following lines with you own code
echo $OUTPUT->heading('Yay! It works!');

// Finish the page
echo $OUTPUT->footer();
