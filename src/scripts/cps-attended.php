<?php
// Set Namespace
namespace comfortcloth_events;

global $post;
$complete = '';
$label = '';
	if($post->post_type == 'event'){
    if($post->post_status == 'attended'){ ?>

			<script type="text/javascript" id="XXXXXXXXXXXXX">
				jQuery(document).ready(function($){
					$("select#post_status").append("<option value=\"attended\" selected=\"selected>Attended</option>");
					$(".misc-pub-section").append("<span id=\"post-status-display\"> Attended</span>");
				});
			</script>

		<?php } else { ?>

			<script type="text/javascript" id="XXXXXXXXXXXXX">
				jQuery(document).ready(function($){
					$("select#post_status").append("<option value=\"attended\">Attended</option>");
				});
			</script>
		<?php } ?>
	<?php } ?>
