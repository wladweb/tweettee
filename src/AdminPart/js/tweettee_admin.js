
jQuery(document).ready(function(){
    if (window.tweettee_oauth_authorize_url !== undefined){
        window.location.href = tweettee_oauth_authorize_url;
    }
    if (window.tweettee_oauth_redir_url !== undefined){
        window.location.href = tweettee_oauth_redir_url;
    }
    
    if (jQuery('#show-main-page-settings').prop('checked')){
        jQuery('#settings-main-page-block').slideDown(300);
    }
    if (jQuery('#tweettee-search-result').prop('checked')){
        jQuery('#search-result-for').slideDown(300);
    }
    if (jQuery('#m-tweettee-search-result').prop('checked')){
        jQuery('#m-search-result-for').slideDown(300);
    }
    
    //main-page-settings-block
    jQuery('#show-main-page-settings').on('change', function(){
        if (jQuery('#show-main-page-settings').prop('checked')){
            jQuery('#settings-main-page-block').slideDown(300);
        }else{
            jQuery('#settings-main-page-block').slideUp(300);
        }
    });
    
    //twitter-search-settings-block
    jQuery('[name=w_content_type]').on('change', function(){
        if (jQuery('#tweettee-search-result').prop('checked')){
            jQuery('#search-result-for').slideDown(300);
        }else{
            jQuery('#search-result-for').slideUp(300);
        }
        
        if(jQuery('#tweettee-another-timeline').prop('checked')){
            jQuery('#tweettee-another-timeline-name').focus();
        }
        jQuery('#tweettee-another-timeline-error').text('');
    });
    
    jQuery('[name=m_content_type]').on('change', function(){
        if (jQuery('#m-tweettee-search-result').prop('checked')){
            jQuery('#m-search-result-for').slideDown(300);
        }else{
            jQuery('#m-search-result-for').slideUp(300);
        }
        
        if(jQuery('#m-tweettee-another-timeline').prop('checked')){
            jQuery('#m-tweettee-another-timeline-name').focus();
        }
        jQuery('#m-tweettee-another-timeline-error').text('');
    });
    
    jQuery('[name=w_search_type]').on('change', function(){
        if(jQuery('#tweettee-search-free-word').prop('checked')){
            jQuery('#tweettee-free-word-value').focus();
        }
        jQuery('#tweettee-free-word-error').text('');
    });
    
    jQuery('[name=m_search_type]').on('change', function(){
        if(jQuery('#m-tweettee-search-free-word').prop('checked')){
            jQuery('#m-tweettee-free-word-value').focus();
        }
        jQuery('#m-tweettee-free-word-error').text('');
    });
    
    //block form
    jQuery('#tweettee_change_settings').click(function(){
        if (jQuery('#tweettee-another-timeline').prop('checked') && 
                !jQuery('#tweettee-another-timeline-name').val()){
                    jQuery('#tweettee-another-timeline-error').text('Type real twitter account.');
                    return false;
                }
        if (jQuery('#tweettee-search-free-word').prop('checked') && 
                !jQuery('#tweettee-free-word-value').val()){
                    jQuery('#tweettee-free-word-error').text('Type any word.');
                    return false;
                } 
        
        if (jQuery('#m-tweettee-another-timeline').prop('checked') && 
                !jQuery('#m-tweettee-another-timeline-name').val()){
                    jQuery('#m-tweettee-another-timeline-error').text('Type real twitter account.');
                    return false;
                }
        if (jQuery('#m-tweettee-search-free-word').prop('checked') && 
                !jQuery('#m-tweettee-free-word-value').val()){
                    jQuery('#m-tweettee-free-word-error').text('Type any word.');
                    return false;
                } 
        
    });
    
});
