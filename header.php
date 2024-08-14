<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="<?php bloginfo('template_directory');?>/images/fav.png">
	<title><?php echo wp_get_document_title();?></title>
	<?php wp_head();?>
</head>
<body>
	<div class="b-top">
		<div class="container">
			<div class="b-top_inner">
				<p>Поблагодарите автора и пожертвуйте для дальнейшего развития сайта</p>
				<a href="<?php echo get_page_link(945); ?>" class="b-btn">Пожертвовать</a>
			</div>
		</div>
	</div>
	<header class="b-header">
		<div class="container">
			<div class="header-top">				
				<a href="/" class="b-logo">
					ДЫШУ ПРАВОСЛАВИЕМ
				</a>				
				<a target="_blank" href="https://fondsiluana.ru/" class="site-info">
					<img src="<?php bloginfo('template_directory');?>/images/logo.png"><span>Благотворительный фонд<span class="logo-small">св. преп. Силуана Афонского</span></span>
				</a>				
			</div>
			<div class="header-bottom">
				<?php wp_nav_menu( array('menu' => 'Меню', 'container' => 'nav', 'container_class'=>'main-menu',)); ?>
				<div class="b-search">
					<form id="searchform" method="get" action="/">
						<div>
							<?php
								if(isset($_GET['s'])) $search_val=$_GET['s'];
								elseif(isset($_GET['search'])) $search_val=$_GET['search'];
								else $search_val='';
							?>
							<input type="search" class="search-field" name="s" value="<?php echo $search_val; ?>" placeholder="Поиск" />
							<?php /*<span class="clear-search"></span>*/ ?>
							<div class="search-select">
								<?php
									$is_searchs=array(
										'site' => array('name' => 'на сайте','type' => 'site'),
										9368 => array('name' => 'в библиотеке','type' => 'library'),
										5536 => array('name' => 'по библии','type' => 'biblia'),
										'canon' => array('name' => 'по канонам','type' => 'canon')
									);
									$search_name='на сайте';
									$search_type='site';
									if(is_singular() && isset($is_searchs[$post->ID])){
										$search_name=$is_searchs[$post->ID]['name'];
										$search_type=$is_searchs[$post->ID]['type'];
									}
									if(is_search() && isset($_GET['type']) && isset($is_searchs[$_GET['type']])){
										$search_name=$is_searchs[$_GET['type']]['name'];
										$search_type=$is_searchs[$_GET['type']]['type'];
									}
								?>
								<div class="toggle-search"><?php echo $search_name; ?></div>
								<div class="search-down">
									<?php
									foreach($is_searchs as $is_search)
										echo '<span data-search="'.$is_search['type'].'">'.$is_search['name'].'</span>';
									?>
								</div>
							</div>
							<input type="hidden" name="type" value="<?php echo $search_type; ?>" />
							<input type="submit"/>
						</div>
					</form>
				</div>
				<div class="b-toggle">
					<span></span>
					<span></span>
					<span></span>
				</div>
			</div>
		</div>
	</header>