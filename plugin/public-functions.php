<?php

use Palasthotel\WordPress\FlexWall\Model\LoginResult;
use Palasthotel\WordPress\FlexWall\Plugin;

/**
 * @return Plugin;
 */
function flexwall_plugin(){
	return Plugin::instance();
}

/**
 * render the content with paywall if needed
 * @param null $more_link_text
 * @param bool $strip_teaser
 */
function flexwall_the_content($more_link_text = null, $strip_teaser = false){
	echo flexwall_plugin()->frontend_protection->get_the_content($more_link_text, $strip_teaser);
}

/**
 * @param int|null $post_id
 *
 * @return bool
 */
function flexwall_post_is_protected($post_id = null){
	return flexwall_plugin()->frontend_protection->isProtectedPost(get_post($post_id));
}

/**
 * check if current user has access to post
 * @param $post_id
 *
 * @return bool
 */
function flexwall_has_access_to($post_id){
	return flexwall_plugin()->frontend_protection->hasAccess(get_post($post_id));
}

/**
 * check login state
 * @return bool
 */
function flexwall_is_logged_in(){
	return flexwall_plugin()->login->isLoggedIn() === true;
}

/**
 * @return null|LoginResult
 */
function flexwall_get_login_result(){
	return flexwall_plugin()->login->loginResult;
}
