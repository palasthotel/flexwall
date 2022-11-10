<?php

namespace Palasthotel\WordPress\FlexWall;

use Palasthotel\WordPress\FlexWall\Components\Component;
use Palasthotel\WordPress\FlexWall\Model\LoginResult;

class Login extends Component{

	/**
	 * @var null|LoginResult
	 */
	var $loginResult = null;


	public function onCreate() {
		add_action( 'init', array( $this, 'init' ), 10, 0 );
		add_filter( Plugin::FILTER_IS_LOGGED_IN, array(
			$this,
			"is_logged_in",
		) );
		add_filter( Plugin::FILTER_USER_HAS_ACCESS, array(
			$this,
			"has_access",
		), 1);
	}

	/**
	 * init
	 */
	public function init() {
		if ( isset( $_POST[ Plugin::POST_LOGIN ] ) ) {

			if ( $this->doLogin()->wasSuccessful() ) {
				// redirect to prevent resending post vars on browser reload
				wp_redirect( add_query_arg( $_GET ), 301 );
				exit;
			}

		} else if( isset($_POST[Plugin::POST_LOGOUT])){
			$this->doLogout();
			wp_redirect(add_query_arg($_GET), 301);
			exit;
		}
	}

	/**
	 * execute the login functions
	 *
	 * @return LoginResult
	 *
	 */
	public function doLogin(){
		/**
		 * @var null|boolean|LoginResult $success
		 */
		$loginResult = apply_filters(Plugin::FILTER_LOGIN, null);

		if($loginResult == null){
			// if there is no extension all login requests will be successful
			$loginResult = LoginResult::build(true, "flexwall core login");
		} else if($loginResult === true){
			// deprecated boolean response is always translated to login result
			$loginResult = LoginResult::build(true, "boolean result auto-conversion");
		}

		if(!($loginResult instanceof LoginResult)){
			// if $loginResult is anything else but LoginResult object than something went wrong
			$loginResult = LoginResult::error("auto-conversion to LoginResult::error() because of invalid loginResult variable");
		}

		// make it global
		$this->loginResult = $loginResult;

		do_action(Plugin::ACTION_LOGIN, $loginResult);

		if(
			$loginResult instanceof LoginResult
			&&
			$loginResult->wasSuccessful()
		){
			$this->setLoggedIn(DAY_IN_SECONDS * 30);
			do_action(Plugin::ACTION_LOGIN_SUCCESS, $loginResult);
			return $loginResult;
		}

		do_action(Plugin::ACTION_LOGIN_ERROR, $this->loginResult);
		return $loginResult;
	}

	/**
	 * execute the logout functions
	 * @return bool
	 */
	public function doLogout(){
		do_action(Plugin::ACTION_LOGOUT);
		$this->setLoggedIn(DAY_IN_SECONDS * -1);
		return true;
	}

	/**
	 * @return bool
	 */
	public function hasLoginError(){
		return $this->loginResult instanceof LoginResult
		       &&
		       !$this->loginResult->wasSuccessful();
	}

	/**
	 * set logged in cookie
	 *
	 * @param $seconds_till_expires
	 */
	public function setLoggedIn($seconds_till_expires) {
		setcookie( Plugin::COOKIE_LOGIN, Plugin::COOKIE_VALUE, time() + $seconds_till_expires, COOKIEPATH, COOKIE_DOMAIN );
	}

	/**
	 * ask for login state
	 *
	 * @return boolean
	 */
	public function isLoggedIn() {
		return apply_filters( Plugin::FILTER_IS_LOGGED_IN, NULL );
	}

	/**
	 * simple login check
	 *
	 * @param $isLoggedIn
	 *
	 * @return bool
	 */
	public function is_logged_in( $isLoggedIn ) {
		// if is not null another implementation handled the login
		if ( $isLoggedIn != NULL ) {
			return $isLoggedIn;
		}

		// check login state
		if ( isset( $_COOKIE[ Plugin::COOKIE_LOGIN ] ) && $_COOKIE[ Plugin::COOKIE_LOGIN ] == Plugin::COOKIE_VALUE ) {
			// TODO: validate if session cookie is ok
			// TODO: generated values or something?
			return true;
		}

		// if not... you are not logged in
		return false;
	}

	/**
	 * @param boolean $hasAccess
	 *
	 * @return boolean
	 */
	public function has_access($hasAccess) {
		// login grants access or if has already access
		return $this->isLoggedIn() || $hasAccess;
	}

}
