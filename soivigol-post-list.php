<?php
/**
 * Plugin Name:     Posts list
 * Description:     List of posts with pagination, possibility to filter by category, number of posts per page, number of columns and customization of the design of the items.
 * Version:         0.1.0
 * Plugin Uri:      https://www.soivigol.es
 * Author:          David Viña , Destaca
 * Author URI:      https://www.destaca.es
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
	return 20;
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
			'categorys' => get_categories(),
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
				'tipoPost'     => array(
					'type'    => 'string',
					'default' => 'post',
				),
				'category'     => array(
					'type' => 'string',
				),
				'pagination'   => array(
					'type' => 'boolean',
				),
				'numPosts'     => array(
					'type' => 'number',
				),
				'numCol'       => array(
					'type' => 'number',
				),
				'bgColor'      => array(
					'type' => 'string',
				),
				'textColor'    => array(
					'type' => 'string',
				),
				'bgColorH'     => array(
					'type' => 'string',
				),
				'textColorH'   => array(
					'type' => 'string',
				),
				'boxShadow'    => array(
					'type' => 'boolean',
				),
				'boxShadowH'   => array(
					'type' => 'boolean',
				),
				'borderRadius' => array(
					'type' => 'number',
				),
				'idBlock'      => array(
					'type' => 'string',
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
	if ( empty( $border_radius ) ) {
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
	$query = new WP_Query( $args )
	?>
	<style>
		.id-<?php echo esc_html( $id_block ); ?> .item {
			background-color: <?php echo esc_html( $bg_color ); ?> ;
			border-radius: <?php echo esc_html( $border_radius ); ?>px;
		}
		.id-<?php echo esc_html( $id_block ); ?> .item:hover{
			background-color: <?php echo esc_html( $bg_color_h ); ?> ;
		}
		.id-<?php echo esc_html( $id_block ); ?> .cont-image img {
			border-radius: <?php echo esc_html( $border_radius ); ?>px <?php echo esc_html( $border_radius ); ?>px 0 0;
		}
		.id-<?php echo esc_html( $id_block ); ?> .item p,
		.id-<?php echo esc_html( $id_block ); ?> .item-title {
			color: <?php echo esc_html( $text_color ); ?> ;
		}
		.id-<?php echo esc_html( $id_block ); ?> .item:hover p,
		.id-<?php echo esc_html( $id_block ); ?> .item:hover .item-title {
			color: <?php echo esc_html( $text_color_h ); ?> ;
		}
	</style>
	<div class="soivigol-post-list id-<?php echo esc_html( $id_block ); ?> col-<?php echo esc_html( $num_col ); ?> <?php echo ( esc_html( $clase_shadow ) ); ?> <?php echo ( esc_html( $clase_shadow_h ) ); ?>">
	<?php
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) :
			$query->the_post();
			?>
			<a href="<?php the_permalink(); ?>" class="item">
				<div class="cont-image">
					<?php the_post_thumbnail( 'medium' ); ?>
				</div>
				<h3 class="item-title"><?php the_title(); ?></h3>
				<?php the_excerpt(); ?>
			</a>
			<?php
		endwhile;
		wp_reset_postdata();

		?>
	</div>
		<?php
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
