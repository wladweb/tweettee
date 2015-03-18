
<h1>Tweettee</h1>
<hr>

<p class="message">
    <?php echo $this->message; ?>
</p>

<?php if (is_null($tweettee_option['access_token']) || is_null($tweettee_option['access_secret'])) : ?>
    <form method="POST" action="<?php $SERVER['REQUEST_URI']; ?>">
        <?php wp_nonce_field('auth_form', 'oauth_nonce'); ?>
        <table class="oauth_data">
            <tr>
                <td><label for="ck">Consumer Key</label></td>
                <td><input type="text" name="consumer_key" id="ck" value="<?php echo $tweettee_option['consumer_key']; ?>"></td>
            </tr>
            <tr>
                <td><label for="cs">Consumer Secret</label></td>
                <td><input type="text" name="consumer_secret" id="cs" value="<?php echo $tweettee_option['consumer_secret']; ?>"></td>
            </tr>
            <tr>
                <td colspan="2"><button type="submit" name="consumer_submit">Отправить</button></td>
            </tr>
        </table>
    </form>
<?php else : ?>

    <h2>Settings</h2>
    <pre>
    <?php
        print_r($tweettee_option);
        
        if ($this->get_admin_page_url()){
            $str = '"' . $this->get_admin_page_url() . '"';
        }else{
            $str = 'undefined';
        }
        print "<script>var tweettee_oauth_redir_url = " . $str . "</script>";
    ?>
    
    <form method="post" action="<?php $SERVER['REQUEST_URI'] ?>" name="">
        <fieldset>
            <legend>Seo</legend>
            <p>
                Проставляет аттрибут "rel=nofollow" всем ссылкам внутри виджета.
            </p>
            <label for="seo">Seo</label>
            <input type="checkbox" id="seo" name="seo" value="checked" <?php echo $tweettee_option['seo'] ?>>
        </fieldset>
        
        <fieldset>
            <legend>Noindex</legend>
            <p>
                Оборачивает виджет конструкцией <?php echo htmlentities('<!--noindex-->') ?>
            </p>
            <label for="noindex">Noindex</label>
            <input type="checkbox" id="noindex" name="noindex" value="checked" <?php echo $tweettee_option['noindex'] ?>>
        </fieldset>
        
        <button type="submit" name="tweettee_change_settings">
            Submit
        </button>
    </form>
    
<?php endif; ?>


