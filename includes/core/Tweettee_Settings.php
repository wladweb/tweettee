<?php

namespace Tweettee\Includes\Core;

use Tweettee\Includes\Oauth\TwitterOAuth;
use Tweettee\Includes\Oauth\TwitterOAuthException;

class Tweettee_Settings{
    
    private $message = '';
    private $option = array();
    private $callback;
    private $language;
    
    public function __construct(){
        
        $this->option = get_option('tweettee');
        $this->callback = get_home_url() . $_SERVER['REQUEST_URI'];
        $this->set_language();
        
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
    
    private function set_language(){
        $languages = array(
            'all' => 'Любой',
            'ab' => 'Abkhazian',
            'ae' => 'Avestan',
            'af' => 'Afrikaans',
            'ak' => 'Akan',
            'am' => 'Amharic',
            'an' => 'Aragonese',
            'ar' => 'Arabic',
            'as' => 'Assamese',
            'av' => 'Avaric',
            'ay' => 'Aymara',
            'az' => 'Azerbaijani',
            'ba' => 'Bashkir',
            'be' => 'Belarusian',
            'bg' => 'Bulgarian',
            'bh' => 'Bihari',
            'bi' => 'Bislama',
            'bm' => 'Bambara',
            'bn' => 'Bengali',
            'bo' => 'Tibetan',
            'br' => 'Breton',
            'bs' => 'Bosnian',
            'ca' => 'Catalan; Valencian',
            'ce' => 'Chechen',
            'ch' => 'Chamorro',
            'co' => 'Corsican',
            'cr' => 'Cree',
            'cs' => 'Czech',
            'cv' => 'Chuvash',
            'cy' => 'Welsh',
            'da' => 'Danish',
            'de' => 'German',
            'en' => 'English',
            'eo' => 'Esperanto',
            'es' => 'Spanish; Castilian',
            'et' => 'Estonian',
            'eu' => 'Basque',
            'fa' => 'Persian',
            'ff' => 'Fulah',
            'fi' => 'Finnish',
            'fj' => 'Fijian',
            'fo' => 'Faroese',
            'fr' => 'French',
            'fy' => 'Western Frisian',
            'ga' => 'Irish',
            'gl' => 'Galician',
            'gn' => 'Guarani',
            'gu' => 'Gujarati',
            'gv' => 'Manx',
            'ha' => 'Hausa',
            'he' => 'Hebrew',
            'hi' => 'Hindi',
            'ho' => 'Hiri Motu',
            'hr' => 'Croatian',
            'ht' => 'Haitian; Haitian Creole',
            'hu' => 'Hungarian',
            'hy' => 'Armenian',
            'hz' => 'Herero',
            'id' => 'Indonesian',
            'ie' => 'Interlingue; Occidental',
            'ig' => 'Igbo',
            'ii' => 'Sichuan Yi; Nuosu',
            'ik' => 'Inupiaq',
            'io' => 'Ido',
            'is' => 'Icelandic',
            'it' => 'Italian',
            'iu' => 'Inuktitut',
            'ja' => 'Japanese',
            'jv' => 'Javanese',
            'ka' => 'Georgian',
            'kg' => 'Kongo',
            'ki' => 'Kikuyu; Gikuyu',
            'kj' => 'Kuanyama; Kwanyama',
            'kk' => 'Kazakh',
            'kl' => 'Kalaallisut; Greenlandic',
            'km' => 'Central Khmer',
            'kn' => 'Kannada',
            'ko' => 'Korean',
            'kr' => 'Kanuri',
            'ks' => 'Kashmiri',
            'ku' => 'Kurdish',
            'kv' => 'Komi',
            'kw' => 'Cornish',
            'ky' => 'Kirghiz; Kyrgyz',
            'la' => 'Latin',
            'lb' => 'Luxembourgish; Letzeburgesch',
            'lg' => 'Ganda',
            'li' => 'Limburgan; Limburger; Limburgish',
            'ln' => 'Lingala',
            'lo' => 'Lao',
            'lt' => 'Lithuanian',
            'lu' => 'Luba-Katanga',
            'lv' => 'Latvian',
            'mg' => 'Malagasy',
            'mh' => 'Marshallese',
            'mi' => 'Maori',
            'mk' => 'Macedonian',
            'ml' => 'Malayalam',
            'mn' => 'Mongolian',
            'mr' => 'Marathi',
            'ms' => 'Malay',
            'mt' => 'Maltese',
            'my' => 'Burmese',
            'na' => 'Nauru',
            'ne' => 'Nepali',
            'ng' => 'Ndonga',
            'nl' => 'Dutch; Flemish',
            'no' => 'Norwegian',
            'oj' => 'Ojibwa',
            'om' => 'Oromo',
            'or' => 'Oriya',
            'os' => 'Ossetian; Ossetic',
            'pa' => 'Panjabi; Punjabi',
            'pi' => 'Pali',
            'pl' => 'Polish',
            'ps' => 'Pushto; Pashto',
            'pt' => 'Portuguese',
            'qu' => 'Quechua',
            'rm' => 'Romansh',
            'rn' => 'Rundi',
            'ro' => 'Romanian; Moldavian; Moldovan',
            'ru' => 'Russian',
            'rw' => 'Kinyarwanda',
            'sa' => 'Sanskrit',
            'sc' => 'Sardinian',
            'sd' => 'Sindhi',
            'se' => 'Northern Sami',
            'sg' => 'Sango',
            'si' => 'Sinhala; Sinhalese',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'sm' => 'Samoan',
            'sn' => 'Shona',
            'so' => 'Somali',
            'sq' => 'Albanian',
            'sr' => 'Serbian',
            'ss' => 'Swati',
            'su' => 'Sundanese',
            'sv' => 'Swedish',
            'sw' => 'Swahili',
            'ta' => 'Tamil',
            'te' => 'Telugu',
            'tg' => 'Tajik',
            'th' => 'Thai',
            'ti' => 'Tigrinya',
            'tk' => 'Turkmen',
            'tl' => 'Tagalog',
            'tn' => 'Tswana',
            'to' => 'Tonga (Tonga Islands)',
            'tr' => 'Turkish',
            'ts' => 'Tsonga',
            'tt' => 'Tatar',
            'tw' => 'Twi',
            'ty' => 'Tahitian',
            'ug' => 'Uighur; Uyghur',
            'uk' => 'Ukrainian',
            'ur' => 'Urdu',
            'uz' => 'Uzbek',
            've' => 'Venda',
            'vi' => 'Vietnamese',
            'yi' => 'Yiddish',
            'yo' => 'Yoruba',
            'za' => 'Zhuang; Chuang',
            'zh' => 'Chinese',
            'zu' => 'Zulu'
        );
        
        $this->language = $languages;
    }
    
    private function check_update_option(){// Переделать
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tweettee_change_settings'])){
            $null_values = array(
                'show_main_page_settings',
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
    
    private function clear_int($str){
        return abs((int)$str);
    }
    
    public function draw_settings_page(){
        require_once '/tpl/tweettee_admin_template.php';
    }
}

