<?php if (!defined('IS_IN_SCRIPT')) { die();  exit; } ?>
<div class="smart_sidebarrht">
<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('Widget Kanan')) : ?>
<?php endif; ?> 
</div>
<div align="center" class="kat-log">
<a href="<?php bloginfo('home'); ?>/shop/katalog" class="btn btn-info cl"><i class="icon-list-alt icon-white"></i> Lihat Katalog</a></div>
<?php if(get_smart('tj_display_norek') == 'yes'){ ?>
<?php include LAPAKINSTANPATH.'/bank_side.php'; ?>
<?php include LAPAKINSTANPATH.'/fasapay.php'; ?>
<?php } ?>