<?php

namespace Wladweb\Tweettee\Includes;

use Wladweb\Tweettee\Includes\Core\I\SessionInterface;

/**
 * Manage sending & recieving tokens throgh session
 */
class TweetteeSession implements SessionInterface
{

    /**
     * Oauth token
     * @var string
     */
    private $oauth_token;

    /**
     * Oauth secret
     * @var string 
     */
    private $oauth_secret;

    /**
     * Start session & store tokens if it already in session
     */
    public function __construct()
    {
        \session_start();

        if (isset($_SESSION['oauth_token'])) {
            $this->oauth_token = $_SESSION['oauth_token'];
        }

        if (isset($_SESSION['oauth_secret'])) {
            $this->oauth_secret = $_SESSION['oauth_secret'];
        }
    }

    /**
     * Check is token exist in session
     * @return boolean
     */
    public function hasTokens()
    {
        if (empty($this->oauth_token) || empty($this->oauth_secret)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Set tokens into session
     * @param array $token
     * @return false|integer
     */
    public function setTokens(array $token)
    {
        if (!is_array($token) || empty($token['oauth_token']) || empty($token['oauth_token_secret'])) {
            return false;
        }

        $_SESSION['oauth_token'] = (string) $token['oauth_token'];
        $_SESSION['oauth_secret'] = (string) $token['oauth_token_secret'];

        return count($_SESSION);
    }

    /**
     * Get tokens from session if it was there
     * @return false|array
     */
    public function getTokens()
    {
        if ($this->hasTokens()) {
            return [
                'oauth_token' => $this->oauth_token,
                'oauth_token_secret' => $this->oauth_secret
            ];
        }

        return false;
    }
    
    /**
     * Delete session's cookie by hook 'init'
     * @return void 
     */
    public function delSessionCookie()
    {
        if (\filter_has_var(INPUT_GET, 'oauth_verifier')) {
            $cookie_params = \session_get_cookie_params();
            \setcookie(ini_get('session.name'), '', 1, $cookie_params['path'], $cookie_params['domain'], $cookie_params['secure'], $cookie_params['httponly']);
        }
    }

    /**
     * Destroy session 
     * @return void 
     */
    public function destroy()
    {
        $_SESSION = [];
        \session_destroy();
    }

}
