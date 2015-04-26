

<?php foreach($data as $twit) : ?>
    <?php if (is_null($this->option['w_only_text'])) : ?>
        <div class='w-tweettee-block'>
            <div class="w-tweettee-block-header">
                <img src="<?php print $twit->user->profile_image_url ?>">
                <p><?php print $this->build_link('https://twitter.com/' . $twit->user->screen_name, '@' . $twit->user->screen_name) ?></p>
            </div>
            <div class="w-tweettee-block-body"><?php print $twit->text ?></div>
            <div class="w-tweettee-block-footer">
                <p class="w-tweettee-block-date"><?php print $this->get_correct_time($twit->created_at) ?></p>
                <p class="w-tweettee-block-link"><?php print $this->build_link('https://twitter.com/post_post_/status/' . $twit->id, 'Link') ?></p>
            </div>
        </div>
    <?php else : ?>
        <div class='w-tweettee-block'>
            <div class="w-tweettee-block-body"><?php print $twit->text ?></div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
