<?php
/**
 * Spa Services Grid Shortcode  –  Archive-aware version
 *
 * INSTALLATION
 * ─────────────
 * Option A  –  Save this file in your child theme and add to functions.php:
 *     require_once get_stylesheet_directory() . '/spa-services-shortcode.php';
 *
 * Option B  –  Paste the entire contents directly into functions.php.
 *
 * SHORTCODE PARAMETERS  (all optional)
 * ──────────────────────────────────────
 *   columns   (int)     Grid columns, 1-4.  Default: 3
 *   limit     (int)     Posts per page (fallback only).  Default: 6
 *   category  (string)  Product-cat slug (fallback only).  Default: ''
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ──────────────────────────────────────────────────────────────────────────────
// 1. REGISTER & ENQUEUE CSS  (always runs on wp_enqueue_scripts — never too late)
// ──────────────────────────────────────────────────────────────────────────────

add_action( 'wp_enqueue_scripts', 'spa_services_register_styles' );

function spa_services_register_styles() {

    $css = "
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=DM+Sans:wght@400;500&display=swap');

.spa-services-grid {
    display: grid;
    gap: 2.5rem;
    padding: 40px 0;
    font-family: 'DM Sans', sans-serif;
    box-sizing: border-box;
}

@media (max-width: 900px) {
    .spa-services-grid { grid-template-columns: repeat(2, 1fr) !important; }
}
@media (max-width: 560px) {
    .spa-services-grid { grid-template-columns: 1fr !important; }
}

.spa-card {
    background: #1a1616;
    border-radius: 20px;
    padding: 36px 32px 28px;
    color: #f5ede8;
    display: flex;
    flex-direction: column;
    box-sizing: border-box;
    transition: transform 0.25s ease-in-out , box-shadow 0.25s ease-in-out;
}

.spa-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 18px rgba(0,0,0,.22);
    background: linear-gradient(145deg, #e8683c 0%, #c84040 100%);
}

.spa-card--featured {
    background: linear-gradient(145deg, #e8683c 0%, #c84040 100%);
}

.spa-card__header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 14px;
}

.spa-card__title {
    font-size: clamp(1.3rem, 2vw, 1.6rem);
    font-weight: 600;
    line-height: 1.2;
    color: #ffffff;
    margin: 0;
    padding: 0;
}

.spa-card__icon {
    flex-shrink: 0;
    width: 58px;
    height: 58px;
    color: rgba(255,255,255,.85);
}

.spa-card__icon-img {
    width: 58px;
    height: 58px;
    object-fit: contain;
    border-radius: 50%;
}

.spa-card__icon-svg {
    width: 58px;
    height: 58px;
}

.spa-card__description {
    font-size: 0.92rem;
    line-height: 1.6;
    color: rgba(255,255,255,.72);
    margin: 0 0 22px;
    padding: 0;
}

.spa-card__prices {
    flex: 1;
    margin-bottom: 26px;
}

.spa-card__price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255,255,255,.13);
    font-size: 0.9rem;
    gap: 12px;
}

.spa-card__price-row:first-child {
    border-top: 1px solid rgba(255,255,255,.13);
}

.spa-card__price-label {
    color: rgba(255,255,255,.82);
}

.spa-card:not(.spa-card--featured) .spa-card__price-amount {
    color: #e8683c;
    font-size: 1.25rem;
    font-weight: 500;
    white-space: nowrap;
}

/* When dark card becomes orange on hover, flip price to white */
.spa-card:not(.spa-card--featured):hover .spa-card__price-amount {
    color: #ffffff;
}

.spa-card--featured .spa-card__price-amount {
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: 500;
    white-space: nowrap;
}

.spa-card__price-amount .woocommerce-Price-amount,
.spa-card__price-amount ins .woocommerce-Price-amount {
    color: inherit !important;
    font-weight: inherit !important;
    background: none !important;
    text-decoration: none !important;
}

.spa-card__price-amount del { display: none !important; }

.spa-card__footer {
    margin-top: auto;
    padding-top: 4px;
}

.spa-card__btn {
    display: inline-block;
    padding: 11px 30px;
    border: 2px solid rgba(255,255,255,.6);
    border-radius: 50px;
    color: #ffffff;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.875rem;
    font-weight: 500;
    letter-spacing: 0.04em;
    text-decoration: none;
    transition: background 0.2s ease, border-color 0.2s ease;
    cursor: pointer;
}

.spa-card__btn:hover {
    background: rgba(255,255,255,.18);
    border-color: #ffffff;
    color: #ffffff;
    text-decoration: none;
}

