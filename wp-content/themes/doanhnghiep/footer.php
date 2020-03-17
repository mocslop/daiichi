	<div class="pay_qlbh">
		<div class="container">
					<h4 class="title_under_before"><span><?php  echo get_cat_name('20'); ?></span></h4>
			<div class="row">
			<?php 
			$args_qlbh = array(  
				'post_status' => 'publish',
				'orderby' => 'title', 
				'order' => 'ASC',
				'cat' => 20
			);
			$loop_pay_qlbh = new WP_Query( $args_qlbh ); 
			while($loop_pay_qlbh->have_posts()): $loop_pay_qlbh->the_post();
				?>
					<div class="col-sm-3 ">
						<?php  $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );  ?>
						<figure class="thumbnail" style="background:url('<?php echo $image[0]; ?>');"><a href="<?php the_permalink(); ?>"><?php //the_post_thumbnail();?></a> </figure>
						<div class="post_wrapper_content">
							<h2 class="post_title"><a href="<?php echo the_permalink(); ?>"><?php the_title(); ?></a></h2>
						</div>
					</div>
				<?php
			endwhile;
			wp_reset_query();
			?>
			</div>
		</div>
	</div>
	<div class="partners">
		<div class="container">
			<h4 class="title_under_before"><span>Đối tác bảo lãnh viện phí</span></h4>
			<ul>
				<?php
				$args = array(  
					'post_type' => 'partners',
					'post_status' => 'publish',
					'posts_per_page' => 20, 
					'orderby' => 'title', 
					'order' => 'ASC'
				);

				$loop_partner = new WP_Query( $args ); 

				while ( $loop_partner->have_posts() ) : $loop_partner->the_post(); 
    	//echo the_title();
					?> <li class="pw"> <figure class="thumbnail"> <a href="<?php echo get_the_excerpt(); ?>" target="_blank"><?php the_post_thumbnail();?></a> </figure> </li> <?php
				endwhile;
				wp_reset_query();
				?>
			</ul>
		</div>
	</div>	
	<div class="scrolltop">
		<i class="fa fa-angle-up" aria-hidden="true"></i>	
	</div>			
	<footer class="footer">
		<div class="container">
			<?php
			$args = array(
				'post_type' => 'page',
          'post__in' => array(744) //list of page_ids
      );
			$page_query = new WP_Query( $args );
			if( $page_query->have_posts() ) :
        //print any general title or any header here//
				while( $page_query->have_posts() ) : $page_query->the_post();
					echo '<div class="page-on-page" id="page_id-' . $post->ID . '">';
					echo the_content();
					echo '</div>';
				endwhile;
			else:
        //optional text here is no pages found//
			endif;
			wp_reset_postdata();
			?>
		</div>
	</footer>
	<div class="popup popup_order" data-backdrop="static" data-keyboard="false" >
		<div class="content_popup">
			<h2>Đặt mua sản phẩm</h2>
			<div class="col-sm-6">
				<?php echo do_shortcode('[contact-form-7 id="478" title="Form liên hệ popup"]'); ?>
			</div>
			<div class="col-sm-6">
				<figure class="img_product_pop"><img src="<?php echo BASE_URL; ?>/images/anh_dha_canxi.png"></figure>
			</div>
			<div class="close_popup" data-dismiss="modal">
				<i class="fa fa-times" aria-hidden="true"></i>
			</div>
		</div>
	</div>
	<?php wp_footer(); ?>
	<!-- END  MESSENGER -->
	<script src="<?php echo BASE_URL; ?>/js/wow.min.js"></script>
	<script src="<?php echo BASE_URL; ?>/js/slick.js"></script>
	<script src="<?php echo BASE_URL; ?>/js/bootstrap.min.js"></script>
	<script src="<?php echo BASE_URL; ?>/js/custom.js"></script>
</body>
</html>
