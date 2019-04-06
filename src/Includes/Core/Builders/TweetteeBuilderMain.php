<?php

namespace Wladweb\Tweettee\Includes\Core\Builders;

use Wladweb\Tweettee\Includes\Core\Exceptions\TweetteePublicException;
use Wladweb\Tweettee\Includes\Core\Log\Logger;

/**
 * Build main block in home page if enabled
 */
class TweetteeBuilderMain extends TweetteeBuilder
{
    const PREFIX = 'm_';

    /**
     * Number of position Tweetee block in posts sequence on home page
     * @var int 
     */
    private $position = 1;

    public function __construct()
    {
        parent::__construct();
        $this->position = $this->options['m_position'];
    }

    /**
     * Check if main block enabled
     * @return boolean
     */
    public function have_main_block()
    {
        if (is_null($this->options['show_main_page_settings'])) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Build html with recieved data
     * @return string
     */
    private function getTweetteeContent()
    {
        try {
            $data = $this->get_tweetts();
        } catch (TweetteePublicException $e) {
            Logger::handle($e);
            return '';
        }

        $noindex_start = $noindex_end = '';

        if (!is_null($this->options['m_noindex'])) {
            $noindex_start = '<!--noindex-->';
            $noindex_end = '<!--/noindex-->';
        }

        $tweettee_content = $noindex_start . "<div id='tweettee_main_content'>";

        foreach ($data as $tweet) {

            $tweettee_content .= "<div class='tweettee_block' style='width: 23%'>";

            if (is_null($this->options['m_only_text'])) {
                $tweettee_content .= sprintf(
                        '<div class="tweettee-block-header"><img src="%s"><span>%s</span></div>'
                        . '<div class="tweettee-block-body">%s</div>'
                        . '<div class="tweettee-block-footer">'
                        . '<span class="tweettee-block-date">%s</span>'
                        . '<span class="tweettee-block-link">%s</span>'
                        . '</div>',
                        $tweet->profile_image_url,
                        $this->build_link('https://twitter.com/' . $tweet->screen_name, '@' . $tweet->screen_name),
                        $tweet->text,
                        $this->get_correct_time($tweet->created_at),
                        $this->build_link('https://twitter.com/post_post_/status/' . $tweet->id, 'Link')
                );
            } else {
                $tweettee_content .= sprintf(
                        '<div class="tweettee-block-body">%s</div>',
                        $tweet->text
                );
            }
            $tweettee_content .= "</div>";
        }

        $tweettee_content .= "</div>" . $noindex_end;

        return $tweettee_content;
    }

    /**
     * Set main block position & request his html content 
     * @staticvar int $nr
     */
    public function draw_tweettee()
    {
        static $nr = 0;
        if ($this->position === ++$nr) {
            echo $this->getTweetteeContent();
        }
    }

    /**
     * Remove action after main loop finished
     */
    public function erase_tweettee()
    {
        remove_action('the_post', [$this, 'draw_tweettee']);
    }

    /**
     * Return prefix
     * @return string
     */
    protected static function who()
    {
        return self::PREFIX;
    }

}
