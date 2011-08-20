TWITTER ACTIVITY MODULE

==Features==
The Twitter Activity Module publishes any Moodle's course activities in a Twitter account.

==Installation==
Create a directory called "twitter" in your "mod" directory and copy all the files for this module into the "twitter"
directory.  

If you have the patch command installed, run the script "patch" to modify modedit.php automatically.
If you don't have it you can install it or modify modedit.php manually:

1.Below this line
  require_login();

paste this line
   global $USER;

2.Below the line 265 where it says
      264.       set_coursemodule_idnumber($fromform->coursemodule, $fromform->cmidnumber);
      265.    }

paste this lines
            // Trigger course_updated event with information about this module.
            $eventdata = new object();
            $eventdata->modulename = $fromform->modulename;
            $eventdata->name       = $fromform->name;
            $eventdata->cmid       = $fromform->coursemodule;
            $eventdata->courseid   = $course->id;
            $eventdata->userid     = $USER->id;
            $eventdata->subject     = 'actualizado';
            events_trigger('course_updated', $eventdata);

3.Below the line 314 where it says
      313.       set_coursemodule_idnumber($fromform->coursemodule, $fromform->cmidnumber);
      314.    }

paste this lines
           // Trigger course_update event with information about this module.
            $eventdata = new object();
            $eventdata->modulename = $fromform->modulename;
            $eventdata->name       = $fromform->name;
            $eventdata->cmid       = $fromform->coursemodule;
            $eventdata->courseid   = $course->id;
            $eventdata->userid     = $USER->id;
            $eventdata->subject 	 = 'nuevo';
            events_trigger('course_updated', $eventdata);


Visit the admin Notifications page (admin/index.php). The module tables should get installed.

You can go to Modules > Activities in the Site Administration block. You
should find that this module has been added to the list of recognized modules.


Maintainer Contact information
Organization: LINTI
Author: María Emilia Charnelli
Mail: mcharnelli@linti.unlp.edu.ar

