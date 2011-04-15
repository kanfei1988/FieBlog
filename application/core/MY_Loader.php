<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{
	function __construct()
	{
		parent::__construct();
	}

	function set_theme($theme = 'default')
	{
		$this->_ci_view_path = FCPATH. THEMEPATH . DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR;
	}
}

/* End of file MY_Loader.php */
/* Location: ./application/core/MY_Loader.php */