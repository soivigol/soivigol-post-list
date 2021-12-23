<?php
/**
 * Plugin Name:     List posts and other contents
 * Description:     List of posts and other Custom Post Type with pagination, possibility to filter by category in posts, number of posts per page and number of columns.
 * Customization of the design of each items when it is posible select elements to show, custom title, custom padding of the content, select image aspect ratio and more features.
 * Version:         0.2
 * Plugin Uri:      https://www.davidviña.es
 * Author:          David Viña
 * Author URI:      https://www.davidviña.es
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     soivigol-post-list
 * Domain Path:     /languages
 *
 * @package         soivigol-post-list
 */

/**
 * Load localization files
 *
 * @return void
 */
function soivigol_language() {
	load_plugin_textdomain( 'soivigol-post-list', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'soivigol_language' );

/**
 * Filter the except length to 20 words.
 *
 * @param  int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function soivigol_custom_excerpt_length( $length ) {
	return 150;
}

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function post_list_soivigol_post_list_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";

	$all_custom_post_type = get_post_types();
	unset( $all_custom_post_type['page'] );
	unset( $all_custom_post_type['acf-field'] );
	unset( $all_custom_post_type['acf-field-group'] );
	unset( $all_custom_post_type['wp_template'] );
	unset( $all_custom_post_type['wp_block'] );
	unset( $all_custom_post_type['attachment'] );
	unset( $all_custom_post_type['revision'] );
	unset( $all_custom_post_type['nav_menu_item'] );
	unset( $all_custom_post_type['custom_css'] );
	unset( $all_custom_post_type['customize_changeset'] );
	unset( $all_custom_post_type['oembed_cache'] );
	unset( $all_custom_post_type['user_request'] );

	$custom_post_type = array();
	foreach ( $all_custom_post_type as $value ) {
		$custom_post_type[] = $value;
	}

	$index_js     = 'build/index.js';
	$script_asset = require $script_asset_path;
	wp_register_script(
		'post-list-soivigol-post-list-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version'],
		true
	);
	wp_localize_script(
		'post-list-soivigol-post-list-block-editor',
		'variables',
		array(
			'categorys'    => get_categories(),
			'custom_posts' => $custom_post_type,
		)
	);
	wp_set_script_translations( 'post-list-soivigol-post-list-block-editor', 'soivigol-post-list' );

	$editor_css = 'build/index.css';
	wp_register_style(
		'post-list-soivigol-post-list-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'build/style-index.css';
	wp_register_style(
		'post-list-soivigol-post-list-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type(
		'post-list/soivigol-post-list',
		array(
			'editor_script'   => 'post-list-soivigol-post-list-block-editor',
			'editor_style'    => 'post-list-soivigol-post-list-block-editor',
			'style'           => 'post-list-soivigol-post-list-block',
			'render_callback' => 'post_list_soivigol_callback',
			'attributes'      => array(
				'tipoPost'       => array(
					'type'    => 'string',
					'default' => 'post',
				),
				'category'       => array(
					'type' => 'string',
				),
				'pagination'     => array(
					'type' => 'boolean',
				),
				'numPosts'       => array(
					'type' => 'number',
				),
				'numCol'         => array(
					'type' => 'number',
				),
				'bgColor'        => array(
					'type' => 'string',
				),
				'textColor'      => array(
					'type' => 'string',
				),
				'bgColorH'       => array(
					'type' => 'string',
				),
				'textColorH'     => array(
					'type' => 'string',
				),
				'boxShadow'      => array(
					'type' => 'boolean',
				),
				'boxShadowH'     => array(
					'type' => 'boolean',
				),
				'borderRadius'   => array(
					'type'    => 'number',
					'default' => 10,
				),
				'borderSize'     => array(
					'type'    => 'number',
					'default' => 1,
				),
				'borderColor'    => array(
					'type'    => 'string',
					'default' => '#333',
				),
				'idBlock'        => array(
					'type' => 'number',
				),
				'aspectImage'    => array(
					'type' => 'string',
				),
				'titleColor'     => array(
					'type' => 'string',
				),
				'titleColorH'    => array(
					'type' => 'string',
				),
				'titleTag'       => array(
					'type' => 'string',
				),
				'titleSize'      => array(
					'type' => 'number',
				),
				'excertp'        => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'excertpLenth'   => array(
					'type'    => 'number',
					'default' => 30,
				),
				'readMore'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'labelReadMore'  => array(
					'type' => 'string',
				),
				'contentPadding' => array(
					'type' => 'number',
				),
			),
		)
	);
}
add_action( 'init', 'post_list_soivigol_post_list_block_init' );

/**
 * Function reder posts.
 *
 * @param array $attributes attributes for custom blocks.
 */
function post_list_soivigol_callback( $attributes ) {
	ob_start();

	add_filter( 'excerpt_length', 'soivigol_custom_excerpt_length', 999 );
	// We take the different variables of attributes.
	$tipo_post = $attributes['tipoPost'];
	if ( empty( $tipo_post ) ) {
		$tipo_post = 'post';
	}
	$category = $attributes['category'];
	if ( empty( $category ) ) {
		$category = null;
	}
	if ( 'all' === $category ) {
		$category = null;
	}

	$pagination = $attributes['pagination'];
	if ( empty( $pagination ) ) {
		$pagination = false;
	}

	$num_posts = $attributes['numPosts'];
	if ( empty( $num_posts ) ) {
		$num_posts = 9;
	}

	$num_col = $attributes['numCol'];
	if ( empty( $num_col ) ) {
		$num_col = 3;
	}

	$bg_color = $attributes['bgColor'];
	if ( empty( $bg_color ) ) {
		$bg_color = 'white';
	}

	$text_color = $attributes['textColor'];
	if ( empty( $text_color ) ) {
		$text_color = '#333';
	}

	$bg_color_h = $attributes['bgColorH'];
	if ( empty( $bg_color_h ) ) {
		$bg_color_h = 'white';
	}

	$text_color_h = $attributes['textColorH'];
	if ( empty( $text_color_h ) ) {
		$text_color_h = '#333';
	}

	$border_radius = $attributes['borderRadius'];
	if ( null === $border_radius ) {
		$border_radius = 10;
	}

	$box_shadow   = $attributes['boxShadow'];
	$clase_shadow = '';
	if ( $box_shadow ) {
		$clase_shadow = 'item-shadow';
	}

	$box_shadow_h   = $attributes['boxShadowH'];
	$clase_shadow_h = '';
	if ( $box_shadow_h ) {
		$clase_shadow_h = 'item-shadow-hover';
	}

	$id_block = $attributes['idBlock'];

	$title_color = $attributes['titleColor'];
	if ( empty( $title_color ) ) {
		$title_color = '#333';
	}

	$title_color_h = $attributes['titleColorH'];
	if ( empty( $title_color_h ) ) {
		$title_color_h = '#333';
	}

	$title_tag = $attributes['titleTag'];
	if ( empty( $title_tag ) ) {
		$title_tag = 'h3';
	}

	$title_size = $attributes['titleSize'];
	if ( null === $title_size ) {
		$title_size = 20;
	}

	$excertp = $attributes['excertp'];
	if ( null === $excertp ) {
		$excertp = true;
	}

	$read_more = $attributes['readMore'];
	if ( null === $read_more ) {
		$read_more = true;
	}

	$label_read_more = $attributes['labelReadMore'];
	if ( empty( $label_read_more ) ) {
		$label_read_more = __( 'Read more', 'soivigol-post-list' );
	}

	$content_padding = $attributes['contentPadding'];
	if ( null === $content_padding ) {
		$content_padding = 10;
	}

	// Args y query with variables.
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args  = array(
		'post_type'      => $tipo_post,
		'posts_per_page' => $num_posts,
		'paged'          => $paged,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'category_name'  => $category,
	);
	$query = new WP_Query( $args );
	echo '<style>
		.id-' . esc_html( $id_block ) . ' .item {
			background-color: ' . esc_html( $bg_color ) . ' ;
			border-radius: ' . esc_html( $border_radius ) . 'px;
			border: ' . esc_html( $attributes['borderSize'] ) . 'px solid ' . esc_html( $attributes['borderColor'] ) . ';
		}
		.id-' . esc_html( $id_block ) . ' .item:hover{
			background-color: ' . esc_html( $bg_color_h ) . ' ;
		}
		.id-' . esc_html( $id_block ) . ' .cont-image img {
			border-radius: ' . esc_html( $border_radius ) . 'px ' . esc_html( $border_radius ) . 'px 0 0;
		}
		.id-' . esc_html( $id_block ) . ' .item .content,
		.id-' . esc_html( $id_block ) . ' .item a {
			color: ' . esc_html( $text_color ) . ' ;
		}
		.id-' . esc_html( $id_block ) . ' .item:hover .content,
		.id-' . esc_html( $id_block ) . ' .item:hover a {
			color: ' . esc_html( $text_color_h ) . ' ;
		}

		.id-' . esc_html( $id_block ) . ' .item-title.item-title {
			color: ' . esc_html( $title_color ) . ' ;
			font-size: ' . esc_html( $title_size ) . 'px;
		}

		.id-' . esc_html( $id_block ) . ' .item:hover .item-title.item-title {
			color: ' . esc_html( $title_color_h ) . ' ;
		}

		.id-' . esc_html( $id_block ) . ' .content {
			padding: ' . esc_html( $content_padding ) . 'px ;
		}
	</style>';
	?>
	<div class="soivigol-post-list id-<?php echo esc_html( $id_block ); ?> col-<?php echo esc_html( $num_col ); ?> <?php echo ( esc_html( $clase_shadow ) ); ?> <?php echo ( esc_html( $clase_shadow_h ) ); ?>">
	<?php
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) :
			$query->the_post();
			if ( $read_more ) {
				echo '<div class="item">';
			} else {
				echo '<a href="' . esc_html( get_the_permalink() ) . '" class="item">';
			}
			if ( has_post_thumbnail() ) {
				echo '<div class="cont-image ' . esc_html( $attributes['aspectImage'] ) . '">';
				echo wp_kses_post( the_post_thumbnail( 'large' ) );
				echo '</div>';
			}
				echo '<div class="content">';
				do_action( 'spt_before_title_loop' );
				echo '<' . esc_html( $title_tag ) . ' class="item-title">' . esc_html( get_the_title() ) . '</' . esc_html( $title_tag ) . '>';
				do_action( 'spt_after_title_loop' );
			if ( $excertp ) {
				echo '<p>' . wp_kses_post( soivigol_get_excerpt( intval( $attributes['excertpLenth'] ), get_the_excerpt() ) ) . '</p>';
				do_action( 'spt_after_content_loop' );
			}
			if ( $read_more ) {
				echo '<p><a href="' . esc_url( get_the_permalink() ) . '">' . esc_html( $label_read_more ) . '</a></p>';
				do_action( 'spt_after_button_loop' );
			}
			echo '</div>';
			if ( $read_more ) {
				echo '</div>';
			} else {
				echo '</a>';
			}
		endwhile;
		wp_reset_postdata();

		echo '</dvi>';
		$total_pages = $query->max_num_pages;
		if ( $total_pages > 1 && $pagination ) {

			$current_page = max( 1, $paged );
			$base         = explode( '/page', get_pagenum_link( 1 ) );
			echo wp_kses_post(
				paginate_links(
					array(
						'base'      => $base[0] . '%_%',
						'format'    => 'page/%#%',
						'current'   => $current_page,
						'total'     => $total_pages,
						'prev_text' => __( 'Previous', 'soivigol-post-list' ),
						'next_text' => __( 'Next', 'soivigol-post-list' ),
					)
				)
			);
		}
	} else {
		?>
		<div>
			<?php __( 'There are no articles', 'soivigol-post-list' ); ?>
		</div>
		<?php
	}
	?>
	<?php
	return ob_get_clean();
}

/**
 * Custom excerpt with custom lenght
 *
 * @param int    $lenght Lenght of the excerpt.
 * @param string $content Content of the post.
 */
function soivigol_get_excerpt( $lenght, $content ) {
	$excerpt = substr( $content, 0, $lenght );

	return $excerpt;
}
