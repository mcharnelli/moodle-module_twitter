<?php
/**
 * The main twitter configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod
 * @subpackage twitter
 * @copyright  2012 LINTI, Maria Emilia Charnelli <mcharnelli@linti.unlp.edu.ar>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once('OAuth.php');
require_once('twitteroauth.php');
/**
 * Module instance settings form
 */
class mod_twitter_mod_form extends moodleform_mod {

  public $tok;
    private $access_token;
    private $access_token_secret;
    /**
     * Defines forms elements
     */
    public function definition() {

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
        //$mform->addElement('htmleditor', 'intro', get_string('twitterintro', 'twitter'));
        //$mform->setType('intro', PARAM_RAW);
        //$mform->addRule('intro', get_string('required'), 'required', null, 'client');
        
        $this->add_intro_editor(true, get_string('twitterintro', 'twitter'));

        if (!empty($_GET)) {
            $oauth = new TwitterOAuth($consumer_key, $consumer_secret);
            $request_token_info = $oauth->getRequestToken(NULL);

            if(empty($request_token_info)) {
                $mform->addElement('html', '<span class="error">'. get_string('errormessage', 'twitter') .'. <br /> '. get_string('reloadmessage', 'twitter') .'.');
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
            
        $mform->addElement('text', 'pin', get_string('pintitle', 'twitter'). '<br><a href="'. $direction .'" target="_blank">'. get_string('pin', 'twitter') .'</a>');
        $mform->addRule('pin', null, 'required', null, 'client');
        $mform->addHelpButton('pin','pinhelpmessage', 'twitter');
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
            
                $access_token_info = $oauth->getAccessToken($pinFromTwitter);

                if (!(array_key_exists( 'oauth_token' , $access_token_info ))) {
                    $errors['pin']=get_string('pinerrormessage', 'twitter');
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
