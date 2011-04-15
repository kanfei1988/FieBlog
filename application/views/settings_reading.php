<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
<div id="content">
	<div id="box">
		<h3>文章设置</h3>
		<?php echo form_open('admin/settings/reading', array('id' => 'settings'));?>
			<ul>
				<li>
					<label for="post_date_format" class="label">文章日期格式</label>
					<input type="text" class="text" id="post_date_format" name="post_date_format" value="<?php echo set_value('post_date_format', $settings['post_date_format']);?>" />
					<span class="description">显示文章日期的默认格式，具体写法请参考PHP手册.</span>
				</li>
				<li>
					<label for="posts_page_size" class="label">每页文章数目</label>
					<input type="text" class="text" id="posts_page_size" name="posts_page_size" value="<?php echo set_value('posts_page_size', $settings['posts_page_size']);?>" />
					<span class="description">此数目用于指定文章归档输出时每页显示的文章数目.</span>
					<?php echo form_error('posts_page_size', '<p class="error">', '</p>');?>
				</li>
				<li>
					<label for="posts_list_size" class="label">文章列表数目</label>
					<input type="text" class="text" id="posts_list_size" name="posts_list_size" value="<?php echo set_value('posts_list_size', $settings['posts_list_size']);?>" />
					<span class="description">此数目用于指定显示在侧边拦中的文章列表数目.</span>
					<?php echo form_error('posts_list_size', '<p class="error">', '</p>');?>
				</li>
				<li>
					<label class="label">文章全文输出</label>
					<input type="radio" id="posts_full_text" name="posts_full_text" value="0" <?php echo set_radio('posts_full_text', '0', $settings['posts_full_text'] == '0');?> />
					<label for="posts_full_text">文章全文输出</label>
					<input type="radio" id="posts_full_text_1" name="posts_full_text" value="1" <?php echo set_radio('posts_full_text', '1', $settings['posts_full_text'] == '1');?> />
					<label for="feed_full_text_1">仅输出摘要</label>
					<span class="description">如果你不希望在文章列表中输出文章全文,请使用仅输出摘要选项.</span>
				</li>
				<li>
					<label class="label">聚合全文输出</label>
					<input type="radio" id="feed_full_text" name="feed_full_text" value="0" <?php echo set_radio('feed_full_text', '0', $settings['feed_full_text'] == '0');?> />
					<label for="feed_full_text">聚合全文输出</label>
					<input type="radio" id="feed_full_text_1" name="feed_full_text" value="1" <?php echo set_radio('feed_full_text', '1', $settings['feed_full_text'] == '1');?> />
					<label for="feed_full_text_1">仅输出摘要</label>
					<span class="description">如果你不希望在聚合中输出文章全文,请使用仅输出摘要选项.</span>
				</li>
				<li class="submit">
					<button type="submit" id="button1">保存设置</button>
				</li>
			</ul>
		<?php echo form_close();?>
	</div>
</div>
<?php $this->load->view('footer');?>