
<?php foreach($data as $tweet) : ?>
    <?php if (is_null($this->options['w_only_text'])) : ?>
        <div class='w-tweettee-block'>
            <div class="w-tweettee-block-header">
                <img src="<?php print $tweet->profile_image_url ?>">
                <span><?php print $this->build_link('https://twitter.com/' . $tweet->screen_name, '@' . $tweet->screen_name) ?></span>
            </div>
            <div class="w-tweettee-block-body"><?php print $tweet->text ?></div>
            <div class="w-tweettee-block-footer">
                <span class="w-tweettee-block-date"><?php print $this->get_correct_time($tweet->created_at) ?></span>
                <span class="w-tweettee-block-link"><?php print $this->build_link('https://twitter.com/post_post_/status/' . $tweet->id, 'Link') ?></span>
            </div>
        </div>
    <?php else : ?>
        <div class='w-tweettee-block'>
            <div class="w-tweettee-block-body"><?php print $tweet->text ?></div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
