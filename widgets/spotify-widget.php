<?php

// Initialize Namespace
namespace Elementor;
class easy_spotify_widg extends Widget_Base {

    public function get_name() {
        return  'easy-spotify-widget';
    }

    public function get_title() {
        return esc_html__( 'Easy Viewing for Spotify', 'easy-viewing-spotify');
    }
    public function get_icon() {
        return 'eicon-spotify';
    }
    public function get_custom_help_url() {
		return 'https://wordpress.org/support/plugin/easy-viewing-spotify/';
	}
    public function get_categories() {
        return [ 'basic' ];
    }
    public function get_keywords() {
        return [ 'Spotify', 'Music','easy' ];
    }
    public function _register_controls() {
        // Section Starts
        $this->start_controls_section(
			'easy_section_title',
			[
				'label' => __( 'General', 'easy-viewing-spotify' ),
			]
		);
        // TEXT
        $this->add_control(
			'spotify_link',
			[
				'label' => esc_html__( 'Spotify Embed Link', 'easy-viewing-spotify'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'https://open.spotify.com/embed/track/1frBQy6RIBTiFMT3ARASEg', 'easy-viewing-spotify'),
				'placeholder' => esc_html__( 'Link Here', 'easy-viewing-spotify'),
			]
		);      
	$this->add_control(
      'easy_height',
      [
        'label' => esc_html__( 'Height size:', 'easy-viewing-spotify'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'min' => 80,
        'max' => 1400,
        'step' => 10,
        'default' => 352,
        'description' => esc_html__( 'If the height size value is 0, a small bar will be displayed.', 'easy-viewing-spotify'),
      ]
    );
        $this->end_controls_section();
    }
    // Render the widget
    protected function render() {
        $settings = $this->get_settings_for_display();
	$easy_height = $settings['easy_height'];
        ?>
            <iframe src="<?php echo esc_url($settings['spotify_link']); ?>" width="100%" height="<?php echo esc_html__($easy_height)?>" frameBorder="0" allowtransparency="true" allow="autoplay"></iframe>
        <?php
    }

}
Plugin::instance()->widgets_manager->register_widget_type( new easy_spotify_widg() );
