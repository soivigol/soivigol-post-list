<?php
/**
 * Plugin Name:     Listado de entradas
 * Description:     Listado de entradas con paginación, posibilidad de filtrar por categoria, número de entradas por página, número de columnas y personalización del diseño de los items.
 * Version:         0.1.0
 * Author:          David Viña
 * Author URI:      https://www.davidviña.es
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     soivigol-post-archive
 * Domain Path:     /languages
 *
 * @package         post-archive
 */

/**
 * Load localization files
 *
 * @return void
 */
function soivigol_language() {
	load_plugin_textdomain( 'soivigol-post-archive', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
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
function post_archive_soivigol_post_archive_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";

	$index_js     = 'build/index.js';
	$script_asset = require $script_asset_path;
	wp_register_script(
		'post-archive-soivigol-post-archive-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version'],
		true
	);
	wp_localize_script(
		'post-archive-soivigol-post-archive-block-editor',
		'variables',
		array(
			'categorys' => get_categories(),
		)
	);
	wp_set_script_translations( 'post-archive-soivigol-post-archive-block-editor', 'soivigol-post-archive' );

	$editor_css = 'build/index.css';
	wp_register_style(
		'post-archive-soivigol-post-archive-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'build/style-index.css';
	wp_register_style(
		'post-archive-soivigol-post-archive-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type(
		'post-archive/soivigol-post-archive',
		array(
			'editor_script'   => 'post-archive-soivigol-post-archive-block-editor',
			'editor_style'    => 'post-archive-soivigol-post-archive-block-editor',
			'style'           => 'post-archive-soivigol-post-archive-block',
			'render_callback' => 'post_archive_soivigol_callback',
			'attributes'      => array(
				'category'        => array(
					'type' => 'string',
				),
				'numPosts'        => array(
					'type' => 'number',
				),
				'numCol'          => array(
					'type' => 'number',
				),
				'backgroundColor' => array(
					'type' => 'string',
				),
				'textColor'       => array(
					'type' => 'string',
				),
				'borderRadius'    => array(
					'type' => 'number',
				),
				'boxShadow'       => array(
					'type' => 'boolean',
				),
				'idBlock'         => array(
					'type' => 'string',
				),
			),
		),
	);
}
add_action( 'init', 'post_archive_soivigol_post_archive_block_init' );

/**
 * Function reder posts.
 *
 * @param array $attributes attributes for custom blocks.
 */
function post_archive_soivigol_callback( $attributes ) {
	ob_start();

	add_filter( 'excerpt_length', 'soivigol_custom_excerpt_length', 999 );
	$category = $attributes['category'];
	if ( empty( $category ) ) {
		$category = null;
	}
	if ( 'all' === $category ) {
		$category = null;
	}

	$num_posts = $attributes['numPosts'];
	if ( empty( $num_posts ) ) {
		$num_posts = 12;
	}

	$num_col = $attributes['numCol'];
	if ( empty( $num_col ) ) {
		$num_col = 3;
	}

	$background_color = $attributes['backgroundColor'];
	if ( empty( $background_color ) ) {
		$background_color = 'white';
	}

	$text_color = $attributes['textColor'];
	if ( empty( $text_color ) ) {
		$text_color = '#333';
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

	$id_block = $attributes['idBlock'];

	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args  = array(
		'posts_per_page' => $num_posts,
		'paged'          => $paged,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'category_name'  => $category,
	);
	$query = new WP_Query( $args );
	?>
	<style>
		.id-<?php echo esc_html( $id_block ); ?> .item {
			background-color: <?php echo esc_html( $background_color ); ?> ;
			border-radius: <?php echo esc_html( $border_radius ); ?>px;
		}
		.id-<?php echo esc_html( $id_block ); ?> .cont-image img {
			border-radius: <?php echo esc_html( $border_radius ); ?>px <?php echo esc_html( $border_radius ); ?>px 0 0;
		}
		.id-<?php echo esc_html( $id_block ); ?> .item p,
		.id-<?php echo esc_html( $id_block ); ?> .item-title {
			color: <?php echo esc_html( $text_color ); ?> ;
		}
	</style>
	<div class="soivigol-list-post id-<?php echo esc_html( $id_block ); ?> col-<?php echo esc_html( $num_col ); ?> <?php echo ( esc_html( $clase_shadow ) ); ?>">
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

		$total_pages = $query->max_num_pages;
		?>
	</div>
		<?php
		if ( $total_pages > 1 ) {

			$current_page = max( 1, $paged );
			$base         = explode( '/page', get_pagenum_link( 1 ) );
			echo wp_kses_post(
				paginate_links(
					array(
						'base'      => $base[0] . '%_%',
						'format'    => 'page/%#%',
						'current'   => $current_page,
						'total'     => $total_pages,
						'prev_text' => __( 'Anterior', 'soivigol-post-archive' ),
						'next_text' => __( 'Siguiente', 'soivigol-post-archive' ),
					)
				)
			);
		}
	}
	?>
	<?php
	return ob_get_clean();
}
