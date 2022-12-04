<?php

namespace Palasthotel\WordPress\FlexWall;


use Palasthotel\WordPress\FlexWall\Components\Component;

/**
 * Class MetaBox
 *
 * @package FlexWall
 */
class MetaBox extends Component{

	public function onCreate() {
		add_action( 'add_meta_boxes_post', array( $this, 'add_meta_box' ) );
		add_action( 'post_submitbox_misc_actions', array( $this, 'render_disabler') );
		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
	}

	/**
	 *  register meta box
	 */
	public function add_meta_box( $post ) {
        if ( $this->plugin->post->hasBlocks($post) ) return;

		add_meta_box(
			Plugin::DOMAIN . '-meta-box',
			__( 'FlexWall', Plugin::DOMAIN ),
			array( $this, 'render' ),
			'post'
		);
	}

	public function render_disabler( $post ) {
        if ( $this->plugin->post->hasBlocks($post) ) return;

		$checked = "";
		if ( $this->plugin->post->isProtectionDeactivated( $post->ID ) ) {
			$checked = "checked='checked'";
		}
		echo '<div class="misc-pub-section">';
		echo "<label>";
		echo "<input type='checkbox' name='flexwall_deactivated_disabler' $checked value='turn-it-off' /> " . __( 'Disable FlexWall', Plugin::DOMAIN );
		echo "</label>";
		echo '</div>';

	}

	public function render( $post ) {
        if ( $this->plugin->post->hasBlocks($post) ) return;

		$checked = "";
		if ( $this->plugin->post->isProtectionDeactivated( $post->ID ) ) {
			$checked = "checked='checked'";
		}
		echo "<label>";
		echo "<input type='checkbox' name='flexwall_deactivated_metabox' $checked value='turn-it-off' /> " . __( 'Deactivate', Plugin::DOMAIN );
		echo "</label>";

		do_action( Plugin::ACTION_ADD_POST_META_SETTINGS, $post );
	}

	public function save( $post_id, $post ) {
        if ( $this->plugin->post->hasBlocks($post) ) return;

        $valueDisabler = isset( $_POST["flexwall_deactivated_disabler"] ) && $_POST["flexwall_deactivated_disabler"] == "turn-it-off";
        $valueMetabox = isset( $_POST["flexwall_deactivated_metabox"] ) && $_POST["flexwall_deactivated_metabox"] == "turn-it-off";

        $this->plugin->post->setProtectionDeactivated(
            $post->ID,
            ( $valueDisabler != $valueMetabox ) ? !$this->plugin->post->isProtectionDeactivated( $post->ID ) : ( $valueDisabler && $valueMetabox )
        );
	}
}
