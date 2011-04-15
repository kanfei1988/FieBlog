<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
<div id="content">
	<div id="box">
		<h3>基本设置</h3>
		<?php echo form_open('admin/settings/general', array('id' => 'settings'));?>
			<ul>
				<li>
					<label for="blog_title" class="label">站点名称</label>
					<input type="text" class="text" id="blog_title" name="blog_title" value="<?php echo set_value('blog_title', $settings['blog_title']);?>" />
					<span class="description">站点的名称将显示在网页的标题处.</span>
				</li>
				<li>
					<label for="blog_slogan" class="label">站点口号</label>
					<input type="text" class="text" id="blog_slogan" name="blog_slogan" value="<?php echo set_value('blog_slogan', $settings['blog_slogan']);?>" />
					<span class="description">用一句话描述你的站点，它将显示在网页的头部.</span>
				</li>
				<li>
					<label for="blog_description" class="label">站点描述</label>
					<textarea id="blog_description" name="blog_description"><?php echo set_value('blog_description', $settings['blog_description']);?></textarea>
					<span class="description">站点描述将显示在首页网页代码的Meta标签中.</span>
				</li>
				<li>
					<label for="blog_keywords" class="label">关键词</label>
					<input type="text" class="text" id="blog_keywords" name="blog_keywords" value="<?php echo set_value('blog_keywords', $settings['blog_keywords']);?>" />
					<span class="description">以半角逗号","分割多个关键字.</span>
				</li>
				<li>
					<label for="homepage_type" class="label">站点首页</label>
					<select id="homepage_type" name="homepage_type">
						<option value="posts" <?php echo set_select('homepage_type', 'posts', ($settings['homepage_type']=='posts'));?>>文章列表</option>
						<option value="homepage" <?php echo set_select('homepage_type', 'homepage', ($settings['homepage_type']=='homepage'));?>>指定页面</option>
					</select>
					<select id="homepage_pid" name="homepage_pid" class="<?php echo $settings['homepage_type']=='posts'?'hidden':'';?>">
						<?php foreach ($posts as $post):?>
							<option value="<?php echo $post['pid']?>" <?php echo set_select('homepage_pid', $post['pid'], ($settings['homepage_pid']==$post['pid']));?>><?php echo mb_word_limiter($post['title'], 6);?></option>
						<?php endforeach;?>
					</select>
					<span class="description">可以使用文章列表或指定的页面作为网站的首页.</span>
				</li>
				<li>
					<label for="current_theme" class="label">站点主题</label>
					<select id="current_theme" name="current_theme">
						<?php foreach ($themes as $theme):?>
							<option value="<?php echo $theme?>" <?php echo set_select('current_theme', $theme, (settings('current_theme')==$theme));?>><?php echo $theme;?></option>
						<?php endforeach;?>
					</select>
					<select id="current_theme_format" name="current_theme_format">
						<option value="windows" <?php echo set_select('current_theme_format', 'windows', (settings('current_theme_format')=='windows'));?>>Windows</option>
						<option value="unix" <?php echo set_select('current_theme_format', 'unix', (settings('current_theme_format')=='unix'));?>>Unix</option>
						<option value="mac" <?php echo set_select('current_theme_format', 'mac', (settings('current_theme_format')=='mac'));?>>Mac</option>
					</select>
					<span class="description">选择网站的外观主题及文件格式.</span>
				</li>
				<li>
					<label class="label">是否关闭</label>
					<input type="radio" id="blog_status_0" name="blog_status" value="on" <?php echo set_radio('blog_status', 'on', $settings['blog_status'] == 'on');?> />
					<label for="blog_status_0">不关闭</label>
					<input type="radio" id="blog_status_0" name="blog_status" value="off" <?php echo set_radio('blog_status', 'off', $settings['blog_status'] == 'off');?> />
					<label for="blog_status_1">关闭</label>
					<span class="description">是否暂时关闭你的站点.</span>
				</li>
				<li>
					<label for="offline_reason" class="label">站点关闭原因</label>
					<textarea id="offline_reason" name="offline_reason"><?php echo set_value('offline_reason', $settings['offline_reason']);?></textarea>
					<span class="description">关闭你的站点的原因，仅在站点关闭时有效.</span>
				</li>
				<li>
					<label for="upload_dir" class="label">文件上传路径</label>
					<input type="text" class="text" id="upload_dir" name="upload_dir" value="<?php echo set_value('upload_dir', $settings['upload_dir']);?>" />
					<span class="description">相对于程序根目录的相对路径，以 / 结尾.</span>
				</li>
				<li>
					<label for="upload_max_size" class="label">上传文件最大尺寸</label>
					<input type="text" class="text" id="upload_max_size" name="upload_max_size" value="<?php echo set_value('upload_max_size', $settings['upload_max_size']);?>" />
					<span class="description">单位为KB</span>
					<?php echo form_error('upload_max_size', '<p class="error">', '</p>');?>
				</li>
				<li>
					<label for="upload_exts" class="label">允许上传的文件类型</label>
					<input type="text" class="text" id="upload_exts" name="upload_exts" value="<?php echo set_value('upload_exts', $settings['upload_exts']);?>" />
					<span class="description">用|隔开, 例如: txt|doc|ppt</span>
				</li>
				<li>
					<label for="upload_img_exts" class="label">允许上传的图片类型</label>
					<input type="text" class="text" id="upload_img_exts" name="upload_img_exts" value="<?php echo set_value('upload_img_exts', $settings['upload_img_exts']);?>" />
					<span class="description">用|隔开, 例如: jpg|bmp|png</span>
				</li>
				<li class="submit">
					<button type="submit" id="button1">保存设置</button>
				</li>
			</ul>
		<?php echo form_close();?>
	</div>
</div>
<?php $this->load->view('footer');?>