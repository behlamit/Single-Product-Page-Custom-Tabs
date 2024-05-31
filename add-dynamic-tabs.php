<!-- add these code in your theme function.php file -->
<!-- is code se aapke admin me product page pr a new wp editor ki field ban kr aajayegi, jisme hm kuch b data type krke database me save kr skte he or apne frontend me single product page ki tab me display kra skte he  -->

<?php
// Adding a custom Meta container to admin products pages
add_action( 'add_meta_boxes', 'create_custom_meta_box' );
if ( ! function_exists( 'create_custom_meta_box' ) )
{
    function create_custom_meta_box()
    {
        add_meta_box(
            'custom_product_shipping_field',
            __( 'Custom Shipping Tab information', 'woocommerce' ),
            'add_custom_content_meta_box',
            'product',    //post type
            'normal',
            'high'
        );
    }
}

//  Custom metabox content in admin product pages
if ( ! function_exists( 'add_custom_content_meta_box' ) )
{
    function add_custom_content_meta_box( $post )
    {
        $value = get_post_meta( $post->ID, '_shipping_tab', true ) ? get_post_meta( $post->ID, '_shipping_tab', true ) : '';
        wp_editor( $value, 'custom_shipping_tab', array( 'editor_height' => 100 ) );
        echo '<input type="hidden" name="custom_product_field_nonce" value="' . wp_create_nonce() . '">';

    }
}


//Save the data of the Meta field
add_action( 'save_post', 'save_custom_content_meta_box', 10, 1 );
if ( ! function_exists( 'save_custom_content_meta_box' ) )
{

    function save_custom_content_meta_box( $post_id ) {

        // We need to verify this with the proper authorization (security stuff).

        // Check if our nonce is set.
        if ( ! isset( $_POST[ 'custom_product_field_nonce' ] ) ) {
            return $post_id;
        }
        $nonce = $_REQUEST[ 'custom_product_field_nonce' ];

        //Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce ) ) {
            return $post_id;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check the user's permissions.
        if ( 'page' == $_POST[ 'post_type' ] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        // --- Its safe for us to save the data ! --- //

        // Sanitize user input  and update the meta field in the database.
        update_post_meta( $post_id, '_shipping_tab', wp_kses_post($_POST[ 'custom_shipping_tab' ]) );
    }
}



add_filter( 'woocommerce_product_tabs', 'woo_custom_product_tabs' );
function woo_custom_product_tabs( $tabs ) {
     // Adds the shipping tab
     $tabs['shipping_tab'] = array(
        'title'     => __( 'Shipping Tab', 'woocommerce' ),
        'priority'  => 120,
        'callback'  => 'woo_other_products_tab_content'
    );

    return $tabs;

}

function woo_other_products_tab_content(){
    global $product;
    $product_id = $product->get_ID();
    $shipping_tab_value = get_post_meta($product_id, '_shipping_tab', true);
    echo "<h2>Shipping Tab</h2>";
    echo $shipping_tab_value;
}

?>