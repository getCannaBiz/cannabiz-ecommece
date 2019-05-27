<?php
/** 
 * Copy this file into your theme to customize
 * 
 * @version 1.0
 * @since 1.0
 */
get_header();

do_action( 'wpd_ecommerce_templates_archive_items_before' );

/**
 * Set image size
 * 
 * @todo Move this along with the code below to run on the do_action
 * from within the functions file for templates
 * 
 * @since 1.0
 */

if ( ! empty( $_GET['vendor'] ) ) {
    $vendor_name         = get_term_by( 'slug', $_GET['vendor'], 'vendor' );
    $title               = $vendor_name->name;
}

//print_r( $vendor_name );

//    echo $vendor_name;

/**
 * Taxonomy check.
 * 
 * @since 1.0
 */
if ( is_tax() ) {

    // Get current info.
    $tax = $wp_query->get_queried_object();

    //print_r( $tax );

    $taxonomy             = $tax->taxonomy;
    $taxonomy_slug        = $tax->slug;
    $taxonomy_name        = $tax->name;
    $taxonomy_count       = $tax->count;
    $taxonomy_description = $tax->description;

    $tax_query = array(
        'relation' => 'AND',
    );
    $tax_query[] = array(
        'taxonomy' => $taxonomy,
        'field'    => 'slug',
        'terms'    => $taxonomy_slug,
    );

    $menu_type_name        = $taxonomy_name;
    $menu_type_description = $taxonomy_description;

} else {
    $tax_query = '';
}

/**
 * Set vendor page title.
 * 
 * @since 1.0
 */
if ( ! empty( $_GET['vendor'] ) ) {
    $vendor_name    = get_term_by( 'slug', $_GET['vendor'], 'vendor' );
    $page_title     = __( $vendor_name->name, 'wpd-ecommerce' );
    $menu_type_name = $page_title;
} elseif ( is_tax() ) {
    // Do nothing.
} else {
    // Get post type.
    $post_type = get_post_type();

    // Create post type variables.
    if ( $post_type ) {
        $post_type_data = get_post_type_object( $post_type );
        $post_type_name = $post_type_data->label;
        $post_type_slug = $post_type_data->rewrite['slug'];
        //echo $post_type_slug;
        //echo $post_type_name;
        $menu_type_name = $post_type_name;
    }

    /*
    echo "<pre>";
    print_r( $post_type_data );
    echo "</pre>";
    */

    if ( is_post_type_archive( $post_type_slug ) ) {
        //echo "<h2>Menu: " . $post_type_slug . "</h2>";
    }

}

//print_r( $vendor_name );

do_action( 'wpd_ecommerce_templates_archive_items_wrap_before' );
?>

    <?php if ( have_posts() ) : ?>
    <div class="wpdispensary">
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <h2 class="wpd-title"><?php _e( $menu_type_name, 'wpd-ecommerce' ); ?></h2>
            <div class="item-wrapper">
                <div class="wpd-row">
                <?php
                    $count      = 0;
                    $perrow     = ( empty( $options['perrow'] ) ) ? 3 : $options['perrow'];
                    $item_width = ( empty( $options['perrow'] ) ) ? 32 : ( 100 / $options['perrow'] ) - 1;
                    
                    // Get the posts.
                    $loop = new WP_Query(
                        array(
                            'post_status' => 'publish',
                            'post_type'   => $post_type,
                            'tax_query'   => $tax_query
                          //'posts_per_page' => $options['perpage'] /** @todo create admin setting for this */
                        )
                    );
                    /*
                    echo "<pre>";
                    print_r( $loop );
                    echo "</pre>";
                    */

                    // Loop through each post.
                    while ( $loop->have_posts() ) : $loop->the_post();

                    echo ( $count % $perrow === 0 && $count > 0 ) ? '</div><div class="wpd-row">' : '';
                ?>
                    <?php do_action( 'wpd_ecommerce_archive_items_product_before' ); ?>
                    <div class="wpdshortcode item" style="width:<?php echo ceil( $item_width ); ?>%;">
                        <?php do_action( 'wpd_ecommerce_archive_items_product_inside_before' ); ?>
                        <?php echo wpd_product_image( get_the_ID(), 'wpd-small' ); ?>
                        <p class="wpd-producttitle"><strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong></p>
                        <?php wpd_all_prices_simple( get_the_ID(), TRUE, TRUE ); ?>
                        <?php do_action( 'wpd_ecommerce_archive_items_product_inside_after' ); ?>
                    </div><!-- // .wpdshortcode item -->
                    <?php do_action( 'wpd_ecommerce_archive_items_product_after' ); ?>
                    <?php
                        $count++;
                        endwhile;
                    ?>
                    <div class="wpd-page-numbers">
                    <?php
                        // Get total number of pages
                        global $wp_query;
                        $total = $wp_query->max_num_pages;
                        // Only paginate if we have more than one page
                        if ( $total > 1 )  {
                            // Get the current page
                            if ( ! $current_page = get_query_var( 'paged' ) )
                                $current_page = 1;
                                $format = empty( get_option( 'permalink_structure' ) ) ? '&page=%#%' : 'page/%#%/';
                                echo paginate_links( array(
                                    'base'      => get_pagenum_link(1) . '%_%',
                                    'format'    => $format,
                                    'current'   => $current_page,
                                    'total'     => $total,
                                    'mid_size'  => 4,
                                    'prev_text' => '&larr;',
                                    'next_text' => '&rarr;',
                                    'type'      => 'list'
                                ) );
                        }
                        //the_posts_navigation();
                    ?>
                    </div><!-- // .wpd-page-links -->
                </div><!-- // .row -->
            </div><!-- // .item-wrapper -->
        </div><!-- // #post -->
    </div><!-- // .wpdispensary -->
    <?php endif; ?>

    <?php do_action( 'wpd_ecommerce_templates_archive_items_wrap_after' ); ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
