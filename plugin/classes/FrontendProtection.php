<?php

namespace Palasthotel\WordPress\FlexWall;


use Palasthotel\WordPress\FlexWall\Components\Component;

/**
 * Class FrontendProtection
 *
 * @package FlexWall
 */
class FrontendProtection extends Component{

	public function onCreate() {
		add_filter( Plugin::FILTER_IS_PROTECTED_POST, array(
			$this,
			'is_protected_post',
		), 10, 2 );
		add_filter( Plugin::FILTER_IS_PROTECTED_PAGE, array(
			$this,
			'is_protected_page',
		), 10, 2 );
		add_filter( 'body_class', array( $this, 'body_class' ) );
	}

	/**
	 *
	 * @param $isProtected
	 * @param $post
	 *
	 * @return bool
	 */
	public function is_protected_post( $isProtected, $post ) {
		if ( $isProtected ) {
			return true;
		}
		if(!is_object($post) || !isset($post->ID)) return false;
		return ! $this->plugin->post->isProtectionDeactivated( $post->ID )
		       && str_word_count( strip_tags( $post->post_content ) ) >= $this->plugin->settings->getMinWordsCount();
	}

	/**
	 *
	 * @param $post
	 *
	 * @return bool
	 */
	public function isProtectedPost( $post ) {
		return apply_filters( Plugin::FILTER_IS_PROTECTED_POST, false, $post );
	}

	/**
	 * check if page is protected
	 *
	 * @param boolean $isProtected
	 * @param \WP_Post $post
	 *
	 * @return bool
	 */
	public function is_protected_page( $isProtected, $post ) {
		if ( $isProtected ) {
			return true;
		}

		return is_singular( 'post' ) && is_main_query()
		       && $this->isProtectedPost( $post );
	}

	/**
	 * check if loop post is protected
	 *
	 * @return boolean
	 */
	public function isProtectedPage() {
		return apply_filters( Plugin::FILTER_IS_PROTECTED_PAGE, false, get_post() );
	}

	/**
	 * check access to protected post
	 *
	 * @param $post
	 *
	 * @return boolean
	 */
	public function hasAccess( $post ) {
		$isProtected = $this->isProtectedPost($post);
		return apply_filters( Plugin::FILTER_USER_HAS_ACCESS, !$isProtected, $post );
	}

	/**
	 * @param $classes
	 *
	 * @return array
	 */
	public function body_class( $classes ) {
		$isProtected = $this->isProtectedPage();
		$hasAccess  = $this->hasAccess(get_post());
		if ( $isProtected && $hasAccess ) {
			$classes[] = apply_filters( Plugin::FILTER_BODY_CLASS, "is-flexwall-open" );
		} else if ( $isProtected ) {
			$classes[] = apply_filters( Plugin::FILTER_BODY_CLASS, "is-flexwall-protected" );
		}

		return $classes;
	}

	/**
	 * render the content of a post with paywall modification
	 *
	 * @param string|null $more_link_text
	 * @param bool $strip_teaser
	 *
	 * @return string
	 */
	public function get_the_content( $more_link_text = NULL, $strip_teaser = false ) {

		// get normal the_content
		ob_start();
		the_content( $more_link_text, $strip_teaser );
		$content = ob_get_contents();
		ob_clean();

		// if its not protected or we have access show full content
		if ( ! $this->isProtectedPage() || $this->hasAccess( get_post() ) ) {
			return $content;
		}

		// trim content
		ob_start();
		$isLoginError = $this->plugin->login->hasLoginError();
		include $this->plugin->render->get_template_path( Plugin::TEMPLATE_WALL );
		$wall = ob_get_contents();
		ob_end_clean();

		return $this->shorten_content( $content, $this->plugin->settings->getMinWordsCount() ) . $wall;
	}

	/**
	 * shorten content by words
	 *
	 * @param $content
	 * @param $words
	 *
	 * @return string
	 */
	private function shorten_content( $content, $words ) {
		// Source: https://core.trac.wordpress.org/ticket/29533#comment:3
		return force_balance_tags( html_entity_decode( wp_trim_words( htmlentities( $content ), $words ) ) );
	}

}
