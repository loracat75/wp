<?php
define('HOME',get_option('page_on_front'));

/* Код ссылки на источник в function.php */
function script_copyright() {
	if(!is_page('5536')) { ?> 
		<script> 
			document.oncopy = function () { 
			var bodyElement = document.body; 
			var href = document.location.href; 
			var copyright = "<br>Информация взята из миссионерского православного портала: <a href='"+ href +"'>" + href + "</a>"; 
			var selection = getSelection();  
			var divElement = document.createElement('div'); 
			// cloned.innerHTML = astext.innerHTML = "";
			// Клонируем DOM-элементы из диапазонов (здесь мы поддерживаем множественное выделение)
			for (let i = 0; i < selection.rangeCount; i++) {
			  divElement.append(selection.getRangeAt(i).cloneContents());
			}
			// astext.innerHTML += selection;
			// var text = selection + copyright; 
			divElement.insertAdjacentHTML('beforeend', copyright);
			divElement.style.position = 'absolute'; 
			divElement.style.left = '-99999px'; 
			// divElement.innerHTML = text; 
			bodyElement.appendChild(divElement); 
			selection.selectAllChildren(divElement); 
			setTimeout(function() { 
				bodyElement.removeChild(divElement); }, 0); 
			}; 
		</script> 
	<?php } } 
add_action('wp_footer', 'script_copyright', 95);

function remove_wp_block_library_css(){
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' ); 
}
add_action( 'wp_enqueue_scripts', 'remove_wp_block_library_css', 100 );

/*подключение скриптов*/
add_action('wp_footer', 'add_scripts'); 
if (!function_exists('add_scripts')) { 
	function add_scripts() { 
	    if(is_admin()) return false; 
	    wp_deregister_script('jquery'); 
	    wp_enqueue_script('jquery', get_template_directory_uri().'/js/jquery-3.7.1.min.js',array(), null); 
	    wp_enqueue_script('slick', get_template_directory_uri().'/js/slick.min.js',array(), null); 
		if(is_page(11128)){
			wp_enqueue_script('djvu', get_template_directory_uri().'/js/djvu.js',array(), null); 
			wp_enqueue_script('djvu_viewer', get_template_directory_uri().'/js/djvu_viewer.js',array(), null); 
		}
	    wp_enqueue_script('scripts', get_template_directory_uri().'/js/scripts.js',array(), '1.0.4'); 
	}
}

/*подключение стилей*/
add_action('wp_print_styles', 'add_styles'); 
if (!function_exists('add_styles')) { 
	function add_styles() { 
	    if(is_admin()) return false; 
		wp_enqueue_style( 'slick', get_template_directory_uri().'/css/slick.css', array(), null ); 
		wp_enqueue_style( 'slicktheme', get_template_directory_uri().'/css/slick-theme.css', array(), null ); 
		wp_enqueue_style( 'main', get_template_directory_uri().'/style.css', array(), '1.0.3' ); 
	}
}

add_theme_support('html5',array('style','script'));

register_nav_menus();

/*миниатюры*/
add_theme_support('post-thumbnails'); 
add_image_size('big-thumb', 471, 350, true);

/*постраничная навигация*/
if (!function_exists('pagination')) { 
	function pagination() { 
		global $wp_query; 
		$big = 999999999; 
		$links = paginate_links(array( 
			'base' => str_replace($big,'%#%',esc_url(get_pagenum_link($big))), 
			'format' => '?paged=%#%', 
			'current' => max(1, get_query_var('paged')), 
			'type' => 'array', 
			'prev_text'    => '<', 
	    	'next_text'    => '>', 
			'total' => $wp_query->max_num_pages, 
			'show_all'     => false, 
			'end_size'     => 3, 
			'mid_size'     => 3, 
			'add_args'     => false, 
			'add_fragment' => '',	
			'before_page_number' => '', 
			'after_page_number' => '' 
		));
	 	if( is_array( $links ) ) { 
		    echo '<ul class="pagination">';
		    foreach ( $links as $link ) {
		    	if ( strpos( $link, 'current' ) !== false ) echo "<li class='active'>$link</li>"; 
		        else echo "<li>$link</li>"; 
		    }
		   	echo '</ul>';
		 }
	}
}
/* Хлебные крошки для WordPress (breadcrumbs) */
function kama_breadcrumbs( $sep = ' / ', $l10n = array(), $args = array() ){
	$kb = new Kama_Breadcrumbs;
	echo $kb->get_crumbs( $sep, $l10n, $args );
}

