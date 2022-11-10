<?php
/**
 * @var Login $this
 * @var boolean $isLoginError
 */

use Palasthotel\WordPress\FlexWall\Login;
use Palasthotel\WordPress\FlexWall\Plugin;

?>

<p>Bis hier hin und nicht weiter!</p>
<?php
if($isLoginError){
	echo "<p>Fehler beim Anmelden</p>";
}
?>
<form method="post">
	<button name="<?php echo Plugin::POST_LOGIN; ?>">Login</button>
</form>

