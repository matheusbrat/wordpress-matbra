<?php
function the_excerpt_max_charlength($charlength) {
	$excerpt = get_the_excerpt();
	$charlength++;
	$r;
	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			$r = mb_substr( $subex, 0, $excut );
		} else {
			$r = $subex;
		}
		$r .= '[...]';
	} else {
		$r = $excerpt;
	}
	return $r;
}

function cfb_openGraphMeta() { 
	
	$meta = cfb_ogValues();
	$s	  = "<!-- OpenGraph meta add by Complete Facebook -->\n";
	$s	 .= cfb_genMeta("og:title", $meta['title']);
	$s	 .= cfb_genMeta("og:type", 'article');
	$s	 .= cfb_genMeta("og:url", $meta['permalink']);
	$s	 .= cfb_genMeta("og:image", $meta['image']);
	$s	 .= cfb_genMeta("og:site_name", $meta['blogname']);
	$s	 .= cfb_genMeta("og:description", $meta['description']);
	$s	 .= cfb_genMeta("og:locale", $meta['lang']);
	return $s;		
}



function cfb_ogValues() {
	$og_optn = get_option('cfb_og');
	if(is_single() ){
		if (have_posts()) : while (have_posts()) : the_post(); 
			$meta['title']	  	= get_the_title($post->post_title);
			$meta['permalink']	= get_permalink();
			$meta['image']	  	= $og_optn["thumb"];
			$meta['blogname'] 	= get_option('blogname');
			$meta['description']= the_excerpt_max_charlength(150);
			$meta['lang']		= $og_optn["lang"];
			$meta['appid']		= $og_optn["admin"];
			endwhile; 
		endif; 
	}else{
		$meta['title']	  	= get_option('blogname');
		$meta['permalink']  = get_option('siteurl');
		$meta['image']	  	= $og_optn["thumb"];
		$meta['blogname']   = get_option('blogname');
		$meta['description']= get_option('blogdescription');
		$meta['lang']		= $og_optn["lang"];
		$meta['appid']		= $og_optn["admin"];
	}
	
	return $meta;
}
function cfb_genMeta($name, $value) {
	$m = '<meta property="' . $name . '" content="' . $value . '" />';
	$m.= "\n";
	return $m;
}