class Kama_Breadcrumbs {

	public $arg;

	// Локализация
	static $l10n = array(
		'home'       => 'Главная',
		'paged'      => 'Страница %d',
		'_404'       => 'Ошибка 404',
		'search'     => 'Результаты поиска по запросу - <b>%s</b>',
		'author'     => 'Архив автора: <b>%s</b>',
		'year'       => 'Архив за <b>%d</b> год',
		'month'      => 'Архив за: <b>%s</b>',
		'day'        => '',
		'attachment' => 'Медиа: %s',
		'tag'        => 'Записи по метке: <b>%s</b>',
		'tax_tag'    => '%1$s из "%2$s" по тегу: <b>%3$s</b>',
		// tax_tag выведет: 'тип_записи из "название_таксы" по тегу: имя_термина'.
		// Если нужны отдельные холдеры, например только имя термина, пишем так: 'записи по тегу: %3$s'
	);

	// Параметры по умолчанию
	static $args = array(
		'on_front_page'   => true,  // выводить крошки на главной странице
		'show_post_title' => true,  // показывать ли название записи в конце (последний элемент). Для записей, страниц, вложений
		'show_term_title' => true,  // показывать ли название элемента таксономии в конце (последний элемент). Для меток, рубрик и других такс
		'title_patt'      => '<span class="title">%s</span>', // шаблон для последнего заголовка. Если включено: show_post_title или show_term_title
		'last_sep'        => true,  // показывать последний разделитель, когда заголовок в конце не отображается
		'markup'          => 'schema.org', // 'markup' - микроразметка. Может быть: 'rdf.data-vocabulary.org', 'schema.org', '' - без микроразметки
										   // или можно указать свой массив разметки:
										   // array( 'wrappatt'=>'<div class="kama_breadcrumbs">%s</div>', 'linkpatt'=>'<a href="%s">%s</a>', 'sep_after'=>'', )
		'priority_tax'    => array('category'), // приоритетные таксономии, нужно когда запись в нескольких таксах
		'priority_terms'  => array(), // 'priority_terms' - приоритетные элементы таксономий, когда запись находится в нескольких элементах одной таксы одновременно.
									  // Например: array( 'category'=>array(45,'term_name'), 'tax_name'=>array(1,2,'name') )
									  // 'category' - такса для которой указываются приор. элементы: 45 - ID термина и 'term_name' - ярлык.
									  // порядок 45 и 'term_name' имеет значение: чем раньше тем важнее. Все указанные термины важнее неуказанных...
		'nofollow' => false, // добавлять rel=nofollow к ссылкам?

		// служебные
		'sep'             => '',
		'linkpatt'        => '',
		'pg_end'          => '',
	);

