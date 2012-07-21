<?php
/**
 *  mod_created, mod_updated event handler definition.
 *
 * @package   mod_twitter
 * @copyright 2012 LINTI, Mari­a Emilia Charnelli <mcharnelli@linti.unlp.edu.ar>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/* List of handlers */
$handlers = array (
    'mod_updated' => array (
        'handlerfile'      => '/mod/twitter/lib.php',
        'handlerfunction'  => 'twitter_course_updated',
        'schedule'         => 'instant',
    ),
    'mod_created' => array (
        'handlerfile'      => '/mod/twitter/lib.php',
        'handlerfunction'  => 'twitter_course_created',
        'schedule'         => 'instant',
    ),
);
?>