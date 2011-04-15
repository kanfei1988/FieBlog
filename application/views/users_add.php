<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
<div id="content">
	<div id="box">
		<h3><?php echo isset($user['uid'])?'编辑用户:'.$user['name'] : '添加用户';?></h3>
		<?php echo form_open('admin/users/user'.(isset($user['uid'])?'/'.$user['uid']:''), array('id' => 'settings'));?>
		<?php echo form_hidden('uid', (isset($user['uid'])?$user['uid']:''));?>
			<ul>
				<li>
					<label for="name" class="label">用户名</label> 
					<input type="text" class="text" id="name" name="name" value="<?php echo set_value('name', isset($user['name'])?$user['name']:'');?>" />
					<span class="description">此用户名将作为用户登录时所用的名称.</span>
					<?php echo form_error('name', '<p class="error">', '</p>')?>
				</li>
				<li>
					<label for="password" class="label">用户密码</label> 
					<input type="password" class="text" id="password" name="password" value="" />
					<span class="description">为此用户分配一个密码.</span>
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
					<input type="text" class="text" id="screenName" name="screenName" value="<?php echo set_value('screenName', isset($user['screenName'])?$user['screenName']:'');?>" />
					<span class="description">用户昵称可以与用户名不同, 用于前台显示.</span>
					<?php echo form_error('screenName', '<p class="error">', '</p>')?>
				</li>
				<li>
					<label for="mail" class="label">电子邮箱</label> 
					<input type="text" class="text" id="mail" name="mail" value="<?php echo set_value('mail', isset($user['mail'])?$user['mail']:'');?>" />
					<span class="description">电子邮箱地址将作为此用户的主要联系方式.</span>
					<?php echo form_error('mail', '<p class="error">', '</p>')?>
				</li>
				<li>
					<label for="url" class="label">个人主页</label> 
					<input type="text" class="text" id="url" name="url" value="<?php echo set_value('url', isset($user['url'])?$user['url']:'');?>" />
					<span class="description">该用户的个人网站.</span>
					<?php echo form_error('url', '<p class="error">', '</p>')?>
				</li>
				<li>
					<label for="group" class="label">用户组</label> 
					<select id="group" name="group">
						<option value="contributor" <?php echo set_select('group', 'contributor', isset($user['group'])?$user['group']=='contributor':TRUE);?>>贡献者</option>
						<option value="editor" <?php echo set_select('group', 'editor', isset($user['group'])?$user['group']=='editor':FALSE);?>>编辑</option>
						<option value="administrator" <?php echo set_select('group', 'administrator', isset($user['group'])?$user['group']=='administrator':FALSE);?>>管理员</option>
					</select>
					<span class="description">不同的用户组拥有不同的权限.</span>
				</li>
				<li class="submit">
					<button type="submit" id="button1"><?php echo isset($user)?'编辑用户':'添加用户';?></button>
				</li>
			</ul>
		<?php echo form_close();?>
	</div>
</div>
<?php $this->load->view('footer');?>