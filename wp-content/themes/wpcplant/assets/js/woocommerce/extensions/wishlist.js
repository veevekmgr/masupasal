(function ($) {
    'use strict';

    function ajax_wishlist_count() {

        $('body').on('woosw_change_count', function (event, count) {
            var counter = $('.header-wishlist .count, .footer-wishlist .count');
            counter.html(count);
        });
    }

    $(document).ready(function () {
        ajax_wishlist_count();
    });

})(jQuery);