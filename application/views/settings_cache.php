<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
<div id="content">
	<div id="box">
		<h3>缓存设置</h3>
		<?php echo form_open('admin/settings/cache', array('id' => 'settings'));?>
			<ul>
				<li>
					<label class="label">是否开启静态缓存</label>
					<input type="radio" id="cache_enabled" name="cache_enabled" value="0" <?php echo set_radio('cache_enabled', '0', $settings['cache_enabled'] == '0');?> />
					<label for="cache_enabled">不启用</label>
					<input type="radio" id="cache_enabled_1" name="cache_enabled" value="1" <?php echo set_radio('cache_enabled', '1', $settings['cache_enabled'] == '1');?> />
					<label for="cache_enabled_1">启用</label>
					<span class="description">启用静态文件缓存可以加快页面显示，但频繁更新的数据可能会存在时延，你可以<a href="<?php echo site_url('admin/settings/clean_cache');?>">点击此处</a>手动清除缓存.</span>
				</li>
				<li>
					<label for="cache_expire_time" class="label">页面缓存自动刷新时间</label>
					<input type="text" class="text" id="cache_expire_time" name="cache_expire_time" value="<?php echo set_value('cache_expire_time', $settings['cache_expire_time']);?>" />&nbsp;&nbsp;分钟
					<span class="description">缓存过期的时间间隔, 单位为分钟.</span>
					<?php echo form_error('cache_expire_time', '<p class="error">', '</p>');?>
				</li>
				<li>
					<label for="dbcache_expire_time" class="label">数据缓存自动刷新时间</label>
					<input type="text" class="text" id="dbcache_expire_time" name="dbcache_expire_time" value="<?php echo set_value('dbcache_expire_time', $settings['dbcache_expire_time']);?>" />&nbsp;&nbsp;秒
					<span class="description">数据缓存过期的时间间隔, 单位为秒.</span>
					<?php echo form_error('dbcache_expire_time', '<p class="error">', '</p>');?>
				</li>
				<li class="submit">
					<button type="submit" id="button1">保存设置</button>
				</li>
			</ul>
		<?php echo form_close();?>
	</div>
</div>
<?php $this->load->view('footer');?>