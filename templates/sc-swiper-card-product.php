<?php

/*
 *
 * Product slider template.
 *
 * This template can be overriden by copying this file to your-theme/bs-swiper-main/sc-swiper-card-product.php
 *
 * @author   bootScore
 * @package  bS Swiper
 * @version  5.6.0
 *
 * Product Slider Shortcode
 * [bs-swiper-card-product]
 *
 * Optional:
 * category="cars, boats"    Will pull products matching these categories    (Default: '')
 * id="1, 2, 3"              Will show products matching these ids           (Default: '')
 * posts="12"                Specify how many products will be shown         (Default: 12)
 * orderby="date"            Specify how products will be ordered by         (Default: date)
 * order="DESC"              Specify if products will be ordered ASC or DESC (Default: DESC)
 * featured="true"           Will pull featured products                     (Default: false)
 * outofstock="false"        Will hide out of stock products                 (Default: true)
 *
*/


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


// Product Slider Shortcode
add_shortcode('bs-swiper-card-product', 'bootscore_product_slider');
function bootscore_product_slider($atts) {

  ob_start();
  $atts = shortcode_atts(array(
    'type'       => 'product',
    'order'      => 'DESC',
    'orderby'    => 'date',
    'posts' => -1,
    'id'        => '',
    'category'   => '',
    'featured'   => '',
    'outofstock' => '',
  ), $atts);

  $options = array(
    'order'          => sanitize_text_field($atts['order']),
    'orderby'        => sanitize_text_field($atts['orderby']),
    'posts_per_page' => sanitize_text_field($atts['posts']),
    'product_cat'    => sanitize_text_field($atts['category']),
    'post_type'      => sanitize_text_field($atts['type']),
  );

  if ($atts['id']) {
    $options['post__in'] = array_map('trim', explode(',', sanitize_text_field($atts['id'])));
  }

  if ($atts['featured'] == 'true') {
    $options['tax_query'][] = array(
      'taxonomy' => 'product_visibility',
      'field'    => 'name',
      'terms'    => 'featured',
      'operator' => 'IN',
    );
  }

  if ($atts['outofstock'] == 'false') {
    $options['meta_query'] = array(
      array(
        'key' => '_stock_status',
        'value' => 'instock',
        'compare' => '=',
      )
    );
  }

  $query = new WP_Query($options);
  if ($query->have_posts()) { ?>


    <!-- Swiper -->
    <div class="px-5 position-relative product-slider woocommerce">

      <div class="cards swiper-container swiper position-static">

        <div class="swiper-wrapper">

          <?php while ($query->have_posts()) : $query->the_post(); ?>

            <div <?php wc_product_class('swiper-slide card h-auto mb-5 d-flex text-center product-card'); ?>>
              <?php
              /**
               * Hook: woocommerce_before_shop_loop_item.
               *
               * @hooked woocommerce_template_loop_product_link_open - 10
               */
              do_action('woocommerce_before_shop_loop_item');

              /**
               * Hook: woocommerce_before_shop_loop_item_title.
               *
               * @hooked woocommerce_show_product_loop_sale_flash - 10
               * @hooked woocommerce_template_loop_product_thumbnail - 10
               */
              do_action('woocommerce_before_shop_loop_item_title');

              ?>
              <div class="card-body d-flex flex-column">
                <?php
                /**
                 * Hook: woocommerce_shop_loop_item_title.
                 *
                 * @hooked woocommerce_template_loop_product_title - 10
                 */
                do_action('woocommerce_shop_loop_item_title');

                /**
                 * Hook: woocommerce_after_shop_loop_item_title.
                 *
                 * @hooked woocommerce_template_loop_rating - 5
                 * @hooked woocommerce_template_loop_price - 10
                 */
                do_action('woocommerce_after_shop_loop_item_title');

                /**
                 * Hook: woocommerce_after_shop_loop_item.
                 *
                 * @hooked woocommerce_template_loop_product_link_close - 5
                 * @hooked woocommerce_template_loop_add_to_cart - 10
                 */
                do_action('woocommerce_after_shop_loop_item');
                ?>
              </div>
            </div>

          <?php endwhile;
          wp_reset_postdata(); ?>

        </div> <!-- .swiper-wrapper -->

        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Arrows -->
        <div class="swiper-button-next end-0"></div>
        <div class="swiper-button-prev start-0"></div>

      </div><!-- swiper-container -->


    </div><!-- px-5 position-relative mb-5 -->
    <!-- Swiper End -->

<?php $myvariable = ob_get_clean();
    return $myvariable;
  }
}
