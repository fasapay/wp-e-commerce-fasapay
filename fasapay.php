
<?php

/**
 * Plugin Name: Fasapay
 * Plugin URI: https://fasapay.com/
 * Description: E-payment gateway plugins
 * Version: 1.0
 * Author: Fasapay
 * Author URI: https://fasapay.com/
 */
function mp_admin_actions() {
    add_options_page("Fasapay Plugin Setting", "Fasapay Plugin Setting", 1, "Fasapay_pluggin_setting", "fp_admin");
}

add_action('admin_menu', 'mp_admin_actions');
?>

<?php

function fp_admin() { ?>
    <!--ceck fasapay plugin to shoping card ready -->
    <?php
    $myFile = "../wp-content/plugins/wp-e-commerce/wpsc-merchants/fasapay.merchant.php";
    if (fopen($myFile, 'r')) {
        $wpecomerce = 'choice';
    }
    ?>

    <form name="mp_form" method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <h2>Input fasapay into the shopping cart plugin payment system :</h2>
        <hr>	
        <input type="hidden" name="mp_hidden" value="Y">
        <p><input type="radio" name="sci" value="wpecomerce" <?php echo ($wpecomerce == 'choice') ? 'checked' : '' ?> > WP-Ecomerce</p>
        <p><input type="radio" name="sci" value="cart66"> Cart66</p>
        <p class="submit">
            <input type="submit" name="Submit" value="<?php _e('Add to shoping card plugins', 'mp') ?>" />
        </p>
    </form>

    <?php
    if (isset($_POST['sci']) && $_POST['sci'] === 'wpecomerce'):
        $myFile = "../wp-content/plugins/fasapay/fasapay.merchant.php";
        $myFileLink = fopen($myFile, 'r');
        $myFileContents = fread($myFileLink, filesize($myFile));
        fclose($myFileLink);
        $fileLocation = "../wp-content/plugins/wp-e-commerce/wpsc-merchants/fasapay.merchant.php";
        $file = fopen($fileLocation, "w");
        $content = $myFileContents;
        fwrite($file, $content);
        fclose($file);
    endif;
    if (fopen($myFile, 'r')) {
        echo "Fasapay payment gateway ditambahkan pada plugin shoping card WP-Ecomerce";
    }
    echo "<hr><a target='_blank' href='https://fasapay.com/'><img src='https://fasapay.com/images/fasapay_logo.png'></a>";
}
