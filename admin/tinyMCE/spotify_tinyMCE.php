<?php

//shortcode classic
function easyspotify_func( $atts ) {
    extract( shortcode_atts( array(
        'play' => 'spotify:user:jonk:playlist:2ImDreMyt1Py2iXKtmEStW',
        'size' => get_option('easyspotify_size',0),
        'sizetype' => get_option('easyspotify_sizetype','big'),
        'link' => get_option('easyspotify_link','yes'),
    ), $atts ) );

    $size = round($size);
    $width = '100%';
    $min_height = '';
    if ($size != 0) {
        $width = $size;
    }
    if ($sizetype == "compact") {
        $height = 80;
        $min_height = "min-height:{$height}px;";
    } else {
        if ($size == 0) {
            $height = '500';
        } else {
            $height = $size+80;
        }
    }
    $open_spotify_link = '';
    if ( $link != 'no' ) {
        if ( strpos( $play, 'https://' ) !== false ) {
            $url = $play;
        } else {
            $url = str_replace( ":", "/", $play );
            $url = str_replace( "spotify/", "https://open.spotify.com/", $url );
        }
        $url = esc_url( $url );
        $open_spotify_link = "<p><a href=\"" . $url . "\" target=\"_blank\">" . __("Open in Spotify", "easy-spotify") . "</a></p>";
    }
    if ( strpos( $play, 'https://' ) !== false ) {
        $play = str_replace( "https://open.spotify.com/", "spotify:", $play );
        $play = str_replace( "/", ":", $play );
    } else {
        $play = esc_attr( $play );
    }
    $width = esc_attr( $width );
    $height = esc_attr( $height );
    $min_height = esc_attr( $min_height );

    return "<iframe src=\"https://open.spotify.com/embed/?uri={$play}\" width=\"{$width}\" height=\"{$height}\" frameBorder=\"0\" allowfullscreen=\"\" allow=\"autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture\" loading=\"lazy\" style=\"max-width:{$width}px;max-height:{$height}px;{$min_height}\"></iframe>" . $open_spotify_link;
}
add_shortcode( 'easyspotify', 'easyspotify_func' );

function easyspotify_tinymce_button($context){
    printf( "<a href=\"#TB_inline?&inlineId=easyspotify_tinymce_popup&width=600&height=550\" class=\"button thickbox\" id=\"easyspotify_tinymce_popup_button\" title=\"Spotify Shortcode\"><img src=\"" . plugin_dir_url( __FILE__ ) . "spotify.svg\" alt=\"insert spotify url\" style=\"width:auto;height:16px;vertical-align:text-top;display:inline-block;margin:0;\"></a>");
}
add_action('media_buttons', 'easyspotify_tinymce_button');

function easyspotify_tinymce_popup(){
    ?>
    <div id="easyspotify_tinymce_popup" style="display:none;">
        <div class="wrap" style="position:absolute;">
            <div>
                <div class="my_shortcode_add">
                    <p><strong><?php _e("Guideline: ", "easy-spotify"); ?></strong><?php _e("Copy the desired page address and enter it in the box below.", "easy-spotify"); ?></p>
                    <p>
                        <input value="https://open.spotify.com/track/1frBQy6RIBTiFMT3ARASEg" type="text" id="easyspotify_tinymce_popup_playlist" placeholder="<?php _e("Insert your spotify url", "easy-spotify"); ?> *" style="width:100%;">
                    </p>
                    <p>
                        <button class="button-primary" id="easyspotify_tinymce_popup_insert_button"><?php _e("Ok", "easy-spotify"); ?></button>
                    </p>
                    <p>
                        <?php _e("Refer to ", "easy-spotify"); ?><a href="<?php menu_page_url( 'easyspotify_settings', true ); ?>"><?php _e("Setting", "easy-spotify"); ?></a><?php _e(" for changes", "easy-spotify"); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php
}
add_action('admin_footer', 'easyspotify_tinymce_popup');

function my_shortcode_add_shortcode_to_editor(){?>
    <script>
        jQuery('#easyspotify_tinymce_popup_insert_button').on('click',function(){
            var user_content = jQuery('#easyspotify_tinymce_popup_playlist').val();
            var shortcode = '[easyspotify play="'+user_content+'"]';
            if( !tinyMCE.activeEditor || tinyMCE.activeEditor.isHidden()) {
                jQuery('textarea#content').val(shortcode);
            } else {
                tinyMCE.execCommand('mceInsertContent', false, shortcode);
            }
            self.parent.tb_remove();
        });
    </script>
    <?php
}
add_action('admin_footer','my_shortcode_add_shortcode_to_editor');

function easyspotifyBlockFiles() {
    wp_enqueue_script(
        'easy-spotify-for-wordpress',
        plugin_dir_url( __FILE__ ) . 'assets/easy-spotify-for-wordpress-admin.js',
        array( 'wp-blocks', 'wp-i18n' ),
        true
    );
    wp_enqueue_style(
        'easy-spotify-for-wordpress',
        plugin_dir_url( __FILE__ ) . 'assets/easy-spotify-for-wordpress-admin.css',
        null,
        false
    );
}
add_action( 'enqueue_block_editor_assets', 'easyspotifyBlockFiles' );

function easyspotify_block_do_shortcode( $attr ) {
    $spotifyUri = "";
    $size = "";
    $sizetype = "";
    $link = "";
    if ( isset( $attr['spotifyUri'] ) && $attr['spotifyUri'] != "" ) {
        $spotifyUri = 'play="' . $attr['spotifyUri'] . '" ';
    }
    if ( isset( $attr['size'] ) && $attr['size'] != "" ) {
        $size = 'size="' . $attr['size'] . '" ';
    }
    if ( isset( $attr['sizetype'] ) && $attr['sizetype'] != "" ) {
        $sizetype = 'sizetype="' . $attr['sizetype'] . '" ';
    }
    if ( isset( $attr['link'] ) && $attr['link'] != "" ) {
        $link = 'link="' . $attr['link'] . '" ';
    }
    return do_shortcode( '[easyspotify ' . $spotifyUri . $size . $sizetype . $link . ']' );
}

register_block_type( 'easy-spotify-for-wordpress/play-button', array(
    'render_callback' => 'easyspotify_block_do_shortcode',
    'attributes' => [
        'spotifyUri' => [
            'type' => 'string'
        ],
        'size' => [
            'type' => 'string',
        ],
        'sizetype' => [
            'type' => 'string',
        ],
        'link' => [
            'type' => 'string',
        ],
    ]
) );
