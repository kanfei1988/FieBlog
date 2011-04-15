<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
<div id="content">
	<div id="box">
		<h3>
			<?php if($type == 'post'):?>
				<?php echo isset($post['pid'])?'编辑文章:'.$post['title']:'撰写新文章';?>
			<?php else:?>
				<?php echo isset($post['pid'])?'编辑页面:'.$post['title']:'创建新页面';?>
			<?php endif;?>
		</h3>
		<?php echo form_open('admin/posts/write/'.$type.(isset($post['pid'])?'/'.$post['pid']:''), array('id' => 'write'));?>
			<?php echo form_hidden('type', $type);?>
			<?php echo form_hidden('status', 'publish');?>
			<?php echo form_hidden('uid', (isset($post['uid'])?$post['uid']:$this->auth->get('uid')));?>
			<div class="content">
				<div>
					<p>
						<label for="title" class="label">标题</label>
						<?php echo form_error('title', '<p class="error">', '</p>');?>
						<input type="text" class="text" id="title" name="title" value="<?php echo set_value('title', isset($post['title'])?$post['title']:'');?>" />
					</p>
					<p>
						<label for="text" class="label">内容</label>
						<?php echo form_error('text', '<p class="error">', '</p>');?>
						<textarea id="post_text" name="text"><?php echo set_value('text', isset($post['text'])?$post['text']:'');?></textarea>
					</p>
					<p class="trackback" id="extra">
						<?php if ($type == 'post'):?>
							<a href="javascript:void(0)" id="trackback-menu">引用通告</a>
						<?php endif;?>
						&nbsp;&nbsp;<a href="javascript:void(0)" id="css-menu">CSS</a>
						&nbsp;&nbsp;<a href="javascript:void(0)" id="js-menu">JavaScript</a>
					</p>
					<script type="text/javascript">
						KE.show({
							id : 'text',
							newlineTag : 'br',
							resizeMode : 1,
							fileUploadJson: '<?php echo site_url("admin/upload/file_upload_json");?>',
							imageUploadJson: '<?php echo site_url("admin/upload/upload_json");?>',
							fileManagerJson: '<?php echo site_url("admin/upload/file_manager_json");?>',
							allowFileManager : true
						});
					</script>
					<p class="submit">
						<button id="button1" type="button">保存并继续编辑</button>
						<button id="button2" type="submit"><?php echo ($type=='post')?'更新这篇文章':'发布页面';?></button>
					</p>
					<br style="clear:both"/>
					<p class="extra">
						<?php if ($type == 'post'):?>
							<textarea id="trackback" name="trackback"><?php echo set_value('trackback');?></textarea>
						<?php endif;?>
						<textarea id="css" name="css"><?php echo set_value('css', isset($post['css'])?$post['css']:'');?></textarea>
						<textarea id="js" name="js"><?php echo set_value('js', isset($post['js'])?$post['js']:'');?></textarea>
					</p>
					<script type="text/javascript">
						editAreaLoader.init({
							id: 'js',
							display: "later",
							start_highlight: true,
							allow_resize: "no",
							allow_toggle: false,
							word_wrap: false,
							language: 'zh',
							syntax: 'js'
						});
						editAreaLoader.init({
							id: 'css',
							display: "later",
							start_highlight: true,
							allow_resize: "no",
							allow_toggle: false,
							word_wrap: false,
							language: 'zh',
							syntax: 'css'
						});
					</script>
				</div>
			</div>
			<div class="options">
				<ul class="post-option">
					<li class="post-created">
						<label for="created" class="label">发布时间</label>
						<p><input type="text" class="text" id="created" name="created" value="<?php echo set_value('slug', (isset($post['created'])?date('Y-m-d H:i', $post['created']):date('Y-m-d H:i')));?>" autocomplete="off" /></p>
					</li>
					<li>
						<label for="slug" class="label">缩略名</label>
						<?php echo form_error('slug', '<p class="error">', '</p>');?>
						<p><input type="text" class="text" id="slug" name="slug" value="<?php echo set_value('slug', (isset($post['slug'])?$post['slug']:''));?>" /></p>
						<p class="description">为这篇日志自定义链接地址, 有利于搜索引擎收录</p>
					</li>
					<?php if($type=='post'):?>
						<li class="post-category select">
							<label class="label">分类</label>
							<?php echo form_error('category[]', '<p class="error">', '</p>');?>
							<ul class="categories">
							<?php foreach ($categories as $category):?>
								<li>
									<input type="checkbox" id="category_<?php echo $category['mid']?>" name="category[]" value="<?php echo $category['mid'];?>" <?php echo set_checkbox('category[]', $category['mid'], (isset($post['category_ids'])?in_array($category['mid'], $post['category_ids'], TRUE):FALSE));?> />
									<label for="category_<?php echo $category['mid'];?>"><?php echo mb_word_limiter($category['name'], 10);?></label>
								</li>
							<?php endforeach;?>
							</ul>
						</li>
						<li class="post-tags">
							<label for="tags" class="label">标签</label>
							<p><input type="text" class="text" id="tags" name="tags" value="<?php echo set_value('tags', (isset($post['tags_str'])?$post['tags_str']:''));?>" /></p>
						</li>
					<?php endif;?>
					<li class="post-options select">
						<label class="label">选项设置</label>
						<ul>
							<li>
								<input type="checkbox" id="allowComment" name="allowComment" value="1" <?php echo set_checkbox('allowComment', '1', isset($post['allowComment'])?$post['allowComment']=='1':TRUE);?> />
								<label for="allowComment">允许评论</label>
							</li>
							<li>
								<input type="checkbox" id="allowPing" name="allowPing" value="1" <?php echo set_checkbox('allowPing', '1', isset($post['allowPing'])?$post['allowPing']=='1':TRUE);?> />
								<label for="allowPing">允许被引用</label>
							</li>
							<li>
								<input type="checkbox" id="allowFeed" name="allowFeed" value="1" <?php echo set_checkbox('allowFeed', '1', isset($post['allowFeed'])?$post['allowFeed']=='1':TRUE);?> />
								<label for="allowFeed">允许在聚合中出现</label>
							</li>
						</ul>
					</li>
				</ul>
			</div>
			<div style="clear:both;"></div>
		<?php echo form_close();?>
	</div>
</div>
<?php $this->load->view('footer');?>