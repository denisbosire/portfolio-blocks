<?php
global $post;
$columns = $attributes['columns'];
$classname = 'block-classname-custom';
$filter = $attributes[ 'filter' ];
$layout = $attributes[ 'layout' ];
$overlay = $attributes [ 'overlay' ];
$category = $attributes['category'];
$gutters = $attributes['gutter'];
$alignment = $attributes['alignment'];
//$className = $attributes['className'];

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
    // list terms in a given taxonomy
    //echo '<div class="'.$attributes['className'].'>';
    if($filter == 'true'):
    $tax_terms = get_terms( 'project_type');
        
        if (is_array($tax_terms) || is_object($tax_terms)):
        echo "<ul class='row' id='filters'>";
        echo '<li id="filter--all" class="filter selected" data-filter="*"><a href="#">'.esc_html( 'All', 'understrap-portfolio' ) .'</a></li>';
            foreach ( $tax_terms as $tax_term ) {
                echo '<li class="filter" data-filter=".'. esc_attr( $tax_term->slug ) .'"><a href="#">' . esc_html ( $tax_term->name ).'</a></li>';
            }
        echo "</ul>";
        endif;
        endif; //end filters


        ?>

    <div class="row no-gutters grid-container <?php echo $alignment; ?>" id="<?php echo $layout; ?>">
        <?php /* Start the Loop */
        if ( $portfolio->have_posts() ) :
        while ( $portfolio->have_posts() ) : $portfolio->the_post();		
        // Get the taxonomy terms for Portfolio custom post type.
        $terms = get_the_terms( $post->ID, 'project_type' );   
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

        <article <?php post_class($classes); ?> id="post-<?php the_ID(); ?>" style="padding:<?php echo $gutter; ?>px !important;">

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
                    //portfolio_type();
                endif; ?>
            </header><!-- .entry-header -->

        </article><!-- #post-## -->

<?php 
        endwhile;
    
    wp_reset_postdata();
    endif;
    echo '</div>';
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;