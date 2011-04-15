<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo settings('blog_title');?></title>
<meta name="Keywords" content="<?php echo settings('blog_keywords');?>" />
<meta name="Description" content="<?php echo settings('blog_description');?>" />
<meta name="generator" content="FieBlog" />
<meta name="template" content=<?php echo settings('current_theme');?> />
<link rel="stylesheet" href="<?php echo theme_url();?>css/classes.css" type="text/css"/> 
<link rel="stylesheet" href="<?php echo theme_url();?>css/style.css" type="text/css" /> 
<!--[if lte IE 7]>
<link rel="stylesheet" href="<?php echo theme_url();?>css/style-ie.css.css" type="text/css"/> 
<![endif]-->
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/js/sh/styles/shCore.css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/js/sh/styles/shThemeDefault.css" />
<style type="text/css">
<?php echo (isset($extra_css) && ($extra_css)) ? $extra_css : '';?>
</style>
<link rel="alternate" type="application/rss+xml" href="<?php echo site_url('feed');?>" title="订阅 <?php echo settings('blog_title');?> 所有文章" />
<link rel="alternate" type="application/rss+xml" href="<?php echo site_url('feed/comments');?>" title="订阅 <?php echo settings('blog_title');?> 所有评论" />
<script type="text/javascript" src="<?php echo theme_url();?>js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo theme_url();?>js/comment-reply.js"></script>
<script type="text/javascript">
<?php echo (isset($extra_js) && ($extra_js)) ? $extra_js : '';?>
</script>
</head>
<body id="top">
	<div id="wrap">
		<div id="navigation">
			<ul>
				<?php foreach (get_navigations() as $navigation):?>
					<li><a href="<?php echo $navigation['url'];?>" target="<?php echo $navigation['target'];?>"><?php echo $navigation['title'];?></a></li>
				<?php endforeach;?>
			</ul>
		</div>
		<div id="header">
			<h1><a href="<?php echo site_url();?>"><span title="<?php echo settings('blog_title');?>"><?php echo settings('blog_title');?></span></a></h1>
			<p><?php echo settings('blog_slogan');?></p>
		</div>