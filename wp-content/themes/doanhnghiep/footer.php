<div class="scrolltop">
	<i class="fa fa-angle-up" aria-hidden="true"></i>	
</div>

<div class="order_area_ft">
					<div class="container">
						<div class="zalo_order">
							<div class="panel-grid-cell">
								<a href="<?php echo home_url(); ?>/diem-ban">Đặt mua trực tuyến <br> DHA Canxi </a>
							</div>
							<?php if(get_option('phone')){ ?>
							<div class="panel-grid-cell">
								<div class="textwidget"><h2>Kết nối zalo</h2>
									<p><a href="https://zalo.me/<?php  echo get_option('phone');  ?>"><?php  echo get_option('phone');  ?></a></p>
									<h4>Gọi<br>
									ngay</h4>
								</div>
							</div>
						<?php } ?>
						</div>
					</div>
				</div>
				
<footer class="footer">
	<div class="container">
		<div class="row">
			<?php if(is_active_sidebar('footer1')) {?>
				<div class="footer-widget-area col-sm-6">
					<?php dynamic_sidebar('footer1'); ?>
				</div>
			<?php } ?>  

			<?php if(is_active_sidebar('footer2')) :?>
				<div class="footer-widget-area col-sm-6">
					<?php dynamic_sidebar('footer2'); ?>
				</div>
			<?php endif ?> 
			<div class="copyright">
				<p>©2019 DHA CANXIBONE GOLD</p>
			</div>
		</div>
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
