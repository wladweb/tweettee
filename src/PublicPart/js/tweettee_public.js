
(function ($) {

    $(document).ready(function () {

        setTimeout(function () {

            var $container = $('#tweettee_main_content');

            $container.masonry({
                itemSelector: '.tweettee_block',
                columnWidth: 5,
                isAnimated: true
            });

            $('.tweettee_block').click(function () {
                $('.tweettee_block').css('background', '#f6f6f6');
                $('.tweettee_block').css('box-shadow', '0 0 5px #ddd');

                $(this).css('background', '#fff');
                $(this).css('box-shadow', '0 0 7px #66AFE9');
            });
        }, 300);

    });

})(jQuery);




