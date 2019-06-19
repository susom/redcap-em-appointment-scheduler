/**
 * Cancel appointment
 */
jQuery(document).on('click', '.cancel-appointment', function () {

    if (confirm("Are you sure you want to cancel this appointment?")) {
        var participation_id = jQuery(this).data('participation-id');
        var url = jQuery('#cancel-appointment-url').val();
        /**
         * Get Manage modal to let user manage their saved appointments
         */
        jQuery.ajax({
            url: url + '&participation_id=' + participation_id,
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