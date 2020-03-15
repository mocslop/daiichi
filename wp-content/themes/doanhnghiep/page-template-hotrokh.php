<?php 
/*
Template Name: page-template-hotrokhachhang
*/
get_header(); 
?>	
<div class="page-wrapper">
	<div class="g_content">
		<div class="container"> 
            <div class="col-sm-9">
             <?php
             while ( have_posts() ) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->
             <div class="entry-content-page">
                <?php the_content(); ?> 
            </div>
            <?php
        endwhile; 
    wp_reset_query(); //resetting the page query
    ?> 
</div>

<div class="col-sm-3 sidebar">
    <?php dynamic_sidebar('sidebar1'); ?> 
</div>
</div><!-- container -->
</div>
</div>
<?php get_footer(); ?>

