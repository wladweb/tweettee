
<h1>Tweettee</h1>
<hr>

<p class="message">
    <?php echo $this->message; ?>
</p>

<?php if (is_null($tw_opt['access_token']) || is_null($tw_opt['access_secret'])) : ?>
    <form method="POST" action="<?php $SERVER['REQUEST_URI']; ?>">
        <?php wp_nonce_field('auth_form', 'oauth_nonce'); ?>
        <table class="oauth_data">
            <tr>
                <td><label for="ck">Consumer Key</label></td>
                <td><input type="text" name="consumer_key" id="ck" value="<?php echo $tw_opt['consumer_key']; ?>"></td>
            </tr>
            <tr>
                <td><label for="cs">Consumer Secret</label></td>
                <td><input type="text" name="consumer_secret" id="cs" value="<?php echo $tw_opt['consumer_secret']; ?>"></td>
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
        $op = get_option('tweettee');
        print_r($op);
        //print_r($this->get_admin_page_url());
        if ($this->get_admin_page_url()){
            $str = '"' . $this->get_admin_page_url() . '"';
        }else{
            $str = 'undefined';
        }
        print "<script>var tweettee_oauth_redir_url = " . $str . "</script>";
    ?>

<?php endif; ?>


