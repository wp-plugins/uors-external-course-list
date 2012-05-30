<?php
/*
Plugin Name: UORS External Course List for WordPress
Plugin URI: http://www.uniwits.com/downloads/plugins/wordpress/
Description: This plugin adds a "Quick Reserve" widget to your wordpress weblog sidebar.  With this widget you can display a list of services that you provide on the sidebar, so that your customers can make reservations online.
Version: 0.1.4
Author: Mao, Uniwits System
Author URI: http://www.uniwits.com
License: GPLv2 or Later
*/

class UORS_externalcourselist_WP_Widget extends WP_Widget {
	public function __construct()
	{
		parent::__construct(
			false,
			__('UORS External Course List'),
			array(
				'description'=>__('Displays services outside UORS (Uniwits Online Reservation Service)')
			)
		);
	}
	
	public function filter_default_instance($instance,$fordisplay=false)
	{
		if (!$instance['imgwidth'])
			$instance['imgwidth']=48;
		if (!$instance['imgheight'])
			$instance['imgheight']=48;
		if (!$instance['blkwidth'])
			$instance['blkwidth']=80;
		if ($fordisplay)
		{
			if (!$instance['title'])
				$instance['title']=__('Quick Reserve');
		}
		return $instance;
	}
	
	public function form($instance)
	{
		$instance=$this->filter_default_instance($instance);
//		.
		$form=''
			.'<div style="width: 100%">'
				.'<label>'.__('Your Uniwits Member ID: (Required)').'<br>'
					.'<input type="text" name="'.$this->get_field_name('cp_id').'" style="width: 8em" value="'.$instance['cp_id'].'" /><a href="http://reserv.uniwits.com/qx-cmd-course.publishwizard.register.html" target="_blank">'.__('Register?').'</a>'
				.'</label>'
				.(!ctype_digit($new_instance['cp_id'])?'<div style="font-style: italic">'.__('Your public services will be displayed.').' '.__('Still not have one?').' <a href="http://reserv.uniwits.com/qx-cmd-course.publishwizard.start.html" target="_blank">'.__('Publish&gt;&gt;').'</a><br>&nbsp;</div>':'')
			.'</div>'
			.'<div style="width: 100%">'
				.'<label>'.__('Side Bar Title:').'<br>'
					.'<input type="text" name="'.$this->get_field_name('title').'" style="width: 100%" value="'.$instance['title'].'" />'
				.'</label>'
			.'</div>'
			.'<div style="width: 100%">'
				.'<label>'.__('Total Width Limit:').' <input type="text" name="'.$this->get_field_name('width').'" style="width: 4em; text-align: right" value="'.$instance['width'].'" />'.__('px').' '.__('(Optional)').'</label>'
			.'</div>'
			.'<div style="width: 100%">'
				.'<label>'.__('Total Height Limit:').' <input type="text" name="'.$this->get_field_name('height').'" style="width: 4em; text-align: right" value="'.$instance['height'].'" />'.__('px').' '.__('(Optional)').'</label>'
			.'</div>'
			.'<div style="width: 100%">'
				.'<label>'.__('Image Width:').' <input type="text" name="'.$this->get_field_name('imgwidth').'" style="width: 4em; text-align: right" value="'.$instance['imgwidth'].'" />'.__('px').' '.__('(Optional)').'</label>'
			.'</div>'
			.'<div style="width: 100%">'
				.'<label>'.__('Image Height:').' <input type="text" name="'.$this->get_field_name('imgheight').'" style="width: 4em; text-align: right" value="'.$instance['imgheight'].'" />'.__('px').' '.__('(Optional)').'</label>'
			.'</div>'
			.'<div style="width: 100%">'
				.'<label>'.__('Display Image:').' <input type="checkbox" name="'.$this->get_field_name('disp_image').'" value="1"'.($instance['disp_image']===false?'':' checked="checked"').' /></label>'
			.'</div>'
			.'<div style="width: 100%">'
				.'<label>'.__('Display Service Name:').' <input type="checkbox" name="'.$this->get_field_name('disp_cs_name').'" value="1"'.($instance['disp_cs_name']===false?'':' checked="checked"').' /></label>'
			.'</div>'
			.'<div style="width: 100%">'
				.'<label>'.__('Block Width Limit:').' <input type="text" name="'.$this->get_field_name('blkwidth').'" style="width: 4em; text-align: right" value="'.$instance['blkwidth'].'" />'.__('px').' '.__('(Optional)').'</label>'
			.'</div>'
			.'<div style="width: 100%">'
				.'<label>'.__('Block Height Limit:').' <input type="text" name="'.$this->get_field_name('blkheight').'" style="width: 4em; text-align: right" value="'.$instance['blkheight'].'" />'.__('px').' '.__('(Optional)').'</label>'
			.'</div>'
			.'<div style="width: 100%">'
				.'<label>'.__('Margin:').' <input type="text" name="'.$this->get_field_name('margin').'" style="width: 4em; text-align: right" value="'.$instance['margin'].'" />'.__('px').' '.__('(Optional)').'</label>'
			.'</div>'
			.'<div style="width: 100%">'
				.'<label>'.__('Text Color:').' <input type="text" name="'.$this->get_field_name('textcolor').'" style="width: 4em; text-align: right" value="'.$instance['textcolor'].'" />'.__('(Optional)').' '.__('E.g., #00ff97').'</label>'
			.'</div>'
			.'<div style="width: 100%">'
				.'<label><input type="radio" name="'.$this->get_field_name('target').'" value=""'.($instance['target']?'':' checked="checked"').' />'.__('Link to service detail page').'</label><br>'
				.'<label><input type="radio" name="'.$this->get_field_name('target').'" value="timeavail"'.($instance['target']=='timeavail'?' checked="checked"':'').' />'.__('Link to available time table').'</label>'
			.'</div>'
			.'<div style="width: 100%; text-align: right">'
				.'<a href="http://www.uniwits.com/downloads/plugins/wordpress/archives/21" target="_blank">'.__('More explanation?')."</a>"
			.'</div>'
			.'<div style="width: 100%; text-align: right">'
				.'<a href="http://support.uniwits.com/" target="_blank">'.__('Need support?')."</a>"
			.'</div>'
		;
		echo $form;
	}
	
