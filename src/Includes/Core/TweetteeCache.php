<?php

namespace Wladweb\Tweettee\Includes\Core;

use Wladweb\Tweettee\Includes\Core\TweetteeSettings;
use Wladweb\Tweettee\Includes\Core\Log\Logger;

/**
 * Caching twitts list
 */
class TweetteeCache
{

    const TABLE = 'tweettee_cache';

    private static $instance;
    private $show_time = false;
    private $write_time = false;
    private $wpdb;
    private $settings;
    private $cache_enabled;
    private $cache_interval;
    private $cache_previous_state;
    private $cache_begin_timestamp;
    private $table_name;
    private $prefix;

    private function __construct(TweetteeSettings $settings)
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->get_blog_prefix() . self::TABLE;
        $this->settings = $settings;
        $this->process();
    }

    private function __clone()
    {
        
    }

    private function __wakeup()
    {
        
    }

    public function process()
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
        } elseif ($this->cache_enabled === 'checked') {
            
            $this->show_time = true;
            
            if (((int)$this->cache_begin_timestamp + $this->getTimestamp()) < \time()){
                $this->cacheMustBeUpdated();
            }
        }
    }

    private function getTimestamp()
    {
        list($hours, $minutes) = explode(':', $this->cache_interval);
        return ((int)$hours * 3600) + ((int)$minutes * 60);
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    private function cacheWasTurnedOn()
    {
        $this->settings->setOption(['cache_previous_state' => $this->cache_enabled, 'cache_begin_timestamp' => \time()]);
        $this->write_time = true;
        $this->show_time = true;
    }

    private function cacheWasTurnedOff()
    {
        $this->settings->setOption(['cache_previous_state' => $this->cache_enabled, 'cache_begin_timestamp' => null]);
        $this->clearTable();
    }

    private function cacheMustBeUpdated()
    {
        $this->clearTable();
        $this->write_time = true;
        $this->settings->setOption('cache_begin_timestamp', \time());
    }

    public static function getCache(TweetteeSettings $settings)
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self($settings);
        }

        return self::$instance;
    }

    public function isItFromCache()
    {
        return $this->show_time;
    }

    /**
     * Is it need to write into cache?  hmmm...
     * @return boolean
     */
    public function isItNeedToWriteIntoCache()
    {
        return $this->write_time;
    }

    public function get($prefix)
    {
        $sql = "SELECT id, prefix, profile_image_url, screen_name, text, created_at FROM {$this->table_name} WHERE prefix = '{$prefix}'";
        $data = $this->wpdb->get_results($sql, ARRAY_A);
        return $data;
    }

    public function insert(array $tweets)
    {
        $sql = "INSERT INTO {$this->table_name} (id, prefix, profile_image_url, screen_name, text, created_at) VALUES ";
        $values = [];
        $placeholders = [];

        foreach ($tweets as $tweet) {
            array_push($values, $tweet->id, $this->prefix, $tweet->profile_image_url, $tweet->screen_name, $tweet->text, $tweet->created_at);
            array_push($placeholders, "('%s', '%s', '%s', '%s', '%s', '%s')");
        }

        $sql .= implode(', ', $placeholders);

        //$this->clearTable();
        $response = $this->wpdb->query($this->wpdb->prepare($sql, $values));

        if ($response === false) {
            Logger::write(__CLASS__ . ' Cant write into database.');
        }
    }

    private function clearTable()
    {
        $sql = "TRUNCATE TABLE {$this->table_name}";
        $this->wpdb->query($sql);
    }

    public static function createTable()
    {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $table_name = $wpdb->get_blog_prefix() . self::TABLE;
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

        $sql = "CREATE TABLE {$table_name} (
	id varchar(30) NOT NULL default '',
	prefix varchar(2) NOT NULL default '',
	profile_image_url varchar(255) NOT NULL default '',
	screen_name varchar(255) NOT NULL default '',
	text text NOT NULL default '',
	created_at varchar(255) NOT NULL default ''
	)
        {$charset_collate};";

        dbDelta($sql);
    }

    public static function deleteTable()
    {
        global $wpdb;
        $table_name = $wpdb->get_blog_prefix() . self::TABLE;
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
    }

}
