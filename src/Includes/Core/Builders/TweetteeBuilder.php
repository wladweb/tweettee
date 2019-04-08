<?php

namespace Wladweb\Tweettee\Includes\Core\Builders;

use Wladweb\Tweettee\Includes\Core\TweetteeApp;
use Wladweb\Tweettee\Includes\Core\OAuth;
use Wladweb\Tweettee\Includes\Core\TweetteeCache;
use Wladweb\Tweettee\Includes\Core\Exceptions\TweetteePublicException;
use Wladweb\Tweettee\Includes\Core\Tweet;

/**
 * Parent class with common methods for 2 builders
 */
abstract class TweetteeBuilder
{

    /**
     *
     * @var string Plugin name
     */
    protected $plugin_name;

    /**
     * Plugin version
     * @var string 
     */
    protected $version;

    /**
     * Object wrapper for TwitterOAuth
     * @var OAuth 
     */
    protected $twitteroauth = null;

    /**
     * Cache object
     * @var TweetteeCache
     */
    protected $cache = null;

    /**
     * Plugin options
     * @var array
     */
    protected $options;

    /**
     * Options object
     * @var TweetteeSettings 
     */
    protected $settings;

    /**
     * Prefix of child builders
     * @var string 
     */
    protected $prefix;

    /**
     * GMT offset
     * @var string
     */
    protected $gmt_offset;

    public function __construct()
    {
        $this->prefix = static::who();
        $this->settings = TweetteeApp::$tweettee_settings;
        $this->plugin_name = TweetteeApp::get_plugin_name();
        $this->version = TweetteeApp::get_version();
        $this->options = $this->settings->getOptions();
        $this->gmt_offset = get_option('gmt_offset');
    }

    abstract public function draw_tweettee();

    abstract protected static function who();

    /**
     * Set oauth & cache singletones
     */
    public function prepare()
    {
        $this->cache = TweetteeCache::getCache($this->settings);
        $this->twitteroauth = OAuth::getOauth($this->settings->getOptions(['consumer_key', 'consumer_secret', 'oauth_token', 'oauth_token_secret']));
    }

    /**
     * Recieve array of twits
     * @return array
     */
    protected function get_tweetts()
    {
        $content_type = (int)$this->options['w_content_type'];
        $search_type = (int)$this->options['w_search_type'];
        $this->cache->setPrefix($this->prefix);
        
        if (($content_type === 5) && (($search_type === 1) || ($search_type === 2))){
            
            $this->cache->setSpecialBehavior($this->get_search_value($search_type));
        }
        
        if ($this->cache->canReadFromCache()) {
            
            $data = $this->fromCache();
            
        } else {
            
            $data = $this->fromTwitter();

            if ($this->cache->canWriteIntoCache()) {
                $this->cache->insert($this->toObject($data));
            }
        }
        return $this->toObject($data);
    }

    private function fromCache()
    {
        return $this->cache->get($this->prefix);
    }

    private function fromTwitter()
    {
        $mode = (int) $this->options[$this->prefix . 'content_type'];
        switch ($mode) {
            case 1:
                $params = $this->build_params($mode);
                $data = $this->get_data('statuses/user_timeline', $params);
                break;
            case 2:
                $params = $this->build_params($mode);
                $data = $this->get_data('statuses/home_timeline', $params);
                break;
            case 3:
                $params = $this->build_params($mode);
                $data = $this->get_data('statuses/mentions_timeline', $params);
                break;
            case 4:
                $params = $this->build_params($mode);
                $data = $this->get_data('statuses/user_timeline', $params);
                break;
            case 5:
                $params = $this->build_params($mode);
                $data = $this->get_data('search/tweets', $params);
                break;
            default:
                $params = $this->build_params($mode);
                $data = $this->get_data('statuses/user_timeline', $params);
        }

        return $data;
    }

    /**
     * Make parameters array
     * @param int $mode
     * @return array
     */
    private function build_params($mode)
    {

        $params = [];
        $params['count'] = $this->options[$this->prefix . 'count'];

        switch ($mode) {
            case 1:
                $params['screen_name'] = $this->options['screen_name'];
                break;
            case 2:
                break;
            case 3:
                break;
            case 4:
                $params['screen_name'] = $this->options[$this->prefix . 'another_timeline'];
                break;
            case 5:
                $params['q'] = $this->get_search_value((int) $this->options[$this->prefix . 'search_type']);
                $params['lang'] = $this->options[$this->prefix . 'language'];
                $params['result_type'] = $this->options[$this->prefix . 'result_type'];
                break;
            default:
                $params['screen_name'] = $this->options['screen_name'];
        }
        return $params;
    }

