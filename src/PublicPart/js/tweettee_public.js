
jQuery(document).ready(function(){
    var $container = jQuery('#tweettee_main_content');

    $container.masonry({
        itemSelector: '.tweettee_block',
        columnWidth: 5,
        isAnimated: true
    });

    jQuery('.tweettee_block').click(function(){
        jQuery('.tweettee_block').css('background','#f6f6f6');
        jQuery('.tweettee_block').css('box-shadow','0 0 5px #ddd');
        
        jQuery(this).css('background','#fff');
        jQuery(this).css('box-shadow','0 0 7px #66AFE9');
    });
    
    
});


