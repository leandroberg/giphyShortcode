<?php /* Plugin Name: Giphy Shortcode */

add_shortcode('giphy', function(){

    ob_start();
    
    $dir = plugin_dir_path(__FILE__);

    require_once( $dir . '/template/giphyForm.php');

    $html = ob_get_clean();

    return $html;

});

add_action('giphy_form_after', function(){

    $key = "your-giphy-api-key";
    $html = "<div class='giphy-results'>";

    if ( $_GET['giphyKeyword'] ) :

        $keyword = sanitize_text_field( $_GET['giphyKeyword'] );
        $query = "https://api.giphy.com/v1/gifs/search?api_key=" . $key . "&q=" . $keyword . "&limit=10";

    else:

        $query = "https://api.giphy.com/v1/gifs/trending?api_key=" . $key . "&limit=10";

    endif;

    $results = json_decode ( wp_remote_retrieve_body ( wp_remote_get( $query ) ) );

    foreach ( $results->data as $result ) :
        $html .= '<img src="' . $result->images->original->url . '" />';
    endforeach;

    $html .= "</div>";

    echo $html;

});