	function get_crumbs( $sep, $l10n, $args ){
		global $post, $wp_query, $wp_post_types;

		self::$args['sep'] = $sep;

		// Фильтрует дефолты и сливает
		$loc = (object) array_merge( apply_filters('kama_breadcrumbs_default_loc', self::$l10n ), $l10n );
		$arg = (object) array_merge( apply_filters('kama_breadcrumbs_default_args', self::$args ), $args );

		$arg->sep = '<span class="sep">'. $arg->sep .'</span>'; // дополним

		// упростим
		$sep = & $arg->sep;
		$this->arg = & $arg;

		// микроразметка ---
		if(1){
			$mark = & $arg->markup;

			// Разметка по умолчанию
			if( ! $mark ) $mark = array(
				'wrappatt'  => '<div class="breadcrumbs">%s</div>',
				'linkpatt'  => '<a href="%s">%s</a>',
				'sep_after' => '',
			);
			// rdf
			elseif( $mark === 'rdf.data-vocabulary.org' ) $mark = array(
				'wrappatt'   => '<div class="breadcrumbs" prefix="v: http://rdf.data-vocabulary.org/#">%s</div>',
				'linkpatt'   => '<span typeof="v:Breadcrumb"><a href="%s" rel="v:url" property="v:title">%s</a>',
				'sep_after'  => '</span>', // закрываем span после разделителя!
			);
			// schema.org
			elseif( $mark === 'schema.org' ) $mark = array(
				'wrappatt'   => '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">%s</div>',
				'linkpatt'   => '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="%s" itemprop="item"><span itemprop="name">%s</span><span itemprop="position" content="$d"></span></a></span>',
				'sep_after'  => '',
			);

			elseif( ! is_array($mark) )
				die( __CLASS__ .': "markup" parameter must be array...');

			$wrappatt  = $mark['wrappatt'];
			$arg->linkpatt  = $arg->nofollow ? str_replace('<a ','<a rel="nofollow"', $mark['linkpatt']) : $mark['linkpatt'];
			$arg->sep      .= $mark['sep_after']."\n";
		}

		$linkpatt = $arg->linkpatt; // упростим

		$q_obj = get_queried_object();

		// может это архив пустой таксы?
		$ptype = null;
		if( empty($post) ){
			if( isset($q_obj->taxonomy) )
				$ptype = & $wp_post_types[ get_taxonomy($q_obj->taxonomy)->object_type[0] ];
		}
		else $ptype = & $wp_post_types[ $post->post_type ];

		// paged
		$arg->pg_end = '';
		if( ($paged_num = get_query_var('paged')) || ($paged_num = get_query_var('page')) )
			$arg->pg_end = $sep . sprintf( $loc->paged, (int) $paged_num );

		$pg_end = $arg->pg_end; // упростим

		$out = '';

		if( is_front_page() ){
			return $arg->on_front_page ? sprintf( $wrappatt, ( $paged_num ? sprintf($linkpatt, get_home_url(), $loc->home) . $pg_end : $loc->home ) ) : '';
		}
		// страница записей, когда для главной установлена отдельная страница.
		elseif( is_home() ) {
			$out = $paged_num ? ( sprintf( $linkpatt, get_permalink($q_obj), esc_html($q_obj->post_title) ) . $pg_end ) : esc_html($q_obj->post_title);
		}
		elseif( is_404() ){
			$out = $loc->_404;
		}
		elseif( is_search() ){
			$out = sprintf( $loc->search, esc_html( $GLOBALS['s'] ) );
		}
		elseif( is_author() ){
			$tit = sprintf( $loc->author, esc_html($q_obj->display_name) );
			$out = ( $paged_num ? sprintf( $linkpatt, get_author_posts_url( $q_obj->ID, $q_obj->user_nicename ) . $pg_end, $tit ) : $tit );
		}
		elseif( is_year() || is_month() || is_day() ){
			$y_url  = get_year_link( $year = get_the_time('Y') );

			if( is_year() ){
				$tit = sprintf( $loc->year, $year );
				$out = ( $paged_num ? sprintf($linkpatt, $y_url, $tit) . $pg_end : $tit );
			}
			// month day
			else {
				$y_link = sprintf( $linkpatt, $y_url, $year);
				$m_url  = get_month_link( $year, get_the_time('m') );

				if( is_month() ){
					$tit = sprintf( $loc->month, get_the_time('F') );
					$out = $y_link . $sep . ( $paged_num ? sprintf( $linkpatt, $m_url, $tit ) . $pg_end : $tit );
				}
				elseif( is_day() ){
					$m_link = sprintf( $linkpatt, $m_url, get_the_time('F'));
					$out = $y_link . $sep . $m_link . $sep . get_the_time('l');
				}
			}
		}
		// Древовидные записи
		elseif( is_singular() && $ptype->hierarchical ){
			$out = $this->_add_title( $this->_page_crumbs($post), $post );
		}
		// Таксы, плоские записи и вложения
		else {
			$term = $q_obj; // таксономии

			// определяем термин для записей (включая вложения attachments)
			if( is_singular() ){
				// изменим $post, чтобы определить термин родителя вложения
				if( is_attachment() && $post->post_parent ){
					$save_post = $post; // сохраним
					$post = get_post($post->post_parent);
				}

				// учитывает если вложения прикрепляются к таксам древовидным - все бывает :)
				$taxonomies = get_object_taxonomies( $post->post_type );
				// оставим только древовидные и публичные, мало ли...
				$taxonomies = array_intersect( $taxonomies, get_taxonomies( array('hierarchical' => true, 'public' => true) ) );

				if( $taxonomies ){
					// сортируем по приоритету
					if( ! empty($arg->priority_tax) ){
						usort( $taxonomies, function($a,$b)use($arg){
							$a_index = array_search($a, $arg->priority_tax);
							if( $a_index === false ) $a_index = 9999999;

							$b_index = array_search($b, $arg->priority_tax);
							if( $b_index === false ) $b_index = 9999999;

							return ( $b_index === $a_index ) ? 0 : ( $b_index < $a_index ? 1 : -1 ); // меньше индекс - выше
						} );
					}

					// пробуем получить термины, в порядке приоритета такс
					foreach( $taxonomies as $taxname ){
						if( $terms = get_the_terms( $post->ID, $taxname ) ){
							// проверим приоритетные термины для таксы
							$prior_terms = & $arg->priority_terms[ $taxname ];
							if( $prior_terms && count($terms) > 2 ){
								foreach( (array) $prior_terms as $term_id ){
									$filter_field = is_numeric($term_id) ? 'term_id' : 'slug';
									$_terms = wp_list_filter( $terms, array($filter_field=>$term_id) );

									if( $_terms ){
										$term = array_shift( $_terms );
										break;
									}
								}
							}
							else
								$term = array_shift( $terms );

							break;
						}
					}
				}

				if( isset($save_post) ) $post = $save_post; // вернем обратно (для вложений)
			}

			// вывод

			// все виды записей с терминами или термины
			if( $term && isset($term->term_id) ){
				$term = apply_filters('kama_breadcrumbs_term', $term );

				// attachment
				if( is_attachment() ){
					if( ! $post->post_parent )
						$out = sprintf( $loc->attachment, esc_html($post->post_title) );
					else {
						if( ! $out = apply_filters('attachment_tax_crumbs', '', $term, $this ) ){
							$_crumbs    = $this->_tax_crumbs( $term, 'self' );
							$parent_tit = sprintf( $linkpatt, get_permalink($post->post_parent), get_the_title($post->post_parent) );
							$_out = implode( $sep, array($_crumbs, $parent_tit) );
							$out = $this->_add_title( $_out, $post );
						}
					}
				}
				// single
				elseif( is_single() ){
					if( ! $out = apply_filters('post_tax_crumbs', '', $term, $this ) ){
						$_crumbs = $this->_tax_crumbs( $term, 'self' );
						$out = $this->_add_title( $_crumbs, $post );
					}
				}
				// не древовидная такса (метки)
				elseif( ! is_taxonomy_hierarchical($term->taxonomy) ){
					// метка
					if( is_tag() )
						$out = $this->_add_title('', $term, sprintf( $loc->tag, esc_html($term->name) ) );
					// такса
					elseif( is_tax() ){
						$post_label = $ptype->labels->name;
						$tax_label = $GLOBALS['wp_taxonomies'][ $term->taxonomy ]->labels->name;
						$out = $this->_add_title('', $term, sprintf( $loc->tax_tag, $post_label, $tax_label, esc_html($term->name) ) );
					}
				}
				// древовидная такса (рибрики)
				else {
					if( ! $out = apply_filters('term_tax_crumbs', '', $term, $this ) ){
						$_crumbs = $this->_tax_crumbs( $term, 'parent' );
						$out = $this->_add_title( $_crumbs, $term, esc_html($term->name) );                     
					}
				}
			}
			// влоежния от записи без терминов
			elseif( is_attachment() ){
				$parent = get_post($post->post_parent);
				$parent_link = sprintf( $linkpatt, get_permalink($parent), esc_html($parent->post_title) );
				$_out = $parent_link;

				// вложение от записи древовидного типа записи
				if( is_post_type_hierarchical($parent->post_type) ){
					$parent_crumbs = $this->_page_crumbs($parent);
					$_out = implode( $sep, array( $parent_crumbs, $parent_link ) );
				}

				$out = $this->_add_title( $_out, $post );
			}
			// записи без терминов
			elseif( is_singular() ){
				$out = $this->_add_title( '', $post );
			}
		}

		// замена ссылки на архивную страницу для типа записи
		$home_after = apply_filters('kama_breadcrumbs_home_after', '', $linkpatt, $sep, $ptype );

		if( '' === $home_after ){
			// Ссылка на архивную страницу типа записи для: отдельных страниц этого типа; архивов этого типа; таксономий связанных с этим типом.
			if( $ptype && $ptype->has_archive && ! in_array( $ptype->name, array('post','page','attachment') )
				&& ( is_post_type_archive() || is_singular() || (is_tax() && in_array($term->taxonomy, $ptype->taxonomies)) )
			){
				$pt_title = $ptype->labels->name;

				// первая страница архива типа записи
				if( is_post_type_archive() && ! $paged_num )
					$home_after = sprintf( $this->arg->title_patt, $pt_title );
				// singular, paged post_type_archive, tax
				else{
					$home_after = sprintf( $linkpatt, get_post_type_archive_link($ptype->name), $pt_title );

					$home_after .= ( ($paged_num && ! is_tax()) ? $pg_end : $sep ); // пагинация
				}
			}
		}

		$before_out = sprintf( $linkpatt, home_url(), $loc->home ) . ( $home_after ? $sep.$home_after : ($out ? $sep : '') );

		$out = apply_filters('kama_breadcrumbs_pre_out', $out, $sep, $loc, $arg );

		$out = sprintf( $wrappatt, $before_out . $out );

		return apply_filters('kama_breadcrumbs', $out, $sep, $loc, $arg );
	}

