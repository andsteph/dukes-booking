(function ($) {

    // when window is loaded ------------------------------
    $(window).on('load', function () {

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
            // open email form
            $('.dbs-email-form').modal({
                fadeDuration: 250
            });
            /*
            $('.dbs-schedule-errors').empty();
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
                    // for admin area
                    if ( php_vars.is_admin ) {
                        // update view to show bookings
                        location.reload();
                        if ( getPayment() ) {
                            // update view to show they're paid
                            location.reload();
                        };
                    // for users/front-end
                    } else {
                        if ( getPayment() ) {
                            // update if payment is successful only
                            location.reload();
                        }
                    }
                }
            });
            // is this necessary?
            $(document.body).trigger('post-load');
            */
        });

        // save button on email form ----------------------
        $('#dbs-email-save').on('click', function () {
            var data = {
                'action': 'validate_email',
                'email': $('#dbs-email-input').val()
            };
            $.post(php_vars.ajax_url, data, function (response) {
                if ( response == true ) {
                    console.log('valid email');
                    $('#dbs-payment-form').modal({
                        fadeDuration: 250
                    });
                } else {
                    console.log('invalid email');
                    $('dbs-email-form-errors').html('Please enter a valid email address');
                }
            });
        });

        var payment_form_result = null;

        // failed payment test button ---------------------
        $('#dbs-payment-form-failed').on('click', function () {
            $.modal.close();
        });

        // successful payment text button -----------------
        $('#dbs-payment-form-successful').on('click', function() {
            $.modal.close();
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