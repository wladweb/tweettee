<?php

namespace Wladweb\Tweettee\Includes\Core;

use Wladweb\Tweettee\Includes\Core\I\SessionInterface;
use Wladweb\Tweettee\Includes\Core\I\AccessInterface;
use Wladweb\Tweettee\Includes\Core\I\SettingsInterface;
use Wladweb\Tweettee\Includes\Core\Exceptions\TweetteeAdminException;
use Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuthException;

/**
 * Twitter authorization 
 */
class TwitterAccess implements AccessInterface
{

    /**
     * Regular behavior
     */
    const REGULAR_REQUEST = 0;

    /**
     * We got data from start form with ConsumerKey & ConsumerSecret 
     */
    const FORM_RECIEVED = 1;

    /**
     * We got response from Twitter with oauth_verifier
     */
    const VERIFIER_RECIEVED = 2;

    /**
     * Session manager object
     * @var SessionInterface
     */
    protected $session;

    /**
     * Current state of this object
     * @var int 
     */
    protected $status = self::REGULAR_REQUEST;

    /**
     * Are you verified?
     * @var boolean
     */
    private $verified = false;

    /**
     * Url for twitter response
     * @var string
     */
    private $callback;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->callback = admin_url('options-general.php?page=tweettee');
    }

    /**
     * Check current state
     * @return void
     */
    protected function checkStatus()
    {
        if (\filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST' && \filter_has_var(INPUT_POST, 'consumer_submit') && \check_admin_referer('auth_form', 'oauth_nonce')) {
            $this->status = self::FORM_RECIEVED;
        } elseif (\filter_has_var(INPUT_GET, 'oauth_verifier') && \filter_input(INPUT_GET, 'oauth_verifier') !== '') {
            $this->status = self::VERIFIER_RECIEVED;
        }
    }

    /**
     * Return verif status
     * @return boolean
     */
    public function isVerified()
    {
        return $this->verified;
    }

    /**
     * Create TwitterOAuth object depending on the current state
     * 
     * @param  SettingsInterface $options
     * @return TwitterOAuth
     */
    private function createOauth(SettingsInterface $options)
    {
        if ($this->status === self::FORM_RECIEVED) {
            $consumer_key = \filter_input(INPUT_POST, 'consumer_key', FILTER_SANITIZE_STRING);
            $consumer_secret = \filter_input(INPUT_POST, 'consumer_secret', FILTER_SANITIZE_STRING);

            $options->setOption([
                'consumer_key' => $consumer_key,
                'consumer_secret' => $consumer_secret
            ]);
        } elseif ($this->status === self::VERIFIER_RECIEVED) {

            $consumer_key = $options->getOption('consumer_key');
            $consumer_secret = $options->getOption('consumer_secret');
        }

        $oauth = new TwitterOAuth($consumer_key, $consumer_secret);

        return $oauth;
    }

    /**
     * Start diferent way depending on the current state
     * @param SettingsInterface $options
     */
    public function process(SettingsInterface $options)
    {
        $this->checkStatus();

        switch ($this->status) {
            case self::REGULAR_REQUEST:
                $this->checkVerification($options);
                break;
            case self::FORM_RECIEVED:
                $this->getOauthToken($this->createOauth($options));
                break;
            case self::VERIFIER_RECIEVED:
                $this->getAccessToken($this->createOauth($options), $options);
                break;
        }
    }

    /**
     * Check verivication
     * @param SettingsInterface $options
     */
    private function checkVerification(SettingsInterface $options)
    {
        $tokens = $options->getOption([
            'oauth_token',
            'oauth_token_secret'
        ]);

        if (!empty($tokens['oauth_token']) && !empty($tokens['oauth_token_secret'])) {
            $this->verified = true;
        }
    }

    /**
     * Get request tokens and build twitter url for getting permision 
     * 
     * @param TwitterOAuth $oauth
     * @throws TweetteeAdminException If request failed and response code unequal 200
     */
    private function getOauthToken(TwitterOAuth $oauth)
    {
        try {
            $request_token = $oauth->oauth('oauth/request_token', array('oauth_callback' => $this->callback));
        } catch (TwitterOAuthException $e) {
            throw new TweetteeAdminException($e->getMessage(), $e->getCode(), $e);
        }

        $this->session->setTokens($request_token);

        $url = $oauth->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

        echo "<script>var tweettee_oauth_authorize_url = '$url'</script>";
    }

    /**
     * Get access token, store it and redirect to plugin settings page
     * 
     * @param TwitterOAuth $oauth
     * @param SettingsInterface $options
     * @throws TweetteeAdminException If there isn't tokens in the session
     * @throws TweetteeAdminException If request failed and response code unequal 200
     * @return void
     */
    private function getAccessToken(TwitterOAuth $oauth, SettingsInterface $options)
    {
        if (!$token = $this->session->getTokens()) {
            throw new TweetteeAdminException('Dont have oauth token', 1001);
        }
        
        $this->session->destroy();
        
        $oauth->setOauthToken($token['oauth_token'], $token['oauth_token_secret']);

        try {
            $access_token = $oauth->oauth("oauth/access_token", ["oauth_verifier" => filter_input(INPUT_GET, 'oauth_verifier')]);
        } catch (TwitterOAuthException $e) {
            throw new TweetteeAdminException($e->getMessage(), $e->getCode(), $e);
        }
        
        $account_info = $this->getAccountInfo($oauth, $access_token);
        $access_token['account_info'] = $account_info;
        
        $options->setOption($access_token);

        $url = admin_url('options-general.php?page=tweettee');
        echo "<script>var tweettee_oauth_redir_url = '" . $url . "'</script>";
    }
    
    /**
     * Doing first request for get account info
     * 
     * @param TwitterOAuth $oauth
     * @param array $access_token
     * @return stdClass
     */
    protected function getAccountInfo(TwitterOAuth $oauth, array $access_token)
    {
        $oauth->setOauthToken($access_token['oauth_token'], $access_token['oauth_token_secret']);
        return $oauth->get('account/verify_credentials', ['skip_status' => true, 'include_entities' => false]);
    }

}
