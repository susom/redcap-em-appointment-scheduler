var record = {};
/**
 * Show Form to complete for selected time
 */
jQuery(document).on('click', '.type', function () {
    var url = jQuery(this).data('url');
    var key = jQuery(this).data('key');

    jQuery.ajax({
        'url': url,
        'type': 'GET',
        'beforeSend': function () {
            /**
             * remove any displayed calendar
             */
            jQuery('.slots-container').html('');
        },
        'success': function (data) {
            jQuery("#" + key + "-calendar").html(data);

        },
        'error': function (request, error) {
            alert("Request: " + JSON.stringify(request));
        }
    });
});

/**
 * Show list view
 */
jQuery(document).on('click', '.calendar-view', function () {
    var url = jQuery(this).data('url');
    var key = jQuery(this).data('key');
    $(".date-picker-2").datepicker("destroy");
    jQuery.ajax({
        'url': url,
        'type': 'GET',
        'success': function (data) {
            jQuery("#" + key + "-calendar").html(data);
            setTimeout(function () {
                populateMonthSummary();
            }, 100);
        },
        'error': function (request, error) {
            alert("Request: " + JSON.stringify(request));
        }
    });
});


function populateMonthSummary() {
    setTimeout(function () {
        var url = jQuery("#summary-url").val();
        jQuery.ajax({
            'url': url + '&event_id=' + jQuery("#event-id").val(),
            'type': 'GET',
            'success': function (response) {
                response = JSON.parse(response);

                jQuery(".ui-datepicker-calendar td").each(function (index, item) {
                    var day = jQuery(this).text();
                    if (response[day] != undefined) {
                        /**
                         * if date has open time slots
                         */
                        if (response[day].available != undefined) {
                            jQuery(this).find("a").attr('data-content', response[day].availableText);
                        } else {
                            jQuery(this).find("a").attr('data-content', "All slots are booked for this date");
                        }
                        jQuery(this).find("a").toggleClass('changed');
                    }
                });
            },
            'error': function (request, error) {
                alert("Request: " + JSON.stringify(request));
            }
        });

    }, 0)
}


/**
 * Show Form to complete for selected time
 */
jQuery(document).on('click', '.time-slot', function () {
    record.record_id = jQuery(this).data('record-id');
    record.event_id = jQuery(this).data('event-id');
    var dateText = jQuery(this).data('modal-title');

    /**
     * Capture start and end time for Email calendar
     */
    record.calendarStartTime = jQuery(this).data('start');
    record.calendarEndTime = jQuery(this).data('end');
    /**
     * Capture date for Email calendar
     */
    record.calendarDate = jQuery(this).data('date');
    jQuery("#selected-time").val(dateText);
    jQuery('#booking').find('.modal-title').html('Book Time Slot for ' + dateText);
    $('#booking').modal('show');
    $('#generic-modal').modal('hide');
    console.log(record);
});


/**
 * Complete booking form
 */
jQuery(document).on('click', '#submit-booking-form', function () {

    record.email = jQuery("#email").val();
    record.name = jQuery("#name").val();
    record.mobile = jQuery("#mobile").val();
    record.notes = jQuery("#notes").val();
    record.private = jQuery("#private").val();
    record.type = $("input[name='type']:checked").val();
    record.date = record.calendarDate;

    var url = jQuery("#book-submit-url").val();
    jQuery.ajax({
        url: url,
        type: 'POST',
        data: record,
        datatype: 'json',
        success: function (response) {
            response = JSON.parse(response);
            if (response.status == 'ok') {
                alert(response.message);
                $('#booking').modal('hide');
            } else {
                alert(response.message);
            }
        },
        error: function (request, error) {
            alert("Request: " + JSON.stringify(request));
        }
    });
});

/**
 * load calendar view by clicking on the main button and pass the appropriate key
 */
jQuery(document).on('click', '.list-view', function () {
    var key = jQuery(this).data('key');
    jQuery('.type[data-key="' + key + '"]').get(0).click();

});


/**
 * Get Manage modal to let user manage their saved appointments
 */
jQuery(document).on('click', '.manage', function () {
    var url = jQuery("#manage-url").val();
    if (email != '') {
        jQuery.ajax({
            url: url,
            type: 'GET',
            datatype: 'json',
            success: function (data) {
                jQuery('#generic-modal').find('.modal-title').html('Manage your appointments');
                jQuery('#generic-modal').find('.modal-body').html(data);
                $('#generic-modal').modal('show');

                $('#myTabs a[href="#profile"]').tab('show')
            },
            error: function (request, error) {
                alert("Request: " + JSON.stringify(request));
            }
        });
    } else {
        /**
         * user not logged in refresh to force sign in
         */
        location.reload();
    }
});


/**
 * Get Manage Calendar modal to let instructors manage all calendars
 */
jQuery(document).on('click', '.manage-calendars', function () {
    var url = jQuery("#manage-calendar-url").val();
    if (email != '') {
        jQuery.ajax({
            url: url,
            type: 'GET',
            datatype: 'json',
            success: function (data) {
                jQuery('#generic-modal').find('.modal-title').html('Manage Instructors Calendar');
                jQuery('#generic-modal').find('.modal-body').html(data);
                $('#generic-modal').modal('show');

                $('#myTabs a[href="#profile"]').tab('show')
            },
            error: function (request, error) {
                alert("Request: " + JSON.stringify(request));
            }
        });
    } else {
        /**
         * user not logged in refresh to force sign in
         */
        location.reload();
    }
});
