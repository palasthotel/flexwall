<?php
/**
 * Created by PhpStorm.
 * User: edward
 * Date: 18.07.18
 * Time: 15:36
 */

namespace Palasthotel\WordPress\FlexWall;


use Palasthotel\WordPress\FlexWall\Components\Component;

class Post extends Component {

	/**
	 * check if protection is deactivated
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function isProtectionDeactivated($post_id){
		return intval(get_post_meta($post_id, Plugin::POST_META_DEACTIVATED, true)) == 1;
	}

	/**
	 * set deactivation of protection
	 * @param $post_id
	 * @param $deactivated
	 */
	public function setProtectionDeactivated($post_id, $isDeactivated ){
		if($isDeactivated){
			update_post_meta($post_id, Plugin::POST_META_DEACTIVATED, 1);
		} else {
			delete_post_meta($post_id, Plugin::POST_META_DEACTIVATED);
		}
	}

}
