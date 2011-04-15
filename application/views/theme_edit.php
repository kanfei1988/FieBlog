<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
<div id="content">
	<div id="box" class="theme">
		<h3>编辑主题<?php echo ($current_file)?':'.basename($current_file):'';?></h3>
		<div id="file-tree" class="left">
			<ul id="browser" class="filetree">
				<?php echo $file_tree;?>
			</ul>
		</div>
		<div id="file-edit" class="left">
			<?php echo form_open('admin/themes/manage', 'id="content-form"');?>
				<input type="hidden" name="current_file" value="<?php echo $current_file;?>" />
				<textarea id="file-content" name="current_content" class="code"><?php echo $current_content;?></textarea>
				<script type="text/javascript">
					function theme_save(id, content) {
						var form = document.getElementById('content-form');
						var file_content = document.getElementById('file-content');
						file_content.value = content;
						form.submit();
					}
					editAreaLoader.init({
						id: 'file-content',
						start_highlight: true,
						allow_resize: 'no',
						allow_toggle: false,
						word_wrap: false,
						language: 'zh',
						syntax: '<?php echo $current_extension;?>',
						begin_toolbar: 'save',
						save_callback: 'theme_save'
						
					});
				</script>
			<?php echo form_close();?>
		</div>
		<br style="clear:both;" />
	</div>
</div>
<?php $this->load->view('footer');?>