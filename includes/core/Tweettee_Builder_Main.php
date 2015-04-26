<?php

namespace Tweettee\Includes\Core;

class Tweettee_Builder_Main extends Tweettee_Builder{
    
    private $wp_q;
    private $position = 0;
    
    public function __construct($wp_q) {
        parent::__construct();
        if (!($wp_q instanceof \WP_Query)){
            throw new Tweettee_Exception('Не получен объект WP_Query');
        }
        $this->wp_q = $wp_q;
    }
    
    public function have_main_block(){
        if (is_null($this->option['show_main_page_settings'])){
            return FALSE;
        }else{
            return TRUE;
        }
    } 
    
    private function set_tweettee_post($post){
        $this->position = $this->clear_int($this->option['m_after_which_post']);
        
        if ($this->position > count($this->wp_q->posts)){
            $this->position = count($this->wp_q->posts);
        }
        
        array_splice($this->wp_q->posts, $this->position, 0, array($post));
    }

    private function create_tweettee_block(){
        
        $tw_post = new \WP_Post($this->wp_q->post);
        $tw_post->ID = 0;
        $tw_post->post_name = 'tweettee';
        $tw_post->post_title = 'tweettee';
        $tw_post->post_type = 'page';
        $tw_post->comment_status = 'hold';
        $tw_post->guid = '';
        $tw_post->post_content = $this->get_tweettee_content();
        
        return $tw_post;
    }
    
    private function get_tweettee_content(){
        
        $tweettee_content = "<div id='tweettee_main_content'>";
        
        try{
            $data = $this->get_tweetts();
            
        } catch (Tweettee_Exception $tw_e){
            
            return $tw_e->getMessage();
        }
//var_dump($data);
        foreach ($data as $k => $v){
            
            $tweettee_content .= "<div class='tweettee_block' style='width: 23%'>";
            
            if (is_null($this->option['m_only_text'])){
                $tweettee_content .= sprintf(
                          '<div class="tweettee-block-header"><img src="%s"><p>%s</p></div>'
                        . '<div class="tweettee-block-body">%s</div>'
                        . '<div class="tweettee-block-footer">'
                            . '<p class="tweettee-block-date">%s</p>'
                            . '<p class="tweettee-block-link">%s</p>'
                        . '</div>',
                    $v->user->profile_image_url,
                    $this->build_link('https://twitter.com/' . $v->user->screen_name, '@' . $v->user->screen_name),
                    $v->text,
                    $this->get_correct_time($v->created_at),
                    $this->build_link('https://twitter.com/post_post_/status/' . $v->id, 'Link')
                    );
                
            }else{
                $tweettee_content .= sprintf(
                    '<div class="tweettee-block-body">%s</div>',
                    $v->text
                    );
            }
            $tweettee_content .= "</div>";
        }
        
        $tweettee_content .= "</div>";
        
        return $tweettee_content;
    }
    
    public function draw_tweettee() {
        $this->wp_q->post_count = ++$this->wp_q->post_count;
        
        $tw_post = $this->create_tweettee_block();
        
        $this->set_tweettee_post($tw_post);
    }
    
    public function erase_tweettee() {
        
        array_splice($this->wp_q->posts, $this->position, 1);
        $this->wp_q->post_count = --$this->wp_q->post_count;
        
    }
    
    protected static function who(){
        return 'm_';
    }
}

