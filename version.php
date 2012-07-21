<?php

/**
 * Code fragment to define the version of twitter
 * This fragment is called by moodle_needs_upgrading() and /admin/index.php
 *
 * @@author  Mari­a Emilia Charnelli <mcharnelli@linti.unlp.edu.ar>
 * @version $Id: version.php,v 1.5.2.2 2009/03/19 12:23:11 mudrd8mz Exp $
 * @package mod/twitter
 */

defined('MOODLE_INTERNAL') || die();

$module->version   = 1;               // If version == 0 then module will not be installed
$module->cron      = 0;               // Period for cron to check this module (secs)
$module->component = 'mod_twitter'; // To check on upgrade, that module sits in correct place
