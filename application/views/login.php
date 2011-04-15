<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FieBlog -- Login</title>
<style type="text/css">
* {margin:0; padding:0;}
body {background:#ECECDE; font-family:Arial; font-size:13px; padding:30px;}
h1 {width:130px;margin:0px auto; background:url('<?php echo base_url();?>/assets/images/logo.png') no-repeat; padding:10px 0 10px 70px;;}
h1 a {color:#000; text-decoration:none; font-family:Tahoma; font-size:30px;}
#slogan {width:300px; margin:0 auto; font-family:Georgia; font-size:16px; color:#417076; text-align:center;}
#login {width:300px; margin:30px auto; background:#F5F5EF; padding:10px 20px;}
#login ul {list-style-type:none; width:250px; margin:0 auto;}
#login li {margin:15px 0px;}
#login label.label {display:block; font-weight:bold; margin-bottom:5px;}
#login input.text {width:240px; padding:2px; font-family:Verdana; font-size:15px; font-weight:bold; height:20px; line-height:20px; letter-spacing:2px;}
#login button {height:25px; width:50px; text-align:center;}
.radius {border:1px solid #F5F5EF; -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px;}
p.error {font-size:12px; color:#FF2828; padding-top:3px;}
li.error {text-align:center; padding:3px 0; background:#fbe3e4; border:1px solid #fbc2c4; color:#8a1f11;}
</style>
<script type="text/javascript">
</script>
</head>
<body>
	<h1><a href="<?php echo site_url();?>">FieBlog</a></h1>
	<div id="slogan">Leave Some Words, Get More Joy!</div>
	<div id="login" class="radius">
		<?php echo form_open('admin/login/index', 'id="login-form"');?>
			<ul>
				<?php echo ($this->session->flashdata('error')?'<li class="error">'.$this->session->flashdata('error').'</li>':'');?>
				<li>
					<label for="name" class="label">用户名</label>
					<input type="text" class="text" id="name" name="name" value="<?php echo set_value('name', '');?>" />
					<?php echo form_error('name', '<p class="error">', '</p>');?>
				</li>
				<li>
					<label for="password" class="label">密码</label>
					<input type="password" class="text" id="password" name="password" value="" />
					<?php echo form_error('password', '<p class="error">', '</p>');?>
				</li>
				<li>
					<input type="checkbox" id="remember" name="remember" value="1" />
					<label for="remember">记住我的登录信息</label>
				</li>
				<li>
					<button type="submit">登录</button>
				</li>
			</ul>
		<?php echo form_close();?>
	</div>
</body>
</html>