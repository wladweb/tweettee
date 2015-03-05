<?php

namespace Tweettee\AdminPart;
use Abraham\TwitterOAuth;
use Abraham\TwitterOAuthException;
use Exception;



class Tweettee_Admin{
    private $plugin_name;
    private $version;
    private $message = '';
    private $callback;
    
    public function __construct($plugin_name, $version){
        session_start();
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->callback = get_home_url() . $_SERVER['REQUEST_URI'];
        //header('Location:'. $_SERVER['REQUEST_URI']);
    }
    
    public function enqueue_scripts(){
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/tweettee_admin.js', array('jquery'), $this->version, false);
    }
    
    public function enqueue_styles(){
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/tweettee_admin.css', array(), $this->version, 'all');
    }
    
    public function add_settings_page(){
        add_options_page('Tweettee Options', 'Tweettee', 'manage_options', __FILE__, array($this, 'show_settings_page'));
    }
    
    public function show_settings_page(){
        try{
            $twitter_access = $this->twitter_access();
        }catch(Exception $e){
            $value = array(
                'consumer_key' => 'Не задан',
                'consumer_secret' => 'Не задан',
                'access_token' => NULL,
                'access_secret' => NULL
            );
            update_option('tweettee', $value);
            $this->message = $e->getMessage();
            session_destroy();
        }

        $tw_opt = get_option('tweettee');

        require_once '/template/tweettee_admin_template.php';
    }
    
    private function clear_str($str){
        return trim(strip_tags($str));
    }
    
    private function twitter_access(){
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['consumer_submit']) && check_admin_referer('auth_form','oauth_nonce')){
            $consumer_key = $this->clear_str($_POST['consumer_key']);
            $consumer_secret = $this->clear_str($_POST['consumer_secret']);
            
            $connection = new TwitterOAuth($consumer_key, $consumer_secret);
            
            try{
                $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $this->callback));
            }catch(TwitterOAuthException $e){
                throw new Exception($e->getMessage());
            }
            
            $value = array(
                'consumer_key' => $consumer_key,
                'consumer_secret' => $consumer_secret,
                'access_token' => NULL,
                'access_secret' => NULL
            );
            update_option('tweettee', $value);
            
            $_SESSION['oauth_token'] = $request_token['oauth_token'];
            $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
            
            $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
            //$url = false;
            if (!$url){
                throw new Exception('Url Error!');
            }
            
            print "<script>var tweettee_oauth_authorize_url = '$url'</script>";
        }
        
        if (isset($_REQUEST['oauth_verifier']) && $_REQUEST['oauth_verifier']){
            
            if(!isset($_SESSION['oauth_token']) || !isset($_SESSION['oauth_token_secret'])){
                return;
            }
            $oauth_token = $_SESSION['oauth_token'];
            $oauth_token_secret = $_SESSION['oauth_token_secret'];
            
            unset($_SESSION['oauth_token']);
            unset($_SESSION['oauth_token_secret']);
            session_destroy();
            
            $tweettee_option = get_option('tweettee');
            
            $connection = new TwitterOAuth($tweettee_option['consumer_key'], $tweettee_option['consumer_secret'], $oauth_token, $oauth_token_secret);
            try{
                $access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
            }catch(TwitterOAuthException $e){
                throw new Exception($e->getMessage());
            }
            
            $value = array(
                'consumer_key' => $tweettee_option['consumer_key'],
                'consumer_secret' => $tweettee_option['consumer_secret'],
                'access_token' => $access_token['oauth_token'],
                'access_secret' => $access_token['oauth_token_secret'],
                'user_id' => $access_token['user_id'],
                'screen_name' => $access_token['screen_name']
            );
            update_option('tweettee', $value);
        }
    }
    private function get_admin_page_url() {
        $arr = array();
        parse_str($_SERVER['QUERY_STRING'], $arr);
        if (isset($arr['oauth_token']) || isset($arr['oauth_verifier'])){
            $page = $arr['page'];
            return get_home_url() . $_SERVER['PHP_SELF'] .'?page='. $page;
        }else{
            return false;
        }
    }
}

