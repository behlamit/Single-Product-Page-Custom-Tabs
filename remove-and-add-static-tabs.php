<!-- add these code in you theme function.php file -->

<?php
add_filter( 'woocommerce_product_tabs', 'woo_custom_product_tabs' );
function woo_custom_product_tabs( $tabs ) {

    // 1) Removing tabs

    unset( $tabs['description'] );              // Remove the description tab
    // unset( $tabs['reviews'] );               // Remove the reviews tab
    unset( $tabs['additional_information'] );   // Remove the additional information tab


    // 2 Adding new tabs and set the right order

    //Attribute Description tab
    $tabs['attrib_desc_tab'] = array(
        'title'     => __( 'Desc', 'woocommerce' ),
        'priority'  => 100,
        'callback'  => 'woo_attrib_desc_tab_content'
    );

    // Adds the qty pricing  tab
    $tabs['qty_pricing_tab'] = array(
        'title'     => __( 'Quantity Pricing', 'woocommerce' ),
        'priority'  => 110,
        'callback'  => 'woo_qty_pricing_tab_content'
    );

    // Adds the other products tab
    $tabs['other_products_tab'] = array(
        'title'     => __( 'Other Products', 'woocommerce' ),
        'priority'  => 120,
        'callback'  => 'woo_other_products_tab_content'
    );

    return $tabs;

}

// New Tab contents

function woo_attrib_desc_tab_content() {
    // The attribute description tab content
    global $product;
    $productID = $product->get_id();
    $desc_tab_value = get_post_meta($productID, '_shipping_tab', true);
    echo '<h2>Description</h2>';
    echo '<p>Custom description tab11.</p>';
    echo $desc_tab_value;
}
function woo_qty_pricing_tab_content() {
    // The qty pricing tab content
    echo '<h2>Quantity Pricing</h2>';
    echo '<p>Here\'s your quantity pricing tab.</p>';
}
function woo_other_products_tab_content() {
    // The other products tab content
    echo '<h2>Other Products</h2>';
    echo '<p>Here\'s your other products tab.</p>';
}
