<?php

namespace Tweettee\Includes\Core;

use Tweettee\Includes\Oauth\TwitterOAuth;
use Tweettee\Includes\Oauth\TwitterOAuthException;

class Tweettee_Settings{
    
    private $message = '';
    private $option = array();
    private $callback;
    
    public function __construct(){
        
        $this->option = get_option('tweettee');
        $this->callback = get_home_url() . $_SERVER['REQUEST_URI'];
        
        if (is_null($this->option['access_token']) || is_null($this->option['access_secret'])){
            try{
                $this->twitter_access();
            }catch(Exception $e){
                $this->message = $e->getMessage();
                session_destroy();
            }
        }
        
        $this->get_user_info();
        $this->check_update_option();
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
            );
            
            $this->change_option_value($value);
            
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
            
            $connection = new TwitterOAuth($this->option['consumer_key'], $this->option['consumer_secret'], $oauth_token, $oauth_token_secret);
            try{
                $access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
            }catch(TwitterOAuthException $e){
                throw new Exception($e->getMessage());
            }
            $value = array(
                'access_token' => $access_token['oauth_token'],
                'access_secret' => $access_token['oauth_token_secret'],
                'user_id' => $access_token['user_id'],
                'screen_name' => $access_token['screen_name']
            );
            $this->change_option_value($value);
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
    
    private function check_update_option(){// Переделать
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tweettee_change_settings'])){
            $null_values = array(
                'show-main-page-settings',
                'w_rel_nofollow',
                'w_noindex',
                'w_only_text',
                'm_rel_nofollow',
                'm_noindex',
                'm_only_text'
            );
            $this->set_null($null_values);
            $this->change_option_value($_POST);
        }
    }
    
    private function set_null(array $in){
        foreach ($in as $item){
            if (!isset($_POST[$item])){
                $this->option[$item] = NULL;
            }
        }
    }
    
    private function change_option_value(array $input_arr){
        foreach ($input_arr as $key=>$val){
                if (!array_key_exists($key, $this->option)){
                    continue;
                }
                $this->option[$key] = $val;
            }
            update_option('tweettee', $this->option);
    }
    
    private function get_user_info(){
        if (is_null($this->option['account_info']) && !is_null($this->option['access_token']) && !is_null($this->option['access_secret'])){
            $connection = new TwitterOAuth(
                $this->option['consumer_key'], 
                $this->option['consumer_secret'], 
                $this->option['access_token'], 
                $this->option['access_secret']
            );
            
            $account_info  = $connection->get('account/verify_credentials', array('skip_status' => true));
            $this->change_option_value(array('account_info' => $account_info));
        }
    } 
    
    private function clear_str($str){
        return trim(strip_tags($str));
    }
    
    public function draw_settings_page(){
        require_once '/tpl/tweettee_admin_template.php';
    }
}

