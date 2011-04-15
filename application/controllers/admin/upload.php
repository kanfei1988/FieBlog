<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends MY_Admin_Controller
{
	// 上传路径
	private $upload_path;

	// 上传路径URL
	private $upload_url;

	// 上传文件最大尺寸
	private $max_size;

	// 允许上传文件类型
	private $allowed_types;

	// 上传文件重命名
	private $file_name;

	// 文件排序规则
	private $order;

	// 图片类型
	private $ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');

	function __construct()
	{
		parent::__construct();

		is_contributor();

		$this->load->library('Services_JSON', '', 'json');

		$this->upload_path = settings('upload_dir');

		$this->upload_url = base_url() . settings('upload_dir');

		$this->max_size = settings('upload_max_size');

		$this->file_name = date('YmdHis') . '_' . rand(10000, 99999);
	}

	/**
	 * 上传文件
	 *
	 * @access	public
	 * @return	void
	 */
	function file_upload_json()
	{
		$this->allowed_types = settings('upload_exts');

		$config = array(
			'upload_path'	=> $this->upload_path,
			'max_size'		=> $this->max_size,
			'allowed_types'	=> $this->allowed_types,
			'file_name'		=> $this->file_name
		);
		$this->load->library('upload', $config);
		
		if (! $this->upload->do_upload('upfile'))
		{
			$this->alert($this->upload->display_errors());
		}
		else
		{
			$data = $this->upload->data();
			$file_url = $this->upload_url . $data['file_name'];
			header('Content-type: text/html; charset=UTF-8');
			echo $this->json->encode(array('error' => 0, 'url' => $file_url, 'fileName' => $data['file_name']));
		}
	}

	/**
	 * 上传图片
	 *
	 * @access	public
	 * @return	void
	 */
	function upload_json()
	{
		$this->allowed_types = settings('upload_img_exts');

		$config = array(
			'upload_path'	=> $this->upload_path,
			'max_size'		=> $this->max_size,
			'allowed_types'	=> $this->allowed_types,
			'file_name'		=> $this->file_name
		);
		$this->load->library('upload', $config);
		
		if (! $this->upload->do_upload('imgFile'))
		{
			$this->alert($this->upload->display_errors());
		}
		else
		{
			$data = $this->upload->data();
			$file_url = $this->upload_url.$data['file_name'];
			header('Content-type: text/html; charset=UTF-8');
			echo $this->json->encode(array('error' => 0, 'url' => $file_url));
		}
	}

	/**
	 * 显示错误信息
	 *
	 * @access	public
	 * @param	string	$msg	错误消息
	 * @return	void
	 */	
	function alert($msg)
	{
		header('Content-type: text/html; charset=UTF-8');
		echo $this->json->encode(array('error' => 1, 'message' => $msg));
		exit;
	}

	/**
	 * 图片管理
	 *
	 * @access	public
	 * @return	void
	 */
	function file_manager_json()
	{
		//根据path参数，设置各路径和URL
		if ( ! $this->input->get('path'))
		{
			$current_path = realpath($this->upload_path) . '/';
			$current_url = $this->upload_url;
			$current_dir_path = '';
			$moveup_dir_path = '';
		}
		else
		{
			$current_path = realpath($this->upload_path) . '/' . $this->input->get('path');
			$current_url = $this->upload_url . $this->input->get('path');
			$current_dir_path = $this->input->get('path');
			$moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
		}
		
		//排序形式，name or size or type
		$this->order = $this->input->get('order') ? strtolower($this->input->get('order')) : 'name';

		//不允许使用..移动到上一级目录
		if (preg_match('/\.\./', $current_path))
		{
			echo 'Access is not allowed.';
			exit;
		}

		//最后一个字符不是 /
		if (!preg_match('/\/$/', $current_path))
		{
			echo 'Parameter is not valid.';
			exit;
		}

		//目录不存在或不是目录
		if (!file_exists($current_path) || !is_dir($current_path))
		{
			echo 'Directory does not exist.';
			exit;
		}

		//遍历目录取得文件信息
		$file_list = array();

		if ($handle = opendir($current_path))
		{
			$i = 0;
			while (false !== ($filename = readdir($handle)))
			{
				if ($filename{0} == '.') continue;
				$file = $current_path . $filename;
				if (is_dir($file))
				{
					$file_list[$i]['is_dir'] = true;								//是否文件夹
					$file_list[$i]['has_file'] = (count(scandir($file)) > 2);		//文件夹是否包含文件
					$file_list[$i]['filesize'] = 0;									//文件大小
					$file_list[$i]['is_photo'] = false;								//是否图片
					$file_list[$i]['filetype'] = '';								//文件类别，用扩展名判断
				}
				else
				{
					$file_list[$i]['is_dir'] = false;
					$file_list[$i]['has_file'] = false;
					$file_list[$i]['filesize'] = filesize($file);
					$file_list[$i]['dir_path'] = '';
					$file_ext = strtolower(array_pop(explode('.', trim($file))));
					$file_list[$i]['is_photo'] = in_array($file_ext, $this->ext_arr);
					$file_list[$i]['filetype'] = $file_ext;
				}
				$file_list[$i]['filename'] = $filename;								//文件名，包含扩展名
				$file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); //文件最后修改时间
				$i++;
			}
			closedir($handle);
		}

		// 文件排序
		usort($file_list, array('Upload', '_cmp_func'));

		$result = array();
		$result['moveup_dir_path'] = $moveup_dir_path;								//相对于根目录的上一级目录
		$result['current_dir_path'] = $current_dir_path;							//相对于根目录的当前目录
		$result['current_url'] = $current_url;										//当前目录的URL
		$result['total_count'] = count($file_list);									//文件数
		$result['file_list'] = $file_list;											//文件列表数组

		header('Content-type: application/json; charset=UTF-8');					//输出JSON字符串
		echo $this->json->encode($result);
	}
	
	/**
	 * 图片排序函数
	 *
	 * @access	private
	 * @param	array	$a	图片1
	 * @param	array	$b	图片2
	 * @return	integer
	 */
	private function _cmp_func($a, $b)
	{
		if ($a['is_dir'] && !$b['is_dir'])
		{
			return -1;
		}
		else if (!$a['is_dir'] && $b['is_dir'])
		{
			return 1;
		}
		else
		{
			if ($this->order == 'size')
			{
				if ($a['filesize'] == $b['filesize']) return 0;

				return ($a['filesize'] < $b['filesize']) ? -1 : 1;
			}
			else if ($this->order == 'type')
			{
				return strcmp($a['filetype'], $b['filetype']);
			}
			else
			{
				return strcmp($a['filename'], $b['filename']);
			}
		}
	}
}

/* End of file upload.php */
/* Location: ./application/controllers/admin/upload.php */