<h3>FasaPay Status</h3><hr />
  <table>
	  <tr>
		  <td style="width:30%;"><b>Url Success</b><p>link halaman landing page pembayaran sukses</p></td>
		  <td> <input style="width:80%;" type="text" id="cp_success" value="<?php echo get_page_by_title( 'success' )->guid;?>"><button onclick="copyarea('cp_success')">Copy</button><hr /></td>
	  </tr>
	  <tr>
		  <td style="width:30%;"><b>Url Fail</b><p>link halaman landing page pembayaran gagal</p></td>
		  <td> <input style="width:80%" type="text" id="cp_fail" value="<?php echo get_page_by_title( 'fail' )->guid;?>"><button onclick="copyarea('cp_fail')">Copy</button><hr /></td>
	  </tr>	
	  <tr>
		  <td style="width:30%;"><b>Url Status</b><p>link halaman  untuk ,melakukan proses validasi ketika pembayaran lunas</p></td>
		  <td> <input style="width:80%" type="text" id="cp_status" value="<?php echo get_page_by_title( 'status' )->guid;?>"><button onclick="copyarea('cp_status')">Copy</button><hr /></td>
	  </tr>  
  </table>
<hr />
<table>	  	
	<tr>
		<td style="width:30%;"><b>Store Name</b></td>
		<td> <input style="width:80%" type="text" id="cp_store_name" value="<?php echo get_option('store_name	');?>"><button onclick="copyarea('cp_store_name')">Copy</button> </td>
	  </tr>
	  <tr>
		  <td style="width:30%;"><b>Scurity Word</b></td>
		  <td> <input style="width:80%" type="text" id="cp_word_scurity" value="<?php echo get_option('word_scurity	');?>"><button onclick="copyarea('cp_word_scurity')">Copy</button> </td>
	  </tr>
	<tr>
		  <td style="width:30%;"><b>Fasapay Store Id</b></td>
		  <td>
			 <?php if(get_option('fasa_id')){?>
			  <input disabled style="width:60%" type="text" id="cp_fasaid" value="<?php echo get_option('fasa_id	');?>"><button style="width:35%" disabled onclick="copyarea('cp_word_scurity')">FasaPay Sandbox</button>
			 <?php } if(get_option('fasa_co_id')){ ?>
			  <input disabled style="width:60%" type="text" id="cp_fasacoid" value="<?php echo get_option('fasa_co_id');?>"><button style="width:35%" disabled onclick="copyarea('cp_word_scurity')">Fasapay.Co.Id</button>
			 <?php } if(get_option('fasa_com')){ ?>
			  <input disabled style="width:60%" type="text" id="cp_fasacom" value="<?php echo get_option('fasa_com	');?>"><button style="width:35%" disabled onclick="copyarea('cp_word_scurity')">FasaPay.Com</button>
		  	 <?php } ?>
		  </td>
	  </tr>
</table>
<hr />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
	
	function copyarea(data_copy) {
		document.getElementById(data_copy).select();
    	document.execCommand('copy');
	}
</script>