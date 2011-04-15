<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
<div id="content">
	<div id="box">
		<h3>评论设置</h3>
		<?php echo form_open('admin/settings/discussion', array('id' => 'settings'));?>
			<ul>
				<li>
					<label for="comments_date_format" class="label">评论日期格式</label>
					<input type="text" class="text" id="comments_date_format" name="comments_date_format" value="<?php echo set_value('comments_date_format', $settings['comments_date_format']);?>" />
					<span class="description">显示评论日期的默认格式，具体写法请参考PHP手册.</span>
				</li>
				<li>
					<label for="comments_page_size" class="label">每页评论数目</label>
					<input type="text" class="text" id="comments_page_size" name="comments_page_size" value="<?php echo set_value('comments_page_size', $settings['comments_page_size']);?>" />
					<span class="description">此数目用于显示文章时每页显示的评论数目.</span>
					<?php echo form_error('comments_page_size', '<p class="error">', '</p>');?>
				</li>
				<li>
					<label for="comments_list_size" class="label">评论列表数目</label>
					<input type="text" class="text" id="comments_list_size" name="comments_list_size" value="<?php echo set_value('comments_list_size', $settings['comments_list_size']);?>" />
					<span class="description">此数目用于指定显示在侧边拦中的评论列表数目.</span>
					<?php echo form_error('comments_list_size', '<p class="error">', '</p>');?>
				</li>
				<li>
					<label class="label">是否使用nofollow属性</label>
					<input type="radio" id="comments_url_no_follow" name="comments_url_no_follow" value="0" <?php echo set_radio('comments_url_no_follow', '0', $settings['comments_url_no_follow'] == '0');?> />
					<label for="comments_url_no_follow">不启用</label>
					<input type="radio" id="comments_url_no_follow_1" name="comments_url_no_follow" value="1" <?php echo set_radio('comments_url_no_follow', '1', $settings['comments_url_no_follow'] == '1');?> />
					<label for="comments_url_no_follow_1">启用</label>
					<span class="description">关于nofollow的信息请参考<a href="#">wikipedia上的解释.</a></span>
				</li>
				<li>
					<label class="label">评论审核</label>
					<input type="radio" id="comments_require_moderation" name="comments_require_moderation" value="0" <?php echo set_radio('comments_require_moderation', '0', $settings['comments_require_moderation'] == '0');?> />
					<label for="comments_require_moderation">不启用</label>
					<input type="radio" id="comments_require_moderation_1" name="comments_require_moderation" value="1" <?php echo set_radio('comments_require_moderation', '1', $settings['comments_require_moderation'] == '1');?> />
					<label for="comments_require_moderation_1">启用</label>
					<span class="description">开此选项后,所有提交的评论,引用通告和广播将被标记为待审核.</span>
				</li>
				<li>
					<label class="label" for="comments_auto_close">在文章发布一段时间后自动关闭反馈功能</label>
					<select id="comments_auto_close" name="comments_auto_close">
						<option value="0" <?php echo set_select('comments_auto_close', '0', $settings['comments_auto_close'] == '0');?>>永不关闭</option>
						<option value="86400" <?php echo set_select('comments_auto_close', '86400', $settings['comments_auto_close'] == '86400');?>> 一天后关闭</option>
						<option value="259200" <?php echo set_select('comments_auto_close', '259200', $settings['comments_auto_close'] == '259200');?>>三天后关闭</option>
						<option value="1296000" <?php echo set_select('comments_auto_close', '1296000', $settings['comments_auto_close'] == '1296000');?>>半个月后关闭</option>
						<option value="2592000" <?php echo set_select('comments_auto_close', '2592000', $settings['comments_auto_close'] == '2592000');?>>一个月后关闭</option>
						<option value="7776000" <?php echo set_select('comments_auto_close', '7776000', $settings['comments_auto_close'] == '7776000');?>>三个月后关闭</option>
						<option value="15552000" <?php echo set_select('comments_auto_close', '15552000', $settings['comments_auto_close'] == '15552000');?>>半年后关闭</option>
						<option value="31536000" <?php echo set_select('comments_auto_close', '31536000', $settings['comments_auto_close'] == '31536000');?>>一年后关闭</option>
					</select>
					<span class="description">打开此选项后, 发布时间超过此设置文章的反馈功能将被关闭.</span>
				</li>
				<li>
					<label class="label">必须填写邮箱</label>
					<input type="radio" id="comments_require_mail" name="comments_require_mail" value="0" <?php echo set_radio('comments_require_mail', '0', $settings['comments_require_mail'] == '0');?> />
					<label for="comments_require_mail">不需要</label>
					<input type="radio" id="comments_require_mail_1" name="comments_require_mail" value="1" <?php echo set_radio('comments_require_mail', '1', $settings['comments_require_mail'] == '1');?> />
					<label for="comments_require_mail_1">需要</label>
				</li>
				<li>
					<label class="label">必须填写网址</label>
					<input type="radio" id="comments_require_url" name="comments_require_url" value="0" <?php echo set_radio('comments_require_url', '0', $settings['comments_require_url'] == '0');?> />
					<label for="comments_require_url">不需要</label>
					<input type="radio" id="comments_require_url_1" name="comments_require_url" value="1" <?php echo set_radio('comments_require_url', '1', $settings['comments_require_url'] == '1');?> />
					<label for="comments_require_url_1">需要</label>
				</li>
				<li>
					<label for="comments_allowed_html" class="label">允许使用的HTML标签</label>
					<textarea id="comments_allowed_html" name="comments_allowed_html"><?php echo set_value('comments_allowed_html', $settings['comments_allowed_html']);?></textarea>
					<span class="description">默认的用户评论不允许填写任何的HTML标签.</span>
				</li>
				<li class="submit">
					<button type="submit" id="button1">保存设置</button>
				</li>
			</ul>
		<?php echo form_close();?>
	</div>
</div>
<?php $this->load->view('footer');?>