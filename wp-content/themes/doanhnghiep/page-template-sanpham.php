<?php 
/*
Template Name: page-template-sanpham
*/
get_header(); 
?>	
<div class="page-wrapper">
	<div class="g_content">
		<div class="container"> 
            <div class="row">
              <div class="col-sm-9">
                <ul class="list_categories">    
                    <li class="parent_cat">Sản phẩm</li>                      
                </ul>
                <ul>
                    <?php
                    $args = array(  
                        'post_type' => 'product',
                        'post_status' => 'publish',
                        'posts_per_page' => 4, 
                        'orderby' => 'title', 
                        'order' => 'ASC'
                    );
                    $loop_product = new WP_Query( $args ); 
                    while ( $loop_product->have_posts() ) : $loop_product->the_post(); 
        //echo the_title();
                        ?> <li class="list_post_item pw">
                            <?php  $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );  ?>
                            <figure class="thumbnail" style="background:url('<?php echo $image[0]; ?>');"><a href="<?php the_permalink(); ?>"><?php //the_post_thumbnail();?></a> </figure>
                            <div class="post_wrapper_content">
                                <h2 class="post_title"><a href="<?php echo the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <div class="post_meta">
                                    <p><?php the_time('d/m/Y');?></p>
                                </div>


                                <?php if(is_search() OR is_archive()){?>
                                    <p><?php echo excerpt(28); ?></p>
                                    <a class="readmore" href="<?php echo the_permalink(); ?>">Xem thêm <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                                <?php } 
                                else {
                                    if($post->post_excerpt){ ?>
                                        <div class="excerpt"><p><?php echo excerpt(35); ?></p></div>
                                        <a class="readmore" href="<?php echo the_permalink(); ?>">Xem thêm <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                                    <?php } else{
                                        the_content();
                                    } 
                                } ?>

                            </div>
                        </li>

                        <?php
                    endwhile;
                    wp_reset_query();
                    ?>
                </ul>
            </div>
            <div class="col-sm-3 sidebar">
                <?php dynamic_sidebar('sidebar1'); ?> 
            </div>  
        </div>

    </div><!-- container -->
</div>
</div>
<?php get_footer(); ?>