	function _page_crumbs( $post ){
		$parent = $post->post_parent;

		$crumbs = array();
		while( $parent ){
			$page = get_post( $parent );
			$crumbs[] = sprintf( $this->arg->linkpatt, get_permalink($page), esc_html($page->post_title) );
			$parent = $page->post_parent;
		}

		return implode( $this->arg->sep, array_reverse($crumbs) );
	}

	function _tax_crumbs( $term, $start_from = 'self' ){
		$termlinks = array();
		$term_id = ($start_from === 'parent') ? $term->parent : $term->term_id;
		while( $term_id ){
			$term       = get_term( $term_id, $term->taxonomy );
			$termlinks[] = sprintf( $this->arg->linkpatt, get_term_link($term), esc_html($term->name) );
			$term_id    = $term->parent;
		}

		if( $termlinks )
			return implode( $this->arg->sep, array_reverse($termlinks) ) /*. $this->arg->sep*/;
		return '';
	}

	// добалвяет заголовок к переданному тексту, с учетом всех опций. Добавляет разделитель в начало, если надо.
	function _add_title( $add_to, $obj, $term_title = '' ){
		$arg = & $this->arg; // упростим...
		$title = $term_title ? $term_title : esc_html($obj->post_title); // $term_title чиститься отдельно, теги моугт быть...
		$show_title = $term_title ? $arg->show_term_title : $arg->show_post_title;

		// пагинация
		if( $arg->pg_end ){
			$link = $term_title ? get_term_link($obj) : get_permalink($obj);
			$add_to .= ($add_to ? $arg->sep : '') . sprintf( $arg->linkpatt, $link, $title ) . $arg->pg_end;
		}
		// дополняем - ставим sep
		elseif( $add_to ){
			if( $show_title )
				$add_to .= $arg->sep . sprintf( $arg->title_patt, $title );
			elseif( $arg->last_sep )
				$add_to .= $arg->sep;
		}
		// sep будет потом...
		elseif( $show_title )
			$add_to = sprintf( $arg->title_patt, $title );

		return $add_to;
	}

}
/* End Breadcrumbs */

