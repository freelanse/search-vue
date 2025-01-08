<?php 
    function enqueue_vue_scripts() {
    // Подключаем Vue.js
    wp_enqueue_script('vue-js', 'https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js', [], null, true);
    
    // Подключаем ваш скрипт для логики поиска
    wp_enqueue_script('custom-vue-search', get_template_directory_uri() . '/js/custom-search.js', ['vue-js'], null, true);
    
    // Локализуем данные для API-запросов
    wp_localize_script('custom-vue-search', 'wpSearchData', [
        'ajax_url' => admin_url('admin-ajax.php'), // URL для запросов
        'nonce'    => wp_create_nonce('vue_search_nonce'), // Безопасность
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_vue_scripts');


function vue_search_handler() {
    check_ajax_referer('vue_search_nonce', 'nonce');
    
    $query = sanitize_text_field($_GET['query']);
    if (empty($query)) {
        wp_send_json_error('Запрос пуст');
    }

    $args = [
        'post_type'      => 'post', // Укажите ваш post_type
        'posts_per_page' => 10,
        's'              => $query,
    ];
    $search_query = new WP_Query($args);

    if ($search_query->have_posts()) {
        $results = [];
        while ($search_query->have_posts()) {
            $search_query->the_post();
            $results[] = [
                'title' => get_the_title(),
                'link'  => get_permalink(),
            ];
        }
        wp_send_json_success($results);
    } else {
        wp_send_json_error('Ничего не найдено');
    }

    wp_die();
}
add_action('wp_ajax_vue_search', 'vue_search_handler');
add_action('wp_ajax_nopriv_vue_search', 'vue_search_handler');

