<?php if (!defined('IS_IN_SCRIPT')) { die();  exit; } ?>
<?php if($wp_query->get('shop')!=="konfirmasi"){ ?>
<?php if(get_option('mode')=='sandbox_mode'){?>
<div class="smart_box">
<h3>Pembayaran Melalui Faspaay Sandbox</h3>
<div class="smart_bank">
<div class="banklain"><img src="https://fasapay.com/images/fasapay_logo.png" /></div>
<div class="bank_detail">
Id : <?php echo get_option('fasa_id');?><br/>
Fasapay Sandbox
</div>
</div>
</div>
<?php } ?>
<?php if(get_option('mode')=='live_mode'){?>
<div class="smart_box">
<h3>Pembayaran Melalui Faspaay</h3>
<?php if(get_option('fasa_co_id')){?>
<div class="smart_bank">
<div class="banklain"><img src="https://fasapay.com/images/fasapay_logo.png" /></div>
<div class="bank_detail">
Id : <?php echo get_option('fasa_co_id');?><br/>
FasaPay.Co.Id Account
</div>
</div>
<?php } ?>
<?php if(get_option('fasa_com')){?>
<div class="smart_bank">
<div class="banklain"><img src="https://fasapay.com/images/fasapay_logo.png" /></div>
<div class="bank_detail">
Id : <?php echo get_option('fasa_com');?><br/>
FasaPay.Com Account
</div>
</div>
<?php } ?>
</div>
<?php } ?>

<?php } ?>