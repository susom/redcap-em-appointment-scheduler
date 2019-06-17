var dateToday = new Date();
/**
 * Show list of all available time slots
 */
jQuery(".date-picker-2").datepicker({
    //numberOfMonths: [4,3],
    changeMonth: false,
    changeYear: false,
    duration: 'fast',
    stepMonths: 0,
    beforeShowDay: $.datepicker.noWeekends,
    onSelect: function (dateText) {
        var url = jQuery("#slots-url").val();
        var event_id = jQuery("#event-id").val();

        jQuery.ajax({
            'url': url + "&date=" + dateText + "&event_id=" + event_id,
            'type': 'GET',
            'success': function (data) {
                var html = data;
                jQuery("#selected-date").val(dateText);
                jQuery('#time-slots').find('.modal-title').html('Available Time Slots for ' + dateText);
                jQuery('#time-slots').find('.modal-body').html(html);
                $('#time-slots').modal('show');
            },
            'error': function (request, error) {
                alert("Request: " + JSON.stringify(request));
            },
            'complete': function () {
                populateMonthSummary();
            }
        });
    },
    minDate: dateToday,
});