.spa-card--featured .spa-card__btn:hover {
    background: rgba(255,255,255,.25);
}

/* Loading state injected by WooCommerce AJAX */
.spa-card__btn.loading {
    opacity: 0.65;
    pointer-events: none;
}

.spa-card__btn.added {
    border-color: rgba(255,255,255,.9);
    background: rgba(255,255,255,.15);
}

.spa-no-products {
    text-align: center;
    color: #888;
    padding: 48px 0;
    font-family: 'DM Sans', sans-serif;
}
.spa-card__footer .added_to_cart.wc-forward {
    color: white;
    margin-left: 20px;
}
.spa-card__title {
    font-family: var(--e-global-typography-primary-font-family), Sans-serif;
    font-size: clamp(1.3rem, 2vw, 1.6rem);
    font-weight: 600;
    color: #ffffff;
    margin: 0;
    margin-bottom: 1rem;
    padding: 0;
}
";

    // Register a dummy handle with no src, then attach our CSS as inline style.
    // This guarantees styles are output in <head> regardless of when the
    // shortcode itself runs.
    wp_register_style( 'spa-services-grid', false, [], null );
    wp_enqueue_style( 'spa-services-grid' );
    wp_add_inline_style( 'spa-services-grid', $css );
}

// ──────────────────────────────────────────────────────────────────────────────
// 2. SHORTCODE
// ──────────────────────────────────────────────────────────────────────────────

add_shortcode( 'spa_services_grid', 'spa_services_grid_shortcode' );

function spa_services_grid_shortcode( $atts ) {

    $atts = shortcode_atts(
        [
            'columns'  => 3,
            'limit'    => 6,
            'category' => '',
        ],
        $atts,
        'spa_services_grid'
    );

    // ── Decide which query to use ─────────────────────────────────────────────
    $use_global_query = (
        function_exists( 'is_shop' ) && (
            is_shop()              ||
            is_product_taxonomy()  ||
            is_product_category()  ||
            is_product_tag()
        )
    );

    if ( $use_global_query ) {
        global $wp_query;
        $query      = $wp_query;
        $owns_query = false;
    } else {
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => intval( $atts['limit'] ),
            'post_status'    => 'publish',
        ];

        if ( ! empty( $atts['category'] ) ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( $atts['category'] ),
                ],
            ];
        }

        $query      = new WP_Query( $args );
        $owns_query = true;
    }

    if ( ! $query->have_posts() ) {
        return '<p class="spa-no-products">No services found.</p>';
    }

    // ── Build grid ────────────────────────────────────────────────────────────
    $columns    = max( 1, min( 4, intval( $atts['columns'] ) ) );
    $grid_style = 'grid-template-columns: repeat(' . $columns . ', 1fr);';

    ob_start();
    ?>
    <div class="spa-services-grid" style="<?php echo esc_attr( $grid_style ); ?>">
    <?php
    $index = 0;

    while ( $query->have_posts() ) :
        $query->the_post();

        global $product;

        if ( ! ( $product instanceof WC_Product ) ) {
            $product = wc_get_product( get_the_ID() );
        }

        if ( ! $product ) {
            $index++;
            continue;
        }

        $product_id  = $product->get_id();
        $title       = $product->get_name();
        $description = $product->get_short_description()
                        ?: wp_trim_words( $product->get_description(), 20 );
        $permalink   = get_permalink( $product_id );
        $is_featured = $product->is_featured();

        // ── Price rows ────────────────────────────────────────────────────
        $price_rows = [];

        if ( $product->is_type( 'variable' ) ) {
            foreach ( $product->get_available_variations() as $v ) {
                $var_obj = wc_get_product( $v['variation_id'] );
                if ( ! $var_obj ) continue;

                $attributes = $var_obj->get_variation_attributes();
                $label      = implode( ' / ', array_map( 'esc_html', $attributes ) );
                if ( empty( $label ) ) $label = $var_obj->get_name();

                $price_rows[] = [
                    'label' => $label,
                    'price' => wc_price( $var_obj->get_price() ),
                ];
            }
        } elseif ( $product->is_type( 'grouped' ) ) {
            foreach ( $product->get_children() as $child_id ) {
                $child = wc_get_product( $child_id );
                if ( $child ) {
                    $price_rows[] = [
                        'label' => $child->get_name(),
                        'price' => wc_price( $child->get_price() ),
                    ];
                }
            }
        } else {
            $price_rows[] = [
                'label' => '',
                'price' => $product->get_price_html(),
            ];
        }

        // ── Icon ──────────────────────────────────────────────────────────
        // if ( has_post_thumbnail( $product_id ) ) {
        //     $icon_html = get_the_post_thumbnail(
        //         $product_id,
        //         [ 64, 64 ],
        //         [ 'class' => 'spa-card__icon-img', 'alt' => '' ]
        //     );
        // } else {
        //     $icon_html = '<svg class="spa-card__icon-svg" xmlns="http://www.w3.org/2000/svg"
        //         viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.5"
        //         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        //         <path d="M32 12 C24 12 16 20 16 30 C22 28 28 27 32 27 C36 27 42 28 48 30 C48 20 40 12 32 12Z"/>
        //         <path d="M32 27 C32 34 30 42 26 48"/>
        //         <path d="M32 27 C32 34 34 42 38 48"/>
        //         <circle cx="32" cy="27" r="2" fill="currentColor"/>
        //     </svg>';
        // }

        // ── Card ──────────────────────────────────────────────────────────
        $card_class = 'spa-card' . ( $is_featured ? ' spa-card--featured' : '' );
        ?>
        <div class="<?php echo esc_attr( $card_class ); ?>">

            <div class="spa-card__header">
                <h3 class="spa-card__title"><?php echo esc_html( $title ); ?></h3>
            </div>

            <?php if ( $description ) : ?>
                <p class="spa-card__description"><?php echo wp_kses_post( $description ); ?></p>
            <?php endif; ?>

            <?php if ( ! empty( $price_rows ) ) : ?>
                <div class="spa-card__prices">
                    <?php foreach ( $price_rows as $row ) : ?>
                        <div class="spa-card__price-row">
                            <?php if ( ! empty( $row['label'] ) ) : ?>
                                <span class="spa-card__price-label"><?php echo esc_html( $row['label'] ); ?></span>
                            <?php endif; ?>
                            <span class="spa-card__price-amount"><?php echo $row['price']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="spa-card__footer">
                <?php
                if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
                    // Simple product: direct AJAX add-to-cart button
                    printf(
                        '<a href="%s" data-product_id="%d" data-quantity="1"
                            class="spa-card__btn add_to_cart_button ajax_add_to_cart"
                            rel="nofollow">%s</a>',
                        esc_url( $product->add_to_cart_url() ),
                        absint( $product_id ),
                        esc_html__( 'Add to Cart', 'woocommerce' )
                    );
                } else {
                    // Variable / grouped / out-of-stock: send to product page
                    printf(
                        '<a href="%s" class="spa-card__btn">%s</a>',
                        esc_url( $permalink ),
                        esc_html( $product->is_type( 'variable' ) ? __( 'Select Options', 'woocommerce' ) : __( 'View Details', 'woocommerce' ) )
                    );
                }
                ?>
            </div>

        </div>
        <?php
        $index++;
    endwhile;

    if ( $owns_query ) {
        wp_reset_postdata();
    } else {
        $query->rewind_posts();
    }
    ?>
    </div>
    <?php
    return ob_get_clean();
}

