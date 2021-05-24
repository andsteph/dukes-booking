(function ($) {

    // https://stackoverflow.com/questions/2507030/email-validation-using-jquery
    // *** I might use this
    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    // when window is loaded ------------------------------
    $(window).on('load', function () {

        // time time according to the website
        var date = new Date(php_vars.date);
        setInterval(function () {
            $('.dbs-schedule-date-time').html('<div>Current Date/Time: ' + date.toString() + "</div>");
            date.setSeconds(date.getSeconds() + 1);
        }, 1000);

        // reload schedule when date is changed -----------
        $('#dbs-schedule-date').on('change', function () {
            if (php_vars.is_admin) {
                window.location.href = php_vars.admin_home + '&date=' + $(this).val();
            } else {
                window.location.href = php_vars.booking_url + '/' + $(this).val();
            }
        });

        // toggle selection of timeslot -------------------
        $('.dbs-timeslot').on('click', function () {
            $input = $(this).find('input');
            if ($input.attr('data-status') == 'open') {
                $(this).toggleClass('selected');
                $input.prop('disabled', function (i, v) {
                    return !v;
                });
            }
            var block_count = $('.dbs-timeslot.selected').length;
            var price = 0;
            for (var i = 0; i < block_count; i++) {
                if (i == 0) {
                    price += parseFloat(php_vars.block_price);
                } else {
                    price += parseFloat(php_vars.block_price) * parseFloat(php_vars.extra_block_discount);
                }
            }
            $('#dbs-schedule-price').val(parseFloat(price).toFixed(2));
        });

    });

})(jQuery);