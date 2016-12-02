<?php

// is_singular when $post is not loaded. so to make sure, we do it when at the
//time of attribute adding


function add_comment_boxCFB() {
    $prm_link = wp_get_shortlink(); // better than permalink because doesn't affect if link structure changed
    
    if($prm_link == "") 
    	$prm_link = get_permalink();
    	
    $cfb = get_option('cfb_com');
    echo "<div id='fbcommentbox' style='".$cfb['css']."'>";
    if(strlen($cfb['title']) > 0 )
        echo "<h3>".$cfb['title']."</h3>";
    if(strlen($cfb['txtpre']) > 0 || strlen($cfb['txtpost'] > 0)){
        echo "<h4>".$cfb['txtpre'];
        echo "<fb:comments-count href=\"".$prm_link."\"></fb:comments-count>";
        echo $cfb['txtpost']."</h4>";
     }
     
     
     if($cfb['schm'] == "dark") $schmx = " colorscheme=\"dark\" "; else $schmx = "";
     
     echo "<fb:comments href=\"".$prm_link."\" num_posts=\"".$cfb['numpost'];
     echo "\" width=\"".$cfb['width']."\"".$schmx."></fb:comments>";
     echo "</div>";



}

function add_comment_boxwrapper_for_filter($comments) {

	add_comment_boxCFB();
	return $comments;

}

function add_modCFB(){
    $cfb = get_option('cfb_com');
    return "<meta property=\"fb:admins\" content=\"".$cfb['mods']."\"/>";
}

function facebook_share(){
	$cfbglb = get_option('cfb_global');
?>
<script>
      FB.init({appId: "<?=$cfbglb['appid']?>", status: true, cookie: true});

      function facebook_share() {

       FB.ui({
          method: 'feed',
          link: '<?=get_permalink();?>',
          name: '<?=htmlspecialchars(the_title());?>',
          caption: '<?=strip_tags(get_the_excerpt())?>',
        });


      }
   
    </script>
<?php

}

?>