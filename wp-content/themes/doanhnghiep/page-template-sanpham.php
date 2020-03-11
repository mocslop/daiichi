<?php 
/*
Template Name: page-template-sanpham
*/
get_header(); 
?>	
<div class="page-wrapper">
	<div class="g_content">
	
		<?php 
		$my_postid = 278;//This is page id or post id
		$content_post = get_post($my_postid);
		$content = $content_post->post_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		echo $content;
		?>
	</div>
</div>
<?php get_footer(); ?>
