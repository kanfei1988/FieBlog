<?php $this->load->view('header');?>
<div id="content">
	<div class="post single" id="post-<?php echo $post['pid'];?>">
		<h2 class="pagetitle"><?php echo $post['titleLink'];?></h2>
		<div class="postcontent"><?php echo $post['content'];?></div>
		<?php if ($post['type'] == 'post'):?>
		<div class="postmeta">
			<p>
				发布于 <?php echo $post['published'];?>&nbsp;
				by <?php echo $post['authorLink'];?>&nbsp;&nbsp;|&nbsp;
				分类: <?php echo $post['categories'];?>&nbsp;&nbsp;|&nbsp;
				<?php echo $post['commentLink'];?>
			</p>
			<?php if ($post['tags']):?>
				<p>Tag: <?php echo $post['tags'];?></p>
			<?php endif;?>
		</div>
		<?php endif;?>
	</div>
	<?php if ($post['type'] == 'post'):?>
	<ul id="postnav">
		<?php if ($post['nextLink']):?>
			<li class="right"><?php echo $post['nextLink']?> &raquo;</li>
		<?php endif;?>
		<?php if ($post['prevLink']):?>
			<li class="left">&laquo; <?php echo $post['prevLink'];?></li>
		<?php endif;?>
	</ul>
	<?php endif;?>
	<?php $this->load->view('comments');?>
</div><!-- #content -->
<?php $this->load->view('sidebar');?>
<?php $this->load->view('footer');?>