    /**
     * Prepare search string
     * 
     * @global WP_Post $post
     * @param int $search_mode
     * @return string
     * @throws TweetteePublicException
     */
    private function get_search_value($search_mode, $encode = true)
    {
        global $post;
        $id = $post->ID;

        switch ($search_mode) {
            case 1:
                $tags_arr = wp_get_post_tags($id, array('fields' => 'names'));
                if (empty($tags_arr)) {
                    throw new TweetteePublicException("The post $post->post_name havent tags", 1002);
                }

                $search_word = $tags_arr[0];
                break;
            case 2:
                $cat_arr = get_the_category($id);

                if (empty($cat_arr)) {
                    throw new TweetteePublicException("The post $post->post_name havent category", 1003);
                }
                $search_word = $cat_arr[0]->name;
                break;
            case 3:
                break;
            case 4:
                $search_word = $this->options[$this->prefix . 'search_word'] ? $this->options[$this->prefix . 'search_word'] : 'twitter';
                break;
            default:
                $tags_arr = wp_get_post_tags($id, array('fields' => 'names'));
                if (empty($tags_arr)) {
                    throw new TweetteePublicException("The post $post->post_name havent tags", 1002);
                }

                $search_word = $tags_arr[0];
        }
        
        if ($encode){
            return urlencode($search_word);
        } else {
            return $search_word;
        }
        
    }

    /**
     * Make request & check response
     * 
     * @param string $request_string
     * @param array $params
     * @return array
     * @throws TweetteePublicException
     */
    private function get_data($request_string, array $params)
    {
        $data = $this->twitteroauth->get($request_string, $params);

        if (is_object($data) && !empty($data->errors[0]->message)) {
            throw new TweetteePublicException("Response object has errors. " . $data->errors[0]->message, 1005);
        }

        if (is_object($data) && isset($data->statuses)) {
            if (!empty($data->statuses)) {
                $this->prepareData($data->statuses);
                return $data->statuses;
            } else {
                throw new TweetteePublicException("On request " . urldecode($params['q']) . " found nothing", 1004);
            }
        }
        $this->prepareData($data);
        return $data;
    }

    /**
     * Make link
     * 
     * @param string $url
     * @param string $text
     * @return string
     */
    protected function build_link($url, $text)
    {
        $rel_nofollow = '';

        if ($this->options[$this->prefix . 'rel_nofollow']) {
            $rel_nofollow = ' rel="nofollow" ';
        }

        $link = "<a href=\"{$url}\"{$rel_nofollow} target=\"_blank\">{$text}</a>";
        return $link;
    }

    /**
     * Prepare twit text
     * @param array $data
     */
    private function prepareData(array &$data)
    {
        foreach ($data as $twit) {

            $hashtags = [];
            $hashtags_replace = [];
            foreach ($twit->entities->hashtags as $hashtag) {
                $hashtags[] = '#' . $hashtag->text;
                $hashtags_replace[] = $this->build_link('https://twitter.com/hashtag/' . urlencode($hashtag->text) . '/?src=hash', '#' . $hashtag->text);
            }
            $twit->text = str_replace($hashtags, $hashtags_replace, $twit->text);

            $links = [];
            $links_replace = [];
            foreach ($twit->entities->urls as $url_obj) {
                $links[] = $url_obj->url;
                $links_replace[] = $this->build_link($url_obj->url, $url_obj->url);
            }
            $twit->text = str_replace($links, $links_replace, $twit->text);

            $users = [];
            $users_replace = [];
            foreach ($twit->entities->user_mentions as $url_obj) {
                $users[] = $url_obj->screen_name;
                $users_replace[] = $this->build_link('https://twitter.com/' . $url_obj->screen_name, $url_obj->screen_name);
            }
            $twit->text = str_replace($users, $users_replace, $twit->text);
        }
    }

    private function toObject(array $data)
    {
        $result = [];

        foreach ($data as $item) {
            $tweet = new Tweet;
            $tweet->set($item);
            array_push($result, $tweet);
        }

        return $result;
    }

    /**
     * Set GMT offset
     * 
     * @param string $time_string
     * @return stirng
     */
    protected function get_correct_time($time_string)
    {

        $time = new \DateTime($time_string);

        substr($this->gmt_offset, 0, 1) === '-' ? $modify = $this->gmt_offset : $modify = '+' . $this->gmt_offset;

        $time->modify($modify . ' hour');

        return $time->format('d M Y H:i:s');
    }

}
