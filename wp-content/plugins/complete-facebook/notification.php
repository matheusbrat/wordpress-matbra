<?php
function cfb_send_notification() {
    $cfb_not = get_option('cfb_not');
	$type = $_GET['t'];
	$href = $_GET['h'];
	if($type == "like") { 
		mail($cfb_not['to'], "Like ", "Like on " . $href, "from: " . $cfb_not['from']);
		echo "LIKE";
	} else if($type == "comment") { 
		mail($cfb_not['to'], "Comment ", "Comment on " . $href, "from: " . $cfb_not['from']);
		echo "COMMENT";
		
	}
}