/*ограничение слов в превью постов ---------------------------*/
add_filter( 'excerpt_length', function(){
	return 60;
} );

add_filter( 'excerpt_more', function( $more ) {
	return '...';
} );
/*убирает <br> в контактной форме ------------------*/
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/**
 * Функция для изменения части WHERE запроса SQL для библиотеки.
 *
 * @param  string $where Предложение WHERE запроса.
 * @return string        Изменённый WHERE запрос.
 */
function os_restrict_by_first_letter( $where ) {

	// Условие проверяет наличие GET запроса с параметром 'az'.
	if ( isset( $_GET['az'] ) ) {

		// Глобализация переменной $wpdb.
		global $wpdb;

		// Изменения касаются только страниц архива.
		if ( ! is_tag() && ! is_date() && is_archive() && is_main_query() ) {

			// Устанавливается значение из параметра 'az'.
			$where .= $wpdb->prepare( " AND SUBSTRING( {$wpdb->posts}.post_title, 1, 1 ) = %s ", sanitize_text_field( wp_unslash( $_GET['az'] ) ) );
		}
	}

	// Возвращаются изменённые данные.
	return $where;
}

// Установка фильтра для хука 'posts_where'.
// add_filter( 'posts_where', 'os_restrict_by_first_letter' );

function remove_image_dimensions( $html ) {

   $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );

   return $html;
}
add_filter( 'get_image_tag', 'remove_width_and_height_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_image_dimensions', 10 );