	public function update($new_instance, $old_instance)
	{
		if (!ctype_digit($new_instance['cp_id']))
			return false;
		if (!$new_instance['disp_image'])
			$new_instance['disp_image']=false;
		if (!$new_instance['disp_cs_name'])
			$new_instance['disp_cs_name']=false;
		return $new_instance;
	}
	
	public function widget($args, $instance)
	{
		$instance=$this->filter_default_instance($instance,true);
		if (!$instance['cp_id'])
			return;
		$params=''
			.($instance['width']?'&width='.$instance['width']:'')
			.($instance['height']?'&height='.$instance['height']:'')
			.($instance['imgwidth']?'&imgwidth='.$instance['imgwidth']:'')
			.($instance['imgheight']?'&imgheight='.$instance['imgheight']:'')
			.($instance['blkwidth']?'&blkwidth='.$instance['blkwidth']:'')
			.($instance['blkheight']?'&blkheight='.$instance['blkheight']:'')
			.($instance['margin']?'&margin='.$instance['margin']:'')
			.(!$instance['disp_image']?'&hideimage=1':'')
			.(!$instance['disp_cs_name']?'&hidecsname=1':'')
			.($instance['textcolor']!=''?'&textcolor='.urlencode($instance['textcolor']):'')
			.($instance['target']=='timeavail'?'&target=timeavail':'')
		;
		?><?=$args['before_widget']?>
        <li class="widget-container widget_meta">
        <?=$args['before_title'].$instance['title'].$args['after_title']?>
        <div class="textwidget">
        <script type="text/javascript" src="http<?=$_SERVER['HTTPS'] || $_SERVER['HTTP_SSL']?'s':''?>://reserv.uniwits.com/cp<?=$instance['cp_id']?>/qx-cmd-external.courselist.js.html<?=$params?'?'.substr($params,1):''?>"></script>
        </div>
        </li><?=$args['after_widget']?>
        <?php
	}
}

function UORS_externalcourselist_WP_Widget()
{
	register_widget('UORS_externalcourselist_WP_Widget');
}

add_action('widgets_init', 'UORS_externalcourselist_WP_Widget');
?>