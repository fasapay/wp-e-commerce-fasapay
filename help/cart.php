 <?php
    if (get_option('shopingcard')) {
        $scichoice = get_option('shopingcard');
    }
	if (get_option('mode')) {
        $modechoice = get_option('mode');
    }
    ?>
    <form name="mp_form" method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">	
		<h2>FasaPay Plugins Option</h2>
		<hr>
		<table>
			<tr>
				<td style="width:30%;"><b>Shoping cart interface</b><p>Pastikan shoping cart interface yang anda pilih sudah terinstal the situs anda.</p></td>
				<td>
					<input type="radio"  name="sci" value="wpecomerce" <?php echo ($scichoice == 'wpecomerce') ? 'checked' : '' ?> > WP-Ecomerce <?php echo ($scichoice == 'wpecomerce') ? ' <i>Active</i>' : '' ?> <hr />
					<input type="radio" id="lapakinstan"  name="sci" value="lapakinstan" <?php echo ($scichoice == 'lapakinstan') ? 'checked' : '' ?>> Lapak Instan <?php echo ($scichoice == 'lapakinstan') ? ' <i>Active</i>' : '' ?>
				</td>
			</tr>		
		</table>
		<hr>
		<table>
			<tr>
				<td style="width:30%;"><b>FasaPay Payment Mode</b><p>jika FasaPay merchant akun tidak diisi maka secara otomatis payment gateway tidak akan aktif.</p></td>
				<td>
					<input type="radio" id="sandbox_mode" onclick="clicked()" name="mode" value="sandbox_mode" <?php echo ($modechoice == 'sandbox_mode') ? 'checked' : '' ?> /> Sandbox mode  <?php echo ($modechoice == 'sandbox_mode') ? ' <i>Active</i>' : '' ?><br>
					<div style="padding-left:25px;">
					<input type="text" id="fasa_id" name="fasa_id" value="<?php echo get_option('fasa_id'); ?>">
					</div><hr />
					<input type="radio" id="live_mode" name="mode" value="live_mode" onclick="clicked()" <?php echo ($modechoice == 'live_mode') ? 'checked' : '' ?> /> Live mode <?php echo ($modechoice == 'live_mode') ? ' <i>Active</i>' : '' ?><br>
					<div style="padding-left:25px;">
					FasaPay.Co.Id<input id="fasa_co_id" type="text" name="fasa_co_id" value="<?php echo get_option('fasa_co_id'); ?>"><br>
					Fasapay.Com<input id="fasa_com" type="text" name="fasa_com" value="<?php echo get_option('fasa_com'); ?>">
					</div>
				</td>
			</tr>		
		</table>
		<hr>
		<table>
			<tr>
				<td style="width:30%;"><b>Store</b><p>Store setting digunakan untuk melakukan validasi merchant pada pembayaran dengan sci fasapay</p></td>
				<td>
					Store Name <br />
					<input type="text" name="store_name" value="<?php echo get_option('store_name'); ?>"><hr />
					Scurity Word <br />
					<input type="text" name="word_scurity" value="<?php echo get_option('word_scurity'); ?>">
				</td>
			</tr>		
		</table>
		<table>
			<tr>
				<td style="width:30%;"></td>
				<td>
					<?php if($scichoice){?>
						<input type="submit" name="Update" value="<?php _e('Update shoping card plugins', 'mp') ?>" />
					<?php } else{?>
						<input type="submit" name="Submit" value="<?php _e('Add to shoping card plugins', 'mp') ?>" />
					<?php }?>
				</td>
			</tr>		
		</table>
		<hr />
    </form>

    <?php
	if(isset($_POST['Submit'])){
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
        add_option('shopingcard', 'wpecomerce', '', 'yes');
        add_option('store_name', $_POST['store_name'], '', 'yes');
        add_option('word_scurity', $_POST['word_scurity'], '', 'yes');
		if(isset($_POST['mode'])){
			if($_POST['mode'] == 'sandbox_mode'){
			update_option( 'mode', 'sandbox_mode' );
			update_option( 'fasa_id', filter_input(INPUT_POST, 'fasa_id'));
			}else{
			update_option( 'mode', 'live_mode' );
			update_option( 'fasa_co_id', filter_input(INPUT_POST, 'fasa_co_id') );
			update_option( 'fasa_com', filter_input(INPUT_POST, 'fasa_com') );
			}
		}
    endif;
    if (isset($_POST['sci']) && $_POST['sci'] === 'lapakinstan'){
            add_option('shopingcard', 'lapakinstan', '', 'yes');
            add_option('store_name', $_POST['store_name'], '', 'yes');
            add_option('word_scurity', $_POST['word_scurity'], '', 'yes');
		if(isset($_POST['mode'])){
			if($_POST['mode'] == 'sandbox_mode'){
			update_option( 'mode', 'sandbox_mode' );
			update_option( 'fasa_id', filter_input(INPUT_POST, 'fasa_id'));
			}else{
			update_option( 'mode', 'live_mode' );
			update_option( 'fasa_co_id', filter_input(INPUT_POST, 'fasa_co_id') );
			update_option( 'fasa_com', filter_input(INPUT_POST, 'fasa_com') );
			}
		}
			include '../wp-content/plugins/fasapay/help/url_content.php';
			include "../wp-content/plugins/fasapay/help/move.php";
            // Create post object
            $status = array(
                'post_title' => wp_strip_all_tags('status'),
                'post_content' => $status_content,
                'post_status' => 'publish',
                'post_author' => 1,
                'post_category' => array(8, 39),
                'post_type' => 'page'
            );
            $success = array(
                'post_title' => wp_strip_all_tags('success'),
                'post_content' => $success_content,
                'post_status' => 'publish',
                'post_author' => 1,
                'post_category' => array(8, 39),
                'post_type' => 'page'
            );
            $fail = array(
                'post_title' => wp_strip_all_tags('fail'),
                'post_content' => $fail_content,
                'post_status' => 'publish',
                'post_author' => 1,
                'post_category' => array(8, 39),
                'post_type' => 'page'
            );
            wp_insert_post($status);
            wp_insert_post($success);
            wp_insert_post($fail);
	}
	}
	include "../wp-content/plugins/fasapay/help/cart_update.php";		   
    if (get_option('shopingcard')) {
        echo "Fasapay payment gateway ditambahkan pada plugin shoping card " . get_option('shopingcard');
    }
	?>