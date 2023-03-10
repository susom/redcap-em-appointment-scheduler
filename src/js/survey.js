var record = {};
const reserved_event_id = "reserved_event_id";

/**
 * trigger function to load instance
 */
$(document).ready(function () {
    loadScheduleAppointment();
});
/**
 * list view in modal
 */
jQuery(document).on("click", ".survey-type", function () {
    var url = jQuery("#list-view-url").val();
    var key = jQuery(this).data('key');
    var view = jQuery(this).data('default-view');
    /**
     * init the reservation event id for selected slot.
     * @type {jQuery}
     */
    record.event_id = jQuery('#' + key + "-reservation-event-id").val();
    record.survey_record_id = jQuery(this).data('survey-record-id');
    record.reservation_event_id = jQuery('#' + key + "-reservation-event-id").val();
    jQuery.ajax({
        'url': url + "&event_id=" + jQuery("#slots-events-id").val(),
        'type': 'GET',
        'beforeSend': function () {
            /**
             * remove any displayed calendar
             */
            jQuery('.slots-container').html('');
        },
        'success': function (data) {
            jQuery('#generic-modal').find('.modal-title').html(jQuery("#survey-scheduler-header").val());
            jQuery('#generic-modal').find('.modal-body').html(data);
            $('#generic-modal').modal('show');

            //change calendar view event to be displayed in modal
            jQuery(".calendar-view").removeClass('calendar-view').addClass('survey-calendar-view');
        },
        'error': function (request, error) {
            alert("Request: " + JSON.stringify(request));
        },
        'complete': function () {
            loadDefaultView(view)
        }
    });
});

/**
 * Show calendar view in modal
 */
jQuery(document).on('click', '.survey-calendar-view', function () {
    var url = jQuery(this).data('url');
    var key = jQuery(this).data('key');
    $(".date-picker-2").datepicker("destroy");
    jQuery.ajax({
        'url': url,
        'type': 'GET',
        'success': function (data) {
            jQuery('#generic-modal').find('.modal-title').html(jQuery("#survey-scheduler-header").val());
            jQuery('#generic-modal').find('.modal-body').html(data);
            $('#generic-modal').modal('show');
            jQuery(".list-view").removeClass('list-view').addClass('survey-type');
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

function loadScheduleAppointment() {
    var survey_record_id_field = jQuery("#survey-record-id-field").val();
    jQuery(".survey-btn ").remove();
    if (jQuery("input[name=" + survey_record_id_field + "]").length) {
        var $elem = jQuery("input[name=" + survey_record_id_field + "]").parent();
        jQuery("input[name=" + survey_record_id_field + "]").attr('type', 'hidden');
        if (jQuery("input[name=" + survey_record_id_field + "]").val() == '') {
            var url = jQuery("#survey-scheduler-url").val();
            var key = jQuery("#slots-events-id").val();
            var surveyRecordId = jQuery("#survey-record-id").val();
            $elem.append('<div data-url="' + url + '" id="survey-controller" data-survey-record-id="' + surveyRecordId + '" data-key="' + key + '" class="survey-btn survey-type btn btn-block btn-info">Schedule Appointment</div>')
            //append this to show loader when ajax is fired
            $elem.append('<div class="loader"><!-- Place at bottom of page --></div>')
        } else {
            $elem.append('<div data-survey-field="' + survey_record_id_field + '" data-appt-source="survey" data-survey-record-id="' + jQuery("input[name=" + survey_record_id_field + "]").val() + '" data-event-id="' + jQuery("#survey-reservation-event-id").val() + '"  data-record-id-field="' + jQuery("#record-id-field").val() + '" class="survey-btn  cancel-appointment btn btn-block btn-danger">Cancel Booked Reservation</div>')
        }
    }
}

function completeSurveyReservation(response) {
    var survey_record_id_field = jQuery("#survey-record-id-field").val();
    jQuery("input[name=" + survey_record_id_field + "]").val(response.id);
    jQuery("#reserved-email").val(response.email);
    jQuery("#survey-controller").text("Cancel Booked Reservation");
    jQuery("#survey-controller").addClass("survey-btn  cancel-appointment btn-danger");
    jQuery("#survey-controller").removeClass("survey-type survey-calendar-view btn-info");
    jQuery("#survey-controller").attr("data-survey-field", survey_record_id_field);
    jQuery("#survey-controller").attr("data-appt-source", 'survey');
    jQuery("#survey-controller").attr("data-survey-record-id", response.id);
    jQuery("#survey-controller").attr("data-event-id", jQuery("#survey-reservation-event-id").val());
    jQuery("#survey-controller").attr("data-record-id-field", jQuery("#record-id-field").val());

    //jQuery("#survey-controller").addClass("manage");
}

$(document).on({
    ajaxStart: function () {
        $body.addClass("loading");
    },
    ajaxStop: function () {
        $body.removeClass("loading");
    }
});