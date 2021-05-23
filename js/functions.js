(function ($) {

    // https://stackoverflow.com/questions/2507030/email-validation-using-jquery
    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
      }

    // when window is loaded ------------------------------
    $(window).on('load', function () {

        // time time according to the website
        var date = new Date(php_vars.date);
        setInterval(function() {
            $('.dbs-schedule-date-time').html('<div>' + date.toString() + "</div>");
            date.setSeconds(date.getSeconds()+1);
        }, 1000);

        // reload schedule when date is changed -----------
        $('#dbs-schedule-date').on('change', function () {
            if (php_vars.is_admin) {
                window.location.href = php_vars.admin_home + '&date=' + $(this).val();
            } else {
                window.location.href = php_vars.booking_url + '/' + $(this).val();
            }
        });

        // when book button is clicked --------------------
        $('#dbs-schedule-book').on('click', function (e) {
            e.preventDefault();
            var email = $('#dbs-schedule-email').val();
            if ( !isEmail(email) ) {
                $('.dbs-schedule-errors').append('<p>Email is not valid.</p>');
                $('#dbs-schedule-email').focus();
                return;
            }
            var times = [];
            $('.dbs-timeslot.selected').each(function () {
                var time = $(this).children('input').val()
                times.push(time);
            });
            var data = {
                'action': 'save_booking',
                'date': $('#dbs-schedule-date').val(),
                'email': $('#dbs-schedule-email').val(),
                'provider_id': $('.dbs-schedule-provider.selected').attr('id'),
                'times': times
            };
            jQuery.post(php_vars.ajax_url, data, function (response) {
                var errors = JSON.parse(response);
                if (errors.length) {
                    for (var i = 0; i < errors.length; i++) {
                        var error = errors[i];
                        $('.dbs-schedule-errors').append('<p>' + error + '</p>');
                    }
                } else {
                    location.reload();
                }
            });
            // is this necessary?
            $(document.body).trigger('post-load');
        });

        // toggle selection of timeslot -------------------
        $('.dbs-timeslot').on('click', function () {
            if ($(this).attr('data-locked') == false && $(this).attr('data-status') == 'open') {
                var lastSelectedProviderID = $('.dbs-schedule-provider.selected').attr('id');
                $(this).toggleClass('selected');
                $('.dbs-schedule-provider').removeClass('selected');
                if ($('.dbs-timeslot.selected').length > 0) {
                    $('#dbs-schedule-book').prop('disabled', false);
                    $(this).closest('.dbs-schedule-provider').addClass('selected');
                } else {
                    $('#dbs-schedule-book').prop('disabled', true);
                }
                var currentSelectedProviderID = $('.dbs-schedule-provider.selected').attr('id');
                if (lastSelectedProviderID !== currentSelectedProviderID) {
                    $('#' + lastSelectedProviderID).find('.dbs-timeslot').removeClass('selected');
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
            }
        });

    });

})(jQuery);