<?php

namespace Palasthotel\WordPress\FlexWall;


use Palasthotel\WordPress\FlexWall\Components\Component;


class JS extends Component {

	const HANDLE = "flexwall";

	public function onCreate() {
		add_action("wp_enqueue_scripts", array($this, 'enqueue'));
		add_action("wp_ajax_nopriv_flexwall", array($this, 'handle'));
		add_action("wp_ajax_flexwall", array($this, 'handle'));
	}

	/**
	 * enqueue in theme if needed
	 */
	public function enqueue(){
		$inFooter = true;
		$version = filemtime($this->plugin->path."/js/flexwall.js");
		wp_register_script(
			self::HANDLE,
			$this->plugin->url."/js/flexwall.js",
			array("jquery"),
			$version,
			$inFooter
		);
		wp_localize_script(self::HANDLE, "FlexWall", array(
			"api" => array(
				"url" => admin_url( 'admin-ajax.php' ),
				"params"=> array(
					"action" => "flexwall"
				),
			)
		));
		do_action(Plugin::ACTION_ENQUEUE_FLEXWALL_SCRIPT, self::HANDLE, $inFooter, $version);
	}

	/**
	 *
	 */
	public function handle(){
		$method = sanitize_text_field($_POST["method"]);

		// someone wants to handle?
		do_action(Plugin::ACTION_AJAX, $method, $this);

		// if not handle self
		switch ($method){
			case "login":
				$this->_login();
				break;
			case "logout":
				$this->_logout();
				break;
			case "isLoggedIn":
				$this->_isLoggedIn();
				break;
			default:
				echo "???";
		}
		exit;
	}

	private function _login(){
		$success = $this->plugin->login->doLogin();
		wp_send_json(array(
			"success" => $success->wasSuccessful(),
			"data" => $success->getData(),
		));
		exit;
	}

	private function _logout(){
		wp_send_json(array(
			"sucess" => $this->plugin->login->doLogout()
		));
		exit;
	}

	private function _isLoggedIn(){
		wp_send_json(array(
			"isLoggedIn" => $this->plugin->login->isLoggedIn()
		));
		exit;
	}
}
