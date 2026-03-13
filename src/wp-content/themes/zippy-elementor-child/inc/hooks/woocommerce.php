<?php

add_filter('woocommerce_email_enabled', '__return_false');

function generate_test_orders($count = 1000) {

    for ($i = 0; $i < $count; $i++) {

        $order = wc_create_order();

        $product_id = 52;
        $product = wc_get_product($product_id);

        $order->add_product($product, rand(1,3));

        $order->set_address([
            'first_name' => 'Test',
            'last_name'  => 'User'.$i,
            'email'      => "test$i@email.com",
        ], 'billing');

        $order->calculate_totals();
        $order->update_status('completed');

    }

}

// generate_test_orders(1000);