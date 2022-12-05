<?php

namespace Palasthotel\WordPress\FlexWall;

/**
 * Plugin Name: FlexWall
 * Description: Flexible content protection that is highly customizable and can be integrated in hopefully every process. Can be used as paywall or whatever wall you need.
 * Version: 1.0
 * Author: PALASTHOTEL (by Edward Bock)
 * Author URI: https://palasthotel.de
 * Text Domain: flexwall
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once __DIR__."/vendor/autoload.php";

/**
 * @property Post $post
 * @property Settings $settings
 * @property MetaBox $meta_box
 * @property GutenbergBlock $gutenberg_block
 * @property FrontendProtection $frontend_protection
 * @property Render $render
 * @property Login $login
 * @property JS $js
 */
class Plugin extends Components\Plugin {

	const DOMAIN = "flexwall";

	/**
	 * cookies
	 */
	const COOKIE_LOGIN = "flexwall_is_logged_in";
	const COOKIE_VALUE = "check ✔";

	/**
	 * post request
	 */
	const POST_LOGIN = "flexwall_lets_login";
	const POST_LOGOUT = "flexwall_lets_logout";

	/**
	 * meta values
	 */
	const POST_META_DEACTIVATED = "flexwall_deactivated";

	/**
	 * filters
	 */
	const FILTER_BODY_CLASS = "flexwall_protected_body_class";
	const FILTER_IS_PROTECTED_PAGE = "flexwall_is_protected_page"; // this has nothing to do with the post type
	const FILTER_IS_PROTECTED_POST = "flexwall_is_protected_post";
	const FILTER_USER_HAS_ACCESS = "flexwall_user_has_access";
	const FILTER_IS_LOGGED_IN = "flexwall_is_logged_in";
	const FILTER_LOGIN = "flexwall_login";
	const FILTER_TEMPLATE_PATHS = "flexwall_template_paths";
	const FILTER_MIN_WORDS = "flexwall_get_min_words_count";

	/**
	 * actions
	 */
	const ACTION_ADD_POST_META_SETTINGS = "flexwall_add_post_meta_settings";
	const ACTION_LOGIN = "flexwall_login";
	const ACTION_LOGIN_SUCCESS = "flexwall_login_success";
	const ACTION_LOGIN_ERROR = "flexwall_login_error";
	const ACTION_LOGOUT = "flexwall_logout";
	const ACTION_ENQUEUE_FLEXWALL_SCRIPT = "flexwall_enqueue_script";
	const ACTION_AJAX = "flexwall_ajax";

	/**
	 * templates
	 */
	const THEME_FOLDER = "plugin-parts";
	const TEMPLATE_WALL = "flexwall.php";

    /**
     * constatns
     */
    const CONSTANT_FLEXWALL_BREAK_IDENTIFIER = "<flexwall-break></flexwall-break>";

	/**
	 * create plugin
	 * @return void
	 */
	public function onCreate() {

		$this->loadTextdomain(self::DOMAIN, "languages");

		$this->post = new Post($this);
		$this->settings = new Settings($this);
		$this->meta_box = new MetaBox($this);
        $this->gutenberg_block = new GutenbergBlock($this);
		$this->frontend_protection = new FrontendProtection($this);
		$this->render = new Render($this);
		$this->login = new Login($this);
		$this->js = new JS($this);

	}
}
Plugin::instance();

require_once dirname(__FILE__)."/public-functions.php";
