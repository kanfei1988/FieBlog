<?php if ($this->session->flashdata('success')):?>
	<p class="success"><?php echo $this->session->flashdata('success');?></p>
<?php elseif ($this->session->flashdata('error')):?>
	<p class="error"><?php echo $this->session->flashdata('error');?></p>
<?php endif;?>