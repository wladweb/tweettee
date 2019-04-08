<?php

namespace Wladweb\Tweettee\Includes\Core;

use Wladweb\Tweettee\Includes\Core\TweetteeSettings;
use Wladweb\Tweettee\Includes\Core\Log\Logger;

/**
 * Caching twitts
 */
class TweetteeCache
{
    /**
     * Table
     */
    const TABLE = 'tweettee_cache';
    
    /**
     * Instance of self
     * @var TweetteeCache 
     */
    private static $instance;
    
    /**
     * Must to show twitts from cache
     * @var boolean 
     */
    private $show_time = false;
    
    /**
     * Must to write twitts into cache
     * @var boolean
     */
    private $write_time = false;
    
    /**
     * Cache must be updated
     * @var boolean
     */
    private $update_time = false;
    
    /**
     * Wordpress db object
     * @var wpdb 
     */
    private $wpdb;
    
    /**
     * Plugin settings object
     * @var TweetteeSettings 
     */
    private $settings;
    
    /**
     * is cache enabled now
     * @var string|boolean
     */
    private $cache_enabled;
    
    /**
     * Time interval cache will updated in
     * @var string|boolean 
     */
    private $cache_interval;
    
    /**
     * Last cache state
     * @var string|boolean
     */
    private $cache_previous_state;
    
    /**
     * Time mark of begin cache countdown
     * @var int
     */
    private $cache_begin_timestamp;
    
    /**
     * Cache table name with wp prefix
     * @var string
     */
    private $table_name;
    
    /**
     * Mark who's calling  cache object
     * @var string
     */
    private $prefix;
    
    private $special_behavior = false;
    private $mark = '';

