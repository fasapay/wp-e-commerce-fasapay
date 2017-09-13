<?php if (!defined('IS_IN_SCRIPT')) { die();  exit; } ?>
<div class="smart_sidebarlft hidden-phone">
<?php if(get_smart('tj_kolom')){$kolomm = get_smart('tj_kolom'); }else{ $kolomm = '3'; } ?>
<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('Widget Kiri')) : ?>
<div class="widget_side"><p>Anda dapat menambahkan widget disini</p></div>
<?php endif;  
if($kolomm == '2'){ ?>
<?php if(is_home() || is_single()){ if(!$wp_query->get('shop')=="login"){ ?>
<?php include LAPAKINSTANPATH.'/side-box.php'; }}?>
<div class="clear"></div><?php } ?>
<?php if(get_smart('tj_display_norek') == 'yes'){ ?>
<?php include LAPAKINSTANPATH.'/bank_side.php'; ?>
<?php include LAPAKINSTANPATH.'/fasapay.php'; ?>
<?php } ?></div>