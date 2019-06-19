<!-- Generic Modal -->
<div class="modal" id="generic-modal">
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
                    <input type="hidden" name="record-id" id="record-id"/>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp"
                               placeholder="Enter email" required>
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                            else.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile</label>
                        <input type="text" name="mobile" class="form-control" id="mobile"
                               placeholder="Mobile/Phone Number" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="private" type="checkbox" value="1" id="private">
                        <label class="form-check-label" for="private">
                            Private (wont show up in calendar for other users)
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="private">How do you plan to attend the appointment?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="type-campus"
                                   value="<?php echo CAMPUS_ONLY ?>" checked>
                            <label class="form-check-label" for="type-campus">
                                <?php echo CAMPUS_ONLY_TEXT ?>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="type-online"
                                   value="<?php echo VIRTUAL_ONLY ?>">
                            <label class="form-check-label" for="type-online">
                                <?php echo VIRTUAL_ONLY_TEXT ?>
                            </label>
                        </div>
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
