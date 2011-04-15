<div id="commentbox">
	<ul id="comments">
		<?php foreach ($comments as $comment):?>
			<li class="comment" id="comment-<?php echo $comment['cid'];?>">
				<p class="name"><?php echo $comment['authorLink'];?></p>
				<p class="time"><?php echo anchor($comment['permalink'], $comment['published']);?></p>
				<div class="floor"><?php echo $comment['thread'];?></div>
				<p class="text"><?php echo $comment['content'];?></p>
				<?php if ($post['allowComment']):?>
					<p class="reply" id="ff"><a href='javascript:void(0)' onclick="reply(this);">回复</a></p>
				<?php endif;?>
			</li>
		<?php endforeach;?>
	</ul>
	<?php echo $pagination;?>
	<?php if ($post['allowComment']):?>
		<div class="form" id="respond">
			<?php echo form_open('comments/index/'.$post['pid']);?>
			<input type="hidden" name="parent" value="0" />
				<p class="input">
					<label for="comment-author">Name:</label>
					<input type="text" class="text" id="comment-author"name="author" value="" />&nbsp;
					<label for="comment-mail">Mail:</label>
					<input type="text" class="text" id="comment-mail" name="mail" value="" />&nbsp;
					<label for="comment-url">URL:</label>
					<input type="text" class="text" id="comment-url" name="url" value="" />
				</p>
				<p class="textarea"><textarea id="comment-text" name="text"></textarea></p>
				<p class="button"><button type="submit">发表评论</button></p>
			<?php echo form_close();?>
		</div>
	<?php endif;?>
</div>
<?php if ($post['allowPing']):?>
	<p><a href="<?php echo site_url('trackback/'.$post['pid']);?>">引用此文章</a></p>
<?php endif;?>