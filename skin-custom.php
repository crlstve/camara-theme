<?php
//namespace ElementorPro\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly
}

class Skin_Custom extends \ElementorPro\Modules\Posts\Skins\Skin_Classic {

	protected function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/posts/classic_section_design_layout/after_section_end', [ $this, 'register_additional_design_controls' ] );
	}

	public function get_id() {
		return 'custom-cv';
	}

	public function get_title() {
		return esc_html__( 'Custom CV', 'elementor-pro' );
	}

	public function get_container_class() {
		return 'elementor-has-item-ratio elementor-posts--skin-' . $this->get_id();
	}

	
	protected function render_post() {
		
		$this->render_post_header();
		$this->render_thumbnail();
		$this->render_text_header();
		$this->render_title();
		$this->render_meta_data();
		$this->render_excerpt();
		echo "MI RESUMEN->".get_the_ID();
		$this->render_read_more();
		$this->render_text_footer();
		$this->render_post_footer();
	}
	
	
}
