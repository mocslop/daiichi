<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php bloginfo('name'); ?></title>

	<link rel="shortcut icon" href="<?php echo BASE_URL; ?>/images/favicon.ico">
	<!-- css -->
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/slick.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/animate.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
	<!-- js -->
	<script src="<?php echo BASE_URL; ?>/js/jquery.min.js"></script>
	<?php wp_head(); ?>
</head>


<body <?php body_class() ?>>
	<div class="bg_opacity"></div>
	<?php if ( wp_is_mobile() ) { ?>
		<div id="menu_mobile_full">
			<nav class="mobile-menu">
				<p class="close_menu"><span><i class="fa fa-times" aria-hidden="true"></i></span></p>
				<?php 
				$args = array('theme_location' => 'menu_mobile');
				?>
				<?php wp_nav_menu($args);?>
			</nav>
		</div>
	<?php }?>
	<header class="header">
		<div class="top_header">
			<div class="container">
				<!-- display account top_header mobile -->
				<span class="icon_mobile_click"><i class="fa fa-bars" aria-hidden="true"></i></span>
				<div class="wrap_nav_logo">
					<div class="logo_site">
						<?php 
						if(has_custom_logo()){
							the_custom_logo();
						}
						else { ?> 
							<h2><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h2>
						<?php } ?>
					</div>
				</div>
				<div class="phone_search_hd">
					<div class="search_header">
						<form  role="search" method="get" id="searchform" action="<?php echo home_url('/');  ?>">
							<div class="wrap_search_header">
								<input type="text" value="" name="s" id="s" placeholder="Tìm kiếm">
								<button type="submit" id="searchsubmit"><i class="fa fa-search"></i></button>
							</div>
						</form>
					</div>
					<?php if(get_option('phone')){ ?>			
						<div class="phone_hd">
							<a href="tel:<?php echo get_option('phone'); ?>"><?php echo get_option('phone'); ?></a>
						</div>
					<?php }?>
				</div>
			</div>
		</div>
		<div class="middle_header">
			<div class="container">
				<nav class="nav nav_primary">
					<?php 
					$args = array('theme_location' => 'primary');
					?>
					<?php wp_nav_menu($args); ?>
				</nav>
			</div>
		</div>
		<?php if(is_front_page() && !is_home()){ ?>
			<?php echo do_shortcode('[smartslider3 slider=2]'); ?>
		<?php }?>
	</header>