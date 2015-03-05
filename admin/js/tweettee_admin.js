
jQuery(document).ready(function(){
    if (window.tweettee_oauth_authorize_url !== undefined){
        window.location.href = tweettee_oauth_authorize_url;
    }
    if (window.tweettee_oauth_redir_url !== undefined){
        window.location.href = tweettee_oauth_redir_url;
    }
});