    private function __construct(TweetteeSettings $settings)
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->get_blog_prefix() . self::TABLE;
        $this->settings = $settings;
        $this->setup();
    }

    private function __clone()
    {
        //
    }

    private function __wakeup()
    {
        //
    }
    
    /**
     * Determines current state of cache settings & set his further behavior
     */
    public function setup()
    {
        $cache_opt = $this->settings->getOption(['cache_enabled', 'cache_interval', 'cache_previous_state', 'cache_begin_timestamp']);
        $this->cache_enabled = $cache_opt['cache_enabled'];
        $this->cache_interval = $cache_opt['cache_interval'];
        $this->cache_previous_state = $cache_opt['cache_previous_state'];
        $this->cache_begin_timestamp = $cache_opt['cache_begin_timestamp'];

        //state was changed
        if ($this->cache_enabled !== $this->cache_previous_state) {

            if ($this->cache_enabled === 'checked') {
                $this->cacheWasTurnedOn();
            } elseif ($this->cache_enabled === null) {
                $this->cacheWasTurnedOff();
            }
        } elseif ($this->cache_enabled === 'checked') { //state wasnt changed and cache is On
            
            $this->show_time = true;
            
            if (((int)$this->cache_begin_timestamp + $this->getTimestamp()) < \time()){
                $this->cacheMustBeUpdated();
            }
        }
    }
    
    /**
     * Converts cache interval setting into timestamp format
     * @return int
     */
    private function getTimestamp()
    {
        list($hours, $minutes) = explode(':', $this->cache_interval);
        return ((int)$hours * 3600) + ((int)$minutes * 60);
    }
    
    public function setSpecialBehavior($mark)
    {
        $this->mark = $mark;
        $this->special_behavior = true;
    }
    
    /**
     * Informs cache object who called him 
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }
    
    /**
     * Like trigger 'Cache On' handler
     */
    private function cacheWasTurnedOn()
    {
        $this->settings->setOption(['cache_previous_state' => $this->cache_enabled, 'cache_begin_timestamp' => \time()]);
        $this->write_time = true;
    }
    
    /**
     * Like trigger 'Cache Off' handler
     */
    private function cacheWasTurnedOff()
    {
        $this->settings->setOption(['cache_previous_state' => $this->cache_enabled, 'cache_begin_timestamp' => null]);
        $this->clearTable();
    }
    
    /**
     * Like trigger 'Cache Update' handler
     */
    private function cacheMustBeUpdated()
    {
        echo '1', '<hr>';
        $this->write_time = true;
        $this->update_time = true;
        $this->show_time = false;
        $this->settings->setOption('cache_begin_timestamp', \time());
    }
    
    private function deleteOldCache()
    {
        if ($this->special_behavior){
            $this->specialDelete();
        } else {
            $this->regularDelete();
        }
    }
    
    public function regularDelete()
    {
        $this->deleteRows('prefix', $this->prefix);
    }
    
    public function specialDelete()
    {
        $this->deleteRows('mark', $this->mark);
    }
    
    private function deleteRows($sign, $value)
    {
        $sql = "DELETE FROM $this->table_name WHERE $sign = '%s'";
        $response = $this->wpdb->query($this->wpdb->prepare($sql, $value));
    }
    
    /**
     * Singleton stuff
     * @param TweetteeSettings $settings
     * @return TweetteeCache
     */
    public static function getCache(TweetteeSettings $settings)
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self($settings);
        }

        return self::$instance;
    }
    
    /**
     * Public indicator that data will be getting from cache or not
     * @return boolean
     */
    public function canReadFromCache()
    {
        return $this->show_time;
    }

    /**
     * Can write into cache?
     * @return boolean
     */
    public function canWriteIntoCache()
    {
        return $this->write_time;
    }
    
    /**
     * Just select twitts from cache table
     * @param string $prefix
     * @return array|null
     */
    public function get($prefix)
    {
        $sql = "SELECT id, prefix, profile_image_url, screen_name, text, created_at FROM {$this->table_name} WHERE prefix = '{$prefix}'";
        $data = $this->wpdb->get_results($sql, ARRAY_A);
        return $data;
    }
    
    /**
     * Insert twitts into cache table
     * @param array $tweets
     */
    public function insert(array $tweets)
    {
        if ($this->update_time){
            $this->deleteOldCache();
        }
        
        $mark = 'default';
        
        if ($this->special_behavior){
            $mark = $this->mark;
        }
        
        $sql = "INSERT INTO {$this->table_name} (id, prefix, mark, profile_image_url, screen_name, text, created_at) VALUES ";
        $values = [];
        $placeholders = [];

        foreach ($tweets as $tweet) {
            array_push($values, $tweet->id, $this->prefix, $mark, $tweet->profile_image_url, $tweet->screen_name, $tweet->text, $tweet->created_at);
            array_push($placeholders, "('%s', '%s', '%s', '%s', '%s', '%s', '%s')");
        }

        $sql .= implode(', ', $placeholders);

        $response = $this->wpdb->query($this->wpdb->prepare($sql, $values));

        if ($response === false) {
            Logger::write(__CLASS__ . ' Cant write into database.');
        }
    }
    
    /**
     * Clear cache table
     */
    private function clearTable()
    {
        $sql = "TRUNCATE TABLE {$this->table_name}";
        $this->wpdb->query($sql);
    }
    
    /**
     * Create cache table
     * @global wpdb $wpdb
     */
    public static function createTable()
    {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $table_name = $wpdb->get_blog_prefix() . self::TABLE;
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

        $sql = "CREATE TABLE {$table_name} (
	id varchar(30) NOT NULL default '',
	prefix varchar(2) NOT NULL default '',
	mark varchar(255) NOT NULL default 'default',
	profile_image_url varchar(255) NOT NULL default '',
	screen_name varchar(255) NOT NULL default '',
	text text NOT NULL default '',
	created_at varchar(255) NOT NULL default ''
	)
        {$charset_collate};";

        dbDelta($sql);
    }
    
    /**
     * Delete cache table
     * @global wpdb $wpdb
     */
    public static function deleteTable()
    {
        global $wpdb;
        $table_name = $wpdb->get_blog_prefix() . self::TABLE;
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
    }

}
