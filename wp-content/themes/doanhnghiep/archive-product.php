<?php 
get_header(); 
if(have_posts()) :
	?>	
	<div id="wrap">
		<div class="g_content">
			<div class="container">
				<div class="breadcrumb">
					<?php echo the_breadcrumb(); ?>
				</div>
				<div class="row">
					<div class="col-sm-9 content_left">
						<div class="list_post_categories">
							<?php 
							while(have_posts()): the_post();
								get_template_part('includes/frontend/loop/loop_post');
							endwhile;
							get_template_part('includes/frontend/pagination/pagination'); 
							?>
						</div>
						<?php
					else:
					endif;
					wp_reset_postdata();
					?>
				</div>
				<?php  if(have_posts()) : ?>
					<div class="col-sm-3 sidebar">
						<?php dynamic_sidebar('sidebar1'); ?> 
					</div>
				<?php endif ?>
				
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>
