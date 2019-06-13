<div class=" col-md-4">
    <div  class="date-picker-2" data-toggle="popover" data-html="true" data-content="" placeholder="Recipient's username" id="ttry" aria-describedby="basic-addon2"></div>
    <span class="" id="example-popover-2"></span>
</div>

<input type="hidden" name="selected-date" id="selected-date"/>
<input type="hidden" name="selected-time" id="selected-time"/>
<!-- Modal -->
<!-- The Modal -->


<!-- Time Slots Modal -->
<div class="modal" id="time-slots">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Modal Heading</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                Modal body..
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<!-- END Time Slots Modal -->

<!-- Booking Modal -->
<div class="modal" id="booking">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Modal Heading</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form id="booking-form">
                    <input type="hidden" name="record-id" id="record-id" />
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email" required>
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile</label>
                        <input type="text" name="mobile" class="form-control" id="mobile" placeholder="Mobile/Phone Number" required>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" id="submit-booking-form" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<!-- END Booking Modal -->


<input type="hidden" id="slots-url" value="<?php echo $module->getUrl('src/slots.php', TRUE, TRUE) ?>" class="hidden"/>
<input type="hidden" id="book-slot-url" value="<?php echo $module->getUrl('src/slots.php', TRUE, TRUE) ?>" class="hidden"/>
<input type="hidden" id="book-submit-url" value="<?php echo $module->getUrl('src/book.php', FALSE, TRUE) ?>" class="hidden"/>
<input type="hidden" id="event-id" value="<?php echo filter_var($_GET['event_id'], FILTER_SANITIZE_NUMBER_INT) ?>" class="hidden"/>
<script>

    var dateToday = new Date();
    /**
     * Show list of all available time slots
     */
    jQuery(".date-picker-2").datepicker({
        onSelect: function(dateText) {
            var url = jQuery("#slots-url").val();
            var event_id = jQuery("#event-id").val();

            jQuery.ajax({
                'url' : url + "&date=" + dateText + "&event_id=" + event_id,
                'type' : 'GET',
                'success' : function(data) {
                    var html = data;
                    jQuery("#selected-date").val(dateText);
                    jQuery('#time-slots').find('.modal-title').html('Available Time Slots for ' + dateText);
                    jQuery('#time-slots').find('.modal-body').html(html);
                    $('#time-slots').modal('show');
                },
                'error' : function(request,error)
                {
                    alert("Request: "+JSON.stringify(request));
                }
            });
        },
        minDate: dateToday,
    });

    /**
     * Show Form to complete for selected time
     */
    jQuery(document).on('click', '.time-slot', function(){
        var record_id = jQuery(this).data('record-id');
        var dateText = jQuery(this).text();

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
        jQuery("#record-id").val(record_id);
        jQuery('#booking').find('.modal-title').html('Book Time Slot for ' + dateText);
        $('#booking').modal('show');
        $('#time-slots').modal('hide');
    });


    /**
     * Complete booking form
     */
    jQuery(document).on('click', '#submit-booking-form', function(){

        record.email = jQuery("#email").val();
        record.name = jQuery("#name").val();
        record.mobile = jQuery("#mobile").val();
        record.notes = jQuery("#notes").val();
        record.record_id = jQuery("#record-id").val();
        record.event_id = jQuery("#event-id").val();

        var url = jQuery("#book-submit-url").val();
        jQuery.ajax({
            url : url,
            type : 'POST',
            data : record,
            datatype : 'json',
            success : function(response) {
                response = JSON.parse(response);
                if(response.status == 'ok'){
                    alert(response.message);
                    $('#booking').modal('hide');
                }else{
                    alert(response.message);
                }
            },
            error : function(request,error)
            {
                alert("Request: "+JSON.stringify(request));
            }
        });
    });
</script>
