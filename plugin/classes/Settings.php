<?php
/**
 * Created by PhpStorm.
 * User: edward
 * Date: 18.07.18
 * Time: 10:16
 */

namespace Palasthotel\WordPress\FlexWall;


use Palasthotel\WordPress\FlexWall\Components\Component;

class Settings extends Component{

	const SETTING_MIN_WORDS = "min_words";

	const SETTINGS_SECTION = Plugin::DOMAIN.'_settings';

	public function onCreate() {
		add_action('admin_init', array($this, 'init'));
		add_filter('plugin_action_links_' . $this->plugin->basename, array($this, 'add_action_links'));
	}

	// --------------------------
	// --- getter for options ---
	// --------------------------

	/**
	 * @return int
	 */
	public function getMinWordsCount(){
		return apply_filters( Plugin::FILTER_MIN_WORDS, intval($this->get(self::SETTING_MIN_WORDS)) );
	}

	// ---------------------------------
	// --- private options functions ---
	// ---------------------------------

	/**
	 * get option key with domain prefix
	 * @param $name
	 *
	 * @return string
	 */
	private function get_option_key($name){
		return Plugin::DOMAIN."_$name";
	}

	/**
	 * get global plugin setting
	 *
	 * @param $name
	 * @param null $default
	 *
	 * @return mixed
	 */
	private function get($name, $default = null){
		return get_option($this->get_option_key($name), $default);
	}

	/**
	 * set global plugin setting
	 * @param $name
	 * @param $value
	 */
	private function set($name, $value){
		update_option(Plugin::DOMAIN."_$name", $value);
	}

	// ---------------------------------
	// --- settings rendering ---
	// ---------------------------------

	/**
	 * initialize settings page
	 */
	public function init(){
		add_settings_section(
			self::SETTINGS_SECTION,
			'<span id="'.Plugin::DOMAIN.'">FlexWall</span>',
			null, // callback for prolog settings information
			'reading'
		);
		add_settings_field(
			$this->get_option_key(self::SETTING_MIN_WORDS),
			__('Min. words for content protection', Plugin::DOMAIN),
			array($this, "render_setting_words"),
			'reading',
			self::SETTINGS_SECTION
		);
		register_setting( 'reading', $this->get_option_key(self::SETTING_MIN_WORDS) );
	}

	/**
	 * render setting min words
	 */
	public function render_setting_words(){
		?>
		<p>
		<input type="number"
		       name="<?php echo $this->get_option_key(self::SETTING_MIN_WORDS) ?>"
		       value="<?php echo $this->get(self::SETTING_MIN_WORDS); ?>"
		/><br>
		<span class="description"><?php _e("Empty or less than 1 word means no protection.", Plugin::DOMAIN) ?></span></p>
		<?php
	}

	/**
	 * action link to settings on plugins list page
	 * @param $links
	 *
	 * @return array
	 */
	public function add_action_links($links){
		return array_merge($links, array(
			'<a href="'.admin_url('options-reading.php#'.Plugin::DOMAIN).'">Settings</a>'
		));
	}


}
