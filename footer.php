		<footer class="b-footer">
			<div class="container">
				<div class="footer-inner">
					<div class="footer-left">
						<a href="/" class="b-logo">
								ДЫШУ ПРАВОСЛАВИЕМ
						</a>
						<div class="btn-wrap">
							<a href="<?php echo get_page_link(945); ?>" class="b-btn">
								Пожертвовать
							</a>
							<a href="<?php echo get_page_link(16); ?>" class="b-btn_transparent">
								Задать вопрос
							</a>
						</div>
					</div>
					<div class="footer-right">
						<div class="b-footer_toggle">
							Меню
						</div>
						<?php wp_nav_menu( array('menu' => 'Меню', 'container' => 'nav', 'container_class'=>'bottom-menu',)); ?>
						<a href="<?php echo get_page_link(9373); ?>" class="b-privacy">Политика конфиденциальности</a>
						<a href="#" class="up">Вверх</a>
					</div>
				</div>
				
			</div>
		</footer>
		<div class="top-up">
		</div>
		<div id='loadingmessage' style='display:none'>
		  <img src='<?php bloginfo('template_directory');?>/images/loading.svg'/>
		</div>
		<div class="overlay">
		</div>
		<div class="adv-popup">
			<div class="adv-close"></div>
			<h3 class="text-center">Подпишитесь на нашу группу Вконтакте</h3>
			<p><a href="https://vk.com/public64589359" target="_blank"><img src="<?php bloginfo('template_directory');?>/images/vk.png"></a></p>
			<label><input class="input-check" id="b-stop" type="checkbox"><span>Больше не показывать</span></label>
		</div>
		<?php wp_footer();?>
	</body>
</html>