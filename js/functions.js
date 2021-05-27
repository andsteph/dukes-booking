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
            var full_price = 0;
            var discount_price = 0;
            var tax = 0;
            var total = 0;
            if ( block_count > 0 ) {
                full_price = php_vars.block_price;
                var text = '(1 x $' + parseFloat(php_vars.block_price).toFixed(2) + ') = $' + parseFloat(full_price).toFixed(2);
                $('#dbs-schedule-price-full').text(text);
            }
            if ( block_count > 1 ) {
                discount_price = (block_count - 1) * php_vars.block_price * php_vars.extra_block_discount;
                var text = '(' + parseInt(block_count-1) + ' @ ' + php_vars.extra_block_discount*100 + '% off) = $' + parseFloat(discount_price).toFixed(2);
                $('#dbs-schedule-price-discount').text(text);
            }
            tax = (+full_price + +discount_price) * php_vars.tax; // *** shouldn't hard code this
            $('#dbs-schedule-price-tax').text('$' + parseFloat(tax).toFixed(2));
            total = +full_price + +discount_price + +tax;
            $('#dbs-schedule-price-total').text('$' + parseFloat(total).toFixed(2));

        });

    });

})(jQuery);