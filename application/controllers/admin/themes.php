<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Themes extends MY_Admin_Controller
{
	private $_theme_path;

	private $_current_theme;

	function __construct()
	{
		parent::__construct();

		is_administrator();

		$this->load->helper('security');

		$this->_theme_path = THEMEPATH;
		$this->_current_theme = settings('current_theme');

		$this->_data['parentPage'] = 'dashboard';
		$this->_data['title'] = '编辑主题';
	}

	/**
	 * Themes Index
	 *
	 * @access	public
	 * @return	void
	 */
	function index()
	{
		redirect('admin/themes/manage');
	}

	/**
	 * 主题修改
	 *
	 * @access	public
	 * @param	string	$current_file_code	当前修改文件的文件名的Base64编码
	 * @return	void
	 */
	function manage($current_file_code = '')
	{
		$dir = FCPATH . $this->_theme_path . DIRECTORY_SEPARATOR . $this->_current_theme;

		$this->form_validation->set_rules('current_file', '编辑文件', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->helper('directory');
			$this->_data['file_tree'] = get_file_tree($dir);
			$this->_data['current_file'] = '';
			$this->_data['current_content'] = '';
			$this->_data['current_extension'] = 'html';

			if ($current_file_code)
			{
				$current_file = url_base64_decode($current_file_code);
				$this->_data['current_extension'] = end(explode('.', $current_file));
				if ($this->_data['current_extension'] == 'php')
					$this->_data['current_extension'] = 'html';


				$current_file = $dir . DIRECTORY_SEPARATOR . $current_file;
				$this->_data['current_file'] = $current_file;

				$this->load->helper('file');
				$current_content = htmlspecialchars(read_file($current_file));

				$this->_data['current_content'] = $current_content;
			}

			// 加载jQuery-treeview和kindeditor插件
			$this->assets
				->add_a_css('jquery.treeview.css', 'assets/js/treeview')
				->add_a_js('treeview/jquery.treeview.js')
				->add_a_js('edit_area/edit_area_full.js');

			$this->load->view('theme_edit', $this->_data);
		}
		else
		{
			$current_file = $this->input->post('current_file', TRUE);

			//$current_content = htmlspecialchars_decode($_POST['current_content']);
			$current_content = $_POST['current_content'];

			switch(settings('current_theme_format'))
			{
				case 'windows': $current_content = str_replace(PHP_EOL, "\r\n", $current_content); break;
				case 'unix': $current_content = str_replace(PHP_EOL, "\n", $current_content); break;
				case 'mac': $current_content = str_replace(PHP_EOL, "\r", $current_content); break;
			}

			if (file_exists($current_file) && is_writeable($current_file))
			{
				$this->load->helper('file');

				$write = write_file($current_file, $current_content);

				$this->session->set_msg($write, '文件'.basename($current_file).'已经保存', '文件'.basename($current_file).'无法被写入');

				go_back();
			}
			else
			{
				show_error('编辑的文件不存在');
			}
		}
	}
}

/* End of file themes.php */
/* Location: ./application/controllers/admin/themes.php */