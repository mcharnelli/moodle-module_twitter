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

$module->version   = 2013101000;     
$module->requires  = 2012120300; 
$module->release   = '2.1';
$module->cron      = 0;               
$module->component = 'mod_twitter'; 
$module->maturity  = MATURITY_STABLE;
