<?php
namespace Stanford\AppointmentScheduler;
/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


$url = $module->getUrl('src/calendar.php', TRUE, TRUE);
$types = $module->getInstances();

?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<div class="container">
<?php
foreach ($types as $key => $type){
    ?>
    <div class="row">
        <a class="type" data-key="<?php echo $key ?>" href="javascript:;" data-url="<?php echo $url . '&config=' . $key . '&event_id=' . $type['event_id']?>"><div class="btn btn-block btn-info"><?php echo $type['title']?></div></a>
    </div>
    <div class="row">
        <div id="<?php echo $key ?>-calendar"></div>
    </div>
    <hr/>
    <?php
}
?>
</div>
<script>
    var record = {};
    /**
     * Show Form to complete for selected time
     */
    jQuery(document).on('click', '.type', function(){
        var url = jQuery(this).data('url');
        var key = jQuery(this).data('key');

        jQuery.ajax({
            'url' : url,
            'type' : 'GET',
            'success' : function(data) {
                jQuery("#" + key + "-calendar").html(data);
            },
            'error' : function(request,error)
            {
                alert("Request: "+JSON.stringify(request));
            }
        });
    });
</script>
