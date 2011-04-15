<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
<div id="content">
	<div id="box">
		<h3>个人设置</h3>
		<?php echo form_open('admin/profile', 'id="settings"');?>
			<?php echo form_hidden('uid', $user['uid']);?>
			<ul>
				<li>
					<label for="password" class="label">用户密码</label> 
					<input type="password" class="text" id="password" name="password" value="" />
					<span class="description">您的登录密码，不修改请留空白.</span>
					<?php echo form_error('password', '<p class="error">', '</p>')?>
				</li>
				<li>
					<label for="confirm" class="label">密码确认</label> 
					<input type="password" class="text" id="confirm" name="confirm" value="" />
					<span class="description">请确认你的密码, 与上面输入的密码保持一致.</span>
					<?php echo form_error('confirm', '<p class="error">', '</p>')?>
				</li>
				<li>
					<label for="screenName" class="label">用户昵称</label> 
					<input type="text" class="text" id="screenName" name="screenName" value="<?php echo set_value('screenName', $user['screenName']);?>" />
					<span class="description">您的昵称, 用于前台显示.</span>
					<?php echo form_error('screenName', '<p class="error">', '</p>')?>
				</li>
				<li>
					<label for="mail" class="label">电子邮箱</label> 
					<input type="text" class="text" id="mail" name="mail" value="<?php echo set_value('mail', $user['mail']);?>" />
					<span class="description">您的电子邮件，方便联系.</span>
					<?php echo form_error('mail', '<p class="error">', '</p>')?>
				</li>
				<li>
					<label for="url" class="label">个人主页</label> 
					<input type="text" class="text" id="url" name="url" value="<?php echo set_value('url', $user['url']);?>" />
					<span class="description">您的个人网站.</span>
					<?php echo form_error('url', '<p class="error">', '</p>')?>
				</li>
				<li class="submit">
					<button type="submit" id="button1">更新设置</button>
				</li>
			</ul>
		<?php echo form_close();?>
	</div>
</div>
<?php $this->load->view('footer');?>