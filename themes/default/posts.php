<?php $this->load->view('header');?>
<div id="content">
	<?php foreach ($posts as $post):?>
		<div class="post" id="post-<?php echo $post['pid'];?>">
			<h2 class="pagetitle"><?php echo $post['titleLink'];?></h2>
			<div class="postcontent">
				<?php if (settings('posts_full_text') == '1'):?>
					<?php echo $post['excerpt'];?>
				<?php else:?>
					<?php echo $post['content'];?>
				<?php endif;?>
			</div>
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
		</div>
	<?php endforeach;?>
	<div class="pagenav"><?php echo $pagination;?></div>
</div><!-- #content -->
<?php $this->load->view('sidebar');?>
<?php $this->load->view('footer');?>