var slot = {};
/**
 * Cancel Time Slot
 */
jQuery(document).on('click', '.cancel-slot', function () {

    if (confirm("Are you sure you want to cancel this Time Slot?")) {
        var record_id = jQuery(this).data('record-id');
        var event_id = jQuery(this).data('event-id');
        var url = jQuery('#cancel-slot-url').val();
        /**
         * Get Manage modal to let user manage their saved appointments
         */
        jQuery.ajax({
            url: url + '&record_id=' + record_id + '&event_id=' + event_id,
            type: 'GET',
            datatype: 'json',
            success: function (data) {
                data = JSON.parse(data);
                alert(data.message)
            },
            error: function (request, error) {
                alert("Request: " + JSON.stringify(request));
            }
        });
    }
});

/**
 * call reschedule form and populate with data
 */
jQuery(document).on('click', '.reschedule-slot', function () {

    slot.date = jQuery(this).data('date');
    slot.start = jQuery(this).data('start');
    slot.end = jQuery(this).data('end');
    slot.instructor = jQuery(this).data('instructor');
    slot.event_id = jQuery(this).data('event-id');
    slot.record_id = jQuery(this).data('record-id');

    console.log(slot);
    fillSlotForm(slot, function () {
        $("#start").datetimepicker();
        $("#end").datetimepicker();
        $('#reschedule').modal('show');
        $('#generic-modal').modal('hide');
    })
});

/**
 *
 * @param array data
 * @param method callback
 */
function fillSlotForm(data, callback) {

    jQuery('#start').val(data.date + ' ' + data.start);
    jQuery('#end').val(data.date + ' ' + data.end);
    jQuery('#instructor').val(data.instructor);
    callback();
}

/**
 * Submit Reschedule
 */
jQuery(document).on('click', '#submit-reschedule-form', function () {

    slot.start = jQuery("#start").val();
    slot.end = jQuery("#end").val();
    slot.instructor = jQuery("#instructor").val();
    slot.notes = jQuery("#reschedule-notes").val();

    var url = jQuery("#reschedule-submit-url").val();
    console.log(slot);
    jQuery.ajax({
        url: url,
        type: 'POST',
        data: slot,
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