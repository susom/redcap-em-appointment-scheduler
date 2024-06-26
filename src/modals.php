<?php
/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */
//this when called for redcap hook
if (!isset($module)) {
    $module = $this;
}
?>
<!-- Generic Modal -->

<div class="modal " id="generic-modal">
    <div class="modal-dialog modal-xl modal-sm" >
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
                        <input type="text" name="name" class="form-control" id="name" placeholder="Your Name"
                               value="<?php echo(isset($_GET[COMPLEMENTARY_NAME]) ? filter_var($_GET[COMPLEMENTARY_NAME],
                                   FILTER_SANITIZE_STRING) : '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" name="email" class="form-control" id="email"
                               value="<?php echo(isset($_GET[COMPLEMENTARY_EMAIL]) ? filter_var($_GET[COMPLEMENTARY_EMAIL],
                                   FILTER_SANITIZE_STRING) : '') ?>" aria-describedby="emailHelp"
                               placeholder="Enter email" required>
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                            else.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile</label>
                        <input type="text" name="mobile" class="form-control" id="mobile"
                               value="<?php echo(isset($_GET[COMPLEMENTARY_MOBILE]) ? filter_var($_GET[COMPLEMENTARY_MOBILE],
                                   FILTER_SANITIZE_NUMBER_INT) : '') ?>"
                               placeholder="Mobile/Phone Number">
                    </div>
                    <!--div class="form-check">
                        <input class="form-check-input" name="private" type="checkbox" value="1" id="private">
                        <label class="form-check-label" for="private">
                            Private (wont show up in calendar for other users)
                        </label>
                    </div-->
                    <div class="form-group" id="attending-options">
                        <label for="private">How do you plan to attend the appointment?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="type-campus"
                                   value="<?php echo CAMPUS_ONLY ?>">
                            <label class="form-check-label" for="type-campus" id="type-campus-text">
                                <?php echo CAMPUS_ONLY_TEXT ?>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="type-online"
                                   value="<?php echo VIRTUAL_ONLY ?>">
                            <label class="form-check-label" for="type-online" id="type-online-text">
                                <?php echo VIRTUAL_ONLY_TEXT ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group" id="show-notes">
                        <label for="notes" id="notes-label">What is your question?</label>
                        <textarea class="form-control" name="notes" id="notes"
                                  rows="3"><?php echo(isset($_GET[COMPLEMENTARY_NOTES]) ? filter_var($_GET[COMPLEMENTARY_NOTES],
                                FILTER_SANITIZE_STRING) : '') ?></textarea>
                    </div>
                    <?php
                    //for surveys no need for projects to be displayed
                    if (defined("USERID")) {
                        ?>
                        <div class="form-group" id="show-projects">
                            <label for="project_id">Project ID(Optional)</label>
                            <select name="project_id" id="project_id" class="form-control">
                                <option value="" selected>No Project Available</option>
                                <?php
                                $projects = $module->getUserProjects(USERID);
                                while ($row = db_fetch_array($projects)) {
                                    ?>
                                    <option value="<?php echo $row['project_id'] ?>"><?php echo $row['app_title'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <?php
                    }
                    ?>

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

<!-- Reschedule Modal -->
<div class="modal" id="reschedule">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Reschedule Time Slot</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form id="reschedule-form">
                    <input type="hidden" name="reschedule-record-id" id="reschedule-record-id"/>
                    <input type="hidden" name="reschedule-event-id" id="reschedule-event-id"/>
                    <div class="form-group">
                        <label for="start">Start time</label>
                        <input type="text" name="start" class="form-control" id="start"
                               placeholder="Office Hours Start Time" required>
                    </div>
                    <div class="form-group">
                        <label for="end">End time</label>
                        <input type="text" name="end" class="form-control" id="end" placeholder="Office Hours End Time"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="instructor">Instructor (Please type SUNet ID)</label>
                        <input type="text" name="instructor" class="form-control" id="instructor"
                               placeholder="Instructor SUNet ID" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" name="location" class="form-control" id="location"
                               placeholder="Appointment Location" required>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" name="reschedule-notes" id="reschedule-notes"
                                  rows="3"></textarea>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" id="submit-reschedule-form" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<!-- END Booking Modal -->