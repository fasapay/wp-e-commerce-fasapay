<?php
/**
 * Plugin Name: Fasapay
 * Plugin URI: http://fasapay.wordpress.com
 * Description: E-payment gateway plugins
 * Version: 1.0
 * Author: Fasapay
 * Author URI: http://fasapay.wordpress.com
 */
add_action('admin_menu', 'fasapay_plugin_setup_menu');

function fasapay_plugin_setup_menu() {
    add_menu_page('Fasapay Plugin Page', 'Fasapay', 'manage_options', 'test-plugin', 'fasapay_init', plugins_url('fasapay/images/icons.png'), 7);
}

function fasapay_init() {
    ?>
<h2>Input fasapay into the shopping cart plugin payment system :</h2>

    <!--ceck fasapay plug to shoping card ready -->
<div class="tab">
	<br>
  <button id="defaultOpen" class="tablinks" onclick="openCity(event, 'cart')"><img src="<?php echo  plugins_url('fasapay/images/cart.png');?>" width="15px"> Shoping Cart</button>
	<button class="tablinks" onclick="openCity(event, 'setting')"><img src="<?php echo  plugins_url('fasapay/images/setting.png');?>"width="15px"> Setting</button>
  <button class="tablinks" onclick="openCity(event, 'info')"><img src="<?php echo  plugins_url('fasapay/images/info.png');?>"width="13px"> Info</button>
</div>

<div id="cart" class="tabcontent">	
	<?php include "../wp-content/plugins/fasapay/help/cart.php"; ?>
</div>

<div id="setting" class="tabcontent" style="display:none;">
  <?php include "../wp-content/plugins/fasapay/help/setting.php"; ?>
</div>
<div id="info" class="tabcontent" style="display:none;">
	<?php include "../wp-content/plugins/fasapay/help/info.php"; ?>
</div>
<style><?php include '../wp-content/plugins/fasapay/asset/css/style.css'; ?></style>
<script><?php include '../wp-content/plugins/fasapay/asset/javascript/style.js'; ?></script><br>
<?php
}