// ──────────────────────────────────────────────────────────────────────────────
// 3. AUTO-REPLACE DEFAULT WC LOOP ON ARCHIVES
// ──────────────────────────────────────────────────────────────────────────────

add_action( 'woocommerce_before_shop_loop', 'spa_services_replace_archive_loop', 5 );

function spa_services_replace_archive_loop() {
    if ( ! ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) ) {
        return;
    }

    // Remove default WooCommerce loop actions
    remove_action( 'woocommerce_before_shop_loop_item',       'woocommerce_template_loop_product_link_open',  10 );
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash',     10 );
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail',  10 );
    remove_action( 'woocommerce_shop_loop_item_title',        'woocommerce_template_loop_product_title',      10 );
    remove_action( 'woocommerce_after_shop_loop_item_title',  'woocommerce_template_loop_rating',              5 );
    remove_action( 'woocommerce_after_shop_loop_item_title',  'woocommerce_template_loop_price',              10 );
    remove_action( 'woocommerce_after_shop_loop_item',        'woocommerce_template_loop_product_link_close',  5 );
    remove_action( 'woocommerce_after_shop_loop_item',        'woocommerce_template_loop_add_to_cart',        10 );

    // Output spa grid
    echo do_shortcode( '[spa_services_grid columns="3"]' );

    // Suppress WC's empty <ul class="products"> wrapper
    add_filter( 'woocommerce_product_loop_start', '__return_empty_string', 999 );
    add_filter( 'woocommerce_product_loop_end',   '__return_empty_string', 999 );
}