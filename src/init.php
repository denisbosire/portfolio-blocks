<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * Assets enqueued:
 * 1. blocks.style.build.css - Frontend + Backend.
 * 2. blocks.build.js - Backend. 
 * 3. blocks.editor.build.css - Backend.
 *
 * @uses  {wp-blocks} for block type registration & related functions.
 * @uses  {wp-element} for WP Element abstraction — structure of blocks.
 * @uses  {wp-i18n} to internationalize the block's text.
 * @uses  {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function portfolio_block_cgb_block_assets() { // phpcs:ignore
	// Register block styles for both frontend + backend.
	wp_register_style(
	'portfolio_block-cgb-style-css', // Handle.
	plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
	is_admin() ? array( 'wp-editor' ) : null, // Dependency to include the CSS after it.
	null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);

	// Register block editor script for backend.
	wp_register_script(
	'portfolio_block-cgb-block-js', // Handle.
	plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
	array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', ), // Dependencies, defined above.
	null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
	true // Enqueue the script in the footer.
	);

	// Register block editor styles for backend.
	wp_register_style(
	'portfolio_block-cgb-block-editor-css', // Handle.
	plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
	array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
	null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);
	wp_enqueue_script(
		'portfolio-custom-scripts',
		plugins_url( 'src/library/portfolio.js',
		dirname( __FILE__ ) ), 
		array( 'jquery')
	);

	// WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
	wp_localize_script(
	'portfolio_block-cgb-block-js',
	'cgbGlobal', // Array containing dynamic data for a JS Global.
	[
	'pluginDirPath' => plugin_dir_path( __DIR__ ),
	'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
	// Add more data here that you want to access from `cgbGlobal` object.
	]
	);

	/**
	 * Register Gutenberg block on server-side.
	 *
	 * Register the block on server-side to ensure that the block
	 * scripts and styles for both frontend and backend are
	 * enqueued when the editor loads.
	 *
	 * @link  https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
	 * @since 1.16.0
	 */ 
	register_block_type(
	'blox/portfolio-block', array( 
	'script' => 'portfolio-custom-scripts',
    // Enqueue blocks.style.build.css on both frontend & backend.
    'style'         => 'portfolio_block-cgb-style-css',
    // Enqueue blocks.build.js in the editor only.
    'editor_script' => 'portfolio_block-cgb-block-js',
    // Enqueue blocks.editor.build.css in the editor only.
    'editor_style'  => 'portfolio_block-cgb-block-editor-css',
    //render php callback
	'render_callback' => 'portfolio_block',
	
	'attributes'      => array(
		'alignment' => array(
			'type' => 'string',
			'default' => 'wide',
		),
		'filterAlignment' => array(
			'type' => 'string',
			'default' => 'left',
		),
		'align' => array(
			'type' => 'string',
			'default' => 'wide',
		),
		'columns'      => array(
			'type' => 'number',
			'default' => 3,
		
		),
		'gutter'      => array(
			'type' => 'number',
			'default' => 0,
		
		),
		'borderRadius'      => array(
			'type' => 'number',
			'default' => 0,
		
		),
		'className' => array(
			'type' => 'string',
		),
		'layout'      => array(
			'type' => 'string',
			'default' => 'classic',
		
		),
		'filter'      => array(
			'type' => 'boolean',
			'default' => true,
		
		),
		'overlay'      => array(
			'type' => 'string',
			'default' => 'classic',
		
		),
		'category'      => array(
			'type' => 'boolean',
			'default' => true,
		
		),
	),
    )
	);
}

// Hook: Block assets.
add_action( 'init', 'portfolio_block_cgb_block_assets' );

//Load custom scripts and styles
function portfolio_custom_assets() {
	wp_enqueue_script('imagesLoaded');
	wp_enqueue_script('masonry');
	wp_enqueue_script('isotope',plugins_url( 'src/library/isotope.js', dirname( __FILE__ ) ), array());
	// //Load custom scripts
	// wp_enqueue_script(
	// 	'portfolio-custom-scripts',
	// 	plugins_url( 'src/library/portfolio.js',
	// 	dirname( __FILE__ ) ), 
	// 	array( 'jquery')
	// );

}
add_action( 'enqueue_block_assets', 'portfolio_custom_assets' );

//Render Callback
function portfolio_block( $attributes ) {
	global $post;
	$columns = $attributes['columns'];
	$classname = 'block-classname-custom';
	$filter = $attributes[ 'filter' ];
	$layout = $attributes[ 'layout' ];
	$overlay = $attributes [ 'overlay' ];
	$category = $attributes['category'];
	$gutters = $attributes['gutter'];
	$alignment = $attributes['alignment'];
	$borderRadius = $attributes['borderRadius'];
	$filterAlignment = $attributes['filterAlignment'];

	//set the columns
	if ($columns == '1') {
		$column = 'col-md-12';
	} elseif ( $columns == '2') {
		$column = 'col-md-6';
	}elseif ($columns == '3') {
		$column = 'col-md-4';
	}elseif ($columns == '4') {
		$column = 'col-md-3';
	}
		
	//Set Gutter
	if($gutters == '0') {
		$gutter = '0';
	} elseif ( !$gutters == '0') {
		$gutter = $gutters/2;
		//$sidegutter = $gutters/2;
	}
	if ( get_query_var( 'paged' ) ) :
		$pag = get_query_var( 'paged' );
	elseif ( get_query_var( 'page' ) ) :
		$pag = get_query_var( 'page' );
	else :
		$pag = -1;
	endif;


	/* Set arrays for the custom query*/
	$arr = array(
		'post_type' => 'portfolio',
		'paged' => $pag,
	);
	$portfolio = new WP_Query($arr);

		// Get the taxonomy terms for Portfolio custom post type.
		ob_start();
		
		//add align options
		if ( isset( $attributes[ 'align' ] ) ) {
			$align = $attributes['align'];
		} else {
			$align = 'wide'; // Hardcoded default value
		}
		echo '<div class="align'.$align.'">';
		// list terms in a given taxonomy
		if($filter == 'true'):
		$tax_terms = get_terms( 'project-type');
		
			if (is_array($tax_terms) || is_object($tax_terms)):
			echo "<div class='container-fluid no-gutters'>";
				echo "<ul class='row no-gutters' id='filters' style='justify-content:".$filterAlignment."'>";
					echo '<li id="filter--all" class="filter selected" data-filter="*"><a href="#">'.esc_html( 'All', 'understrap-portfolio' ) .'</a></li>';
						foreach ( $tax_terms as $tax_term ) {
							echo '<li class="filter" data-filter=".'. esc_attr( $tax_term->slug ) .'"><a href="#">' . esc_html ( $tax_term->name ).'</a></li>';
						}
				echo "</ul></div>";
			endif;
			endif; //end filters


			?>
	<div class="container-fluid no-gutters">
		<div class="row no-gutters"  <?php echo $alignment; ?>" id="<?php echo $layout; ?>" style="margin-left:-<?php echo $gutter ?>px;">
			<?php /* Start the Loop */
			if ( $portfolio->have_posts() ) :
			while ( $portfolio->have_posts() ) : $portfolio->the_post();		
			// Get the taxonomy terms for Portfolio custom post type.
			$terms = get_the_terms( $post->ID, 'project-type' );   
			if ( $terms && ! is_wp_error( $terms ) ) : 

				$links = array();

				foreach ( $terms as $term ) {
					$links[] = $term->slug;
				}

				$tax_links = join( " ", $links);          
				$taxo = strtolower($tax_links);
			else : 
			$taxo = '';                 
			endif; 
			$classes = [
				$column,
				$taxo,
				'item'
				
			]; 

			?>

			<article <?php post_class($classes); ?> id="post-<?php the_ID(); ?>" style="padding:<?php echo $gutter; ?>px !important; border-radius:<?php echo $borderRadius; ?>px">

				<?php if (has_post_thumbnail()) {
						$image = get_the_post_thumbnail_url( $post->ID, 'large' );
						
					} else {
						$image = get_stylesheet_directory() . '/Assets/img/default.png';
						
					}
		
						echo '<a href="'.esc_url( get_the_permalink() ).'"><img class="w-100" src="'.esc_url( $image ).'"></a>';
					
					
					
				?>

				<header class="<?php echo $overlay; ?>">
				
					<?php
					the_title(
						sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
						'</a></h2>'
					);
					//check if portfolio category should be shown
					if ( $category == 'true' ) :
						//display the taxonomy
						echo '<span>' .$taxo . '</span>';
					endif; ?>
				</header><!-- .entry-header -->

			</article><!-- #post-## -->

	<?php 
			endwhile;
		
		wp_reset_postdata();
		endif;
		echo '</div></div></div>';
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;

}