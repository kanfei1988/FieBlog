<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
<div id="content">
	<div id="box" class="metas-manage">
		<h3>导航管理</h3>
		<div class="left">
			<?php echo form_open('admin/navigations/operate', 'name="manage_categories" class="meta_table operate-form"');?>
				<?php echo form_hidden('op', '');?>
				<p class="operate">
					操作：
					<a href="javascript:void(0)" class="operate select-all">全选</a>，
					<a href="javascript:void(0)" class="operate select-none">不选</a>&nbsp;&nbsp;&nbsp;
					选中项：
					<a href="javascript:void(0)" class="operate select-submit" rel="delete" lang="确认要删除这个导航吗？">删除</a>
				</p>
				<table id="navigations_table" class="manage" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th width="40px">&nbsp;</th>
							<th width="120px">标题</th>
							<th width="40px">类型</th>
							<th width="40px">视窗</th>
							<th width="20px">&nbsp;</th>
							<th>网址</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($navigations as $num => $item):?>
							<tr id="navigation-<?php echo $item['nid'];?>" class="<?php echo ($num%2)?'odd':'even';?>">
								<td class="center"><?php echo form_checkbox('nids', $item['nid']);?></td>
								<td><?php echo $item['manageLink'];?></td>
								<td><?php echo strtoupper($item['type']);?></td>
								<td><?php echo ($item['target'] == '_blank')?'BLANK':'SELF';?></td>
								<td><?php echo $item['viewLink'];?></td>
								<td><?php echo mb_word_limiter($item['url'], 60);?></td>
							</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			<?php echo form_close();?>
		</div>
		<div class="right">
			<?php echo form_open('admin/navigations/manage/'.(isset($navigation['nid'])?'/'.$navigation['nid']:''), 'class="meta_form"');?>
				<?php echo form_hidden('nid', (isset($navigation['nid'])?$navigation['nid']:'0'));?>
				<ul>
					<li>
						<label for="title" class="label">导航标题</label>
						<?php echo form_error('title' ,'<p class="error">', '</p>');?>
						<input type="text" class="text" id="title" name="title" value="<?php echo set_value('name', isset($navigation['title'])?$navigation['title']:'');?>" />
						<p class="description">该导航标题将显示在网站前台的页面上.</p>
					</li>
					<li>
						<label for="type" class="label">链接类型</label>
						<select id="type" name="type">
							<option value="url" <?php echo set_select('type', 'url', (isset($navigation['type'])&&$navigation['type']=='url'));?>>网址</option>
							<option value="uri" <?php echo set_select('type', 'uri', (isset($navigation['type'])&&$navigation['type']=='uri'));?>>网站URI</option>
							<option value="page" <?php echo set_select('type', 'page', (isset($navigation['type'])&&$navigation['type']=='page'));?>>页面</option>
						</select>
						<p class="description">请根据需要选择不同类型的导航链接.</p>
					</li>
					<li class="type_url<?php echo (isset($navigation['type'])&&$navigation['type']!='url')?' hidden':'';?>">
						<label for="url" class="label">网址</label>
						<input type="text" class="text" id="url" name="url" value="<?php echo set_value('url', isset($navigation['url']) && $navigation['type']=='url' ? $navigation['url'] : 'http://');?>" />
						<p class="description">请输入完整的URL地址.</p>
					</li>
					<li class="type_uri<?php echo (isset($navigation['type'])&&$navigation['type']!='uri' || empty($navigation['type']))?' hidden':'';?>">
						<label for="uri" class="label">网站URI</label>
						<input type="text" class="text" id="uri" name="uri" value="<?php echo set_value('uri', isset($navigation['uri']) && $navigation['type']=='uri' ? $navigation['uri'] : '');?>" />
						<p class="description">请输入站内的URI段.</p>
					</li>
					<li class="type_page<?php echo (isset($navigation['type'])&&$navigation['type']!='page' || empty($navigation['type']))?' hidden':'';?>">
						<label for="page" class="label" >页面</label>
						<select id="page" name="page">
							<?php foreach ($posts as $post):?>
								<option value="<?php echo $post['pid'];?>"><?php echo mb_word_limiter($post['title'], 10);?></option>
							<?php endforeach;?>
						</select>
						<p class="description">请选择某个页面.</p>
					</li>
					<li>
						<label for="target" class="label">视窗</label>
						<select id="target" name="target">
							<option value="" <?php echo set_select('target', '', (isset($navigation['target'])&&$navigation['target']=='')||!isset($navigation['target']));?>>在原窗口打开(默认)</option>
							<option value="_blank" <?php echo set_select('target', '_blank', (isset($navigation['target'])&&$navigation['target']=='_blank'));?>>在新窗口打开(_blank)</option>
						</select>
					</li>
					<li>
						<button type="submit" id="button1"><?php echo isset($navigation)?'更新导航':'添加导航';?></button>
					</li>
				</ul>
			<?php echo form_close();?>
		</div>
		<br class="clear" />
	</div>
</div>
<?php $this->load->view('footer');?>