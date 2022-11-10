<?php

namespace Palasthotel\WordPress\FlexWall\Model;

class LoginResult {

	/**
	 * @var bool
	 */
	private $success;

	/**
	 * @var mixed
	 */
	private $data;

	/**
	 * @param $success
	 * @param mixed $data
	 *
	 * @return LoginResult
	 */
	public static function build($success, $data = null){
		return new LoginResult($success, $data);
	}

	/**
	 * @param mixed $data
	 *
	 * @return LoginResult
	 */
	public static function success($data = null){
		return new LoginResult(true, $data);
	}

	/**
	 * @param mixed $data
	 *
	 * @return LoginResult
	 */
	public static function error($data = null){
		return new LoginResult(false, $data);
	}

	/**
	 * LoginResult constructor.
	 *
	 * @param boolean $success
	 * @param mixed $data
	 */
	private function __construct($success, $data) {
		$this->success = $success;
		$this->data = $data;
	}

	/**
	 * @return bool
	 */
	public function wasSuccessful(){
		return $this->success;
	}

	/**
	 * @return mixed
	 */
	public function getData(){
		return $this->data;
	}
}
