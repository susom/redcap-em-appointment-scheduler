var record = {};
/**
 * Show Form to complete for selected time
 */
jQuery(document).on('click', '.type', function () {
    var url = jQuery(this).data('url');
    var key = jQuery(this).data('key');
    /**
     * init the reservation event id for selected slot.
     * @type {jQuery}
     */
    record.event_id = jQuery('#' + key + "-reservation-event-id").val();
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
            jQuery("#" + key + "-calendar").hide().html(data).slideDown("slow");
            ;

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
                $("#event-id").val(key)
                populateMonthSummary(key);
            }, 100);
        },
        'error': function (request, error) {
            alert("Request: " + JSON.stringify(request));
        }
    });
});


function populateMonthSummary(key, year, month) {
    setTimeout(function () {
        var url = jQuery("#summary-url").val();
        if (month == undefined) {
            month = ''
        }
        if (year == undefined) {
            year = ''
        }
        jQuery.ajax({
            'url': url + '&event_id=' + key + '&month=' + month + '&year=' + year,
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
                            if (response[day].availableText != undefined) {
                                jQuery(this).find("a").attr('data-content', response[day].availableText);
                            }
                            if (response[day].REDCapAvailableText != undefined) {
                                var $a = jQuery(this).find("a");
                                jQuery(this).append(response[day].REDCapAvailableText)
                                //jQuery(this).find("a").insertAfter(response[day].REDCapAvailableText);
                            }
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
     * do we need to show notes and projects section based on config.json
     */
    if (jQuery(this).data('show-projects') == "1") {
        jQuery("#show-projects").show();
    } else {
        jQuery("#show-projects").hide();
    }

    if (jQuery(this).data('show-notes') == "1") {
        jQuery("#show-notes").show();
        var label = jQuery(this).data('notes-label');
        jQuery("#notes-label").text(label);
    } else {
        jQuery("#show-notes").hide();
    }

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
                record = {};

                /**
                 * when this book came from survey page lets return the reservation id back to the survey.
                 */
                if (jQuery("input[name=survey_reservation_id]").length) {
                    completeSurveyReservation(response);
                }

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
                jQuery('#generic-modal').modal('show');
                jQuery('#generic-modal').modal('show');

                jQuery('#calendar-datatable').DataTable();
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
 * trigger function to load instance
 */
$(document).ready(function () {
    var instance = jQuery("#triggered-instance").val();
    var instances = jQuery("a.type");
    if (instance == '') {
        var $elem = jQuery(instances[0]);
        $elem.trigger('click');
    } else {
        var $elem = jQuery('a.type[data-name="' + instance + '"]');
        $elem.trigger('click');
    }
});

//Calendar functions
function popupCal(cal_id, width) {
    window.open(app_path_webroot + 'Calendar/calendar_popup.php?pid=' + pid + '&width=' + width + '&cal_id=' + cal_id, 'myWin', 'width=' + width + ', height=250, toolbar=0, menubar=0, location=0, status=0, scrollbars=1, resizable=1');
}