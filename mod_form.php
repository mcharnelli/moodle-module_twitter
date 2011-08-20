<?php 

/**
 * This file defines the main twitter configuration form
 * It uses the standard core Moodle (>1.8) formslib. For
 * more info about them, please visit:
 *
 * http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * The form must provide support for, at least these fields:
 *   - name: text element of 64cc max
 *
 * Also, it's usual to use these fields:
 *   - intro: one htmlarea element to describe the activity
 *            (will be showed in the list of activities of
 *             twitter type (index.php) and in the header
 *             of the twitter main page (view.php).
 *   - introformat: The format used to write the contents
 *             of the intro field. It automatically defaults
 *             to HTML when the htmleditor is used and can be
 *             manually selected if the htmleditor is not used
 *             (standard formats are: MOODLE, HTML, PLAIN, MARKDOWN)
 *             See lib/weblib.php Constants and the format_text()
 *             function for more info
 */

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once ('twitteroauth.php');


class mod_twitter_mod_form extends moodleform_mod {
    
    public $tok;
    private $access_token;
    private $access_token_secret;
    function definition() {
        
        require('config.php');
        global $COURSE;
        $mform =& $this->_form;

//-------------------------------------------------------------------------------
    /// Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('general', 'form'));

    /// Adding the standard "name" field
        $mform->addElement('text', 'name', get_string('twittername', 'twitter'), array('size'=>'64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

    /// Adding the required "intro" field to hold the description of the instance
        $mform->addElement('htmleditor', 'intro', get_string('twitterintro', 'twitter'));
        $mform->setType('intro', PARAM_RAW);
        $mform->addRule('intro', get_string('required'), 'required', null, 'client');
        $mform->setHelpButton('intro', array('writing', 'richtext'), false, 'editorhelpbutton');

        if (!empty($_GET)) {
            $oauth = new TwitterOAuth($consumer_key, $consumer_secret);
            $request_token_info = $oauth->getRequestToken();

            if(empty($request_token_info)) {
                $mform->addElement('html', '<span class="error">Se ha producido un error en la comunicación con Twitter. <br /> Recargue la página y vuelva a intentarlo</div>.');
                $direction='';
            } else {
                $request_token = $request_token_info['oauth_token'];
                $request_token_secret = $request_token_info['oauth_token_secret'];
        
                $_SESSION['request_token'] = $request_token;
                $_SESSION['request_token_secret'] = $request_token_secret;

                $direction = $oauth->getAuthorizeURL($request_token);
            }
        } else  {
            $direction='';
        }
            
        $mform->addElement('text', 'pin', '<a href="'. $direction .'" target="_blank">Copie el PIN de este link</a>');
        $mform->addRule('pin', null, 'required', null, 'client');
        
        $this->standard_coursemodule_elements();
        $this->add_action_buttons();

    }

    function validation($data, $files){
        $errors = parent::validation($data, $files);
        
        if ($errors == NULL ) {
            if (!(empty($data))) {
                include('config.php');
            
                $oauth = new TwitterOAuth($consumer_key, $consumer_secret,$_SESSION['request_token'], $_SESSION['request_token_secret']);
                
                $pinFromTwitter = $data['pin'];
            
                $access_token_info = $oauth->getAccessToken(NULL, $pinFromTwitter);

                if (!(array_key_exists( 'oauth_token' , $access_token_info ))) {
                    $errors['pin']='Ha introducido un PIN inválido';
                } else {
                    $this->access_token = $access_token_info['oauth_token'];
                    $this->access_token_secret = $access_token_info['oauth_token_secret'];        
            
                    unset($_SESSION['request_token']);
                    unset($_SESSION['request_token_secret']);
                }
            }
        }
        return $errors;
    }

    function get_data(){
        $data = parent::get_data();
        if ($data != NULL ) {
            $data->access_token=$this->access_token; 
            $data->access_token_secret=$this->access_token_secret;    
        } 
        return $data;
    }


}

?>
 