add_filter('wp_calculate_image_srcset_meta', '__return_null' );
add_filter('wp_calculate_image_sizes', '__return_false',  99 );
remove_filter('the_content', 'wp_make_content_images_responsive' );
add_filter('wp_get_attachment_image_attributes', 'unset_attach_srcset_attr', 99 );
function unset_attach_srcset_attr( $attr ){
	foreach( array('sizes','srcset') as $key )
		if( isset($attr[ $key ]) )    unset($attr[ $key ]);
	return $attr;
}

function ph($content){
  $pattern="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
  $replacement='<a$1href=$2$3.$4$5 rel="lightbox[roadtrip]" $6>';
  $content=preg_replace($pattern,$replacement,$content);
  return $content;
 }

add_filter('the_content','ph');

## делает IMG тег анкором ссылки на картинку указанную в этом теге, чтобы её можно было увеличить и посмотреть.
add_filter( 'the_content', function( $content ){

  // пропускаем если в тексте нет картинок вообще...
  if( false === strpos( $content, '<img ') )
	return $content;

  if( ! is_main_query() || ! in_array( $GLOBALS['post']->post_type, ['post'] ) )
	return $content;

  $img_ex = '<img[^>]*src *= *["\']([^\'"]+)["\'][^>]*>';
  $content = preg_replace_callback( "~(?:<a[^>]+>\s*)$img_ex|($img_ex)~", function($mm){
	// пропускаем, если картинка уже со ссылкой
	if( empty($mm[2]) )
	  return $mm[0];

	return '<a href="'. $mm[3] .'">'. $mm[2] .'</a>';
  }, $content );

  return $content;
}, 5 );

	add_action('pre_get_posts','custom_posts_per_page');
	function custom_posts_per_page($query){
		if(is_search() && $query->is_main_query())
			$query->set('posts_per_page',50);
	}


add_action('template_redirect','search_select');
function search_select() {
	if(isset($_GET['type'])){
		if($_GET['type']=='library'){
			wp_redirect(add_query_arg(array('search' => $_GET['s']),get_permalink(9368)),302);
			exit;
		}
		if($_GET['type']=='biblia'){
			wp_redirect(add_query_arg(array('search' => $_GET['s']),get_permalink(5536)),302);			
			exit;
		} 
	}
}
// add_action( 'search_hook', 'search_select' );
 // do_action( 'search_hook', '' );
?>