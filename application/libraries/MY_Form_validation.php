<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
	function __construct($rules = array())
	{
		parent::__construct($rules);
	}

	function unique($value, $params)
	{
		$CI =& get_instance();
		$CI->load->database();
		$CI->form_validation->set_message('unique', '%s 已经存在.');

		$parts = explode('.', $params);

		$table = $parts[0];
		$column = $parts[1];
		$id_column = (isset($parts[2])) ? $parts[2] : '';
		$id = (isset($parts[3])) ? $parts[3] : (($id_column) ? $id_column : '');

		$CI->db->where($column, $value);

		if ($id_column && $id && isset($_POST[$id]) && $_POST[$id])
		{
			$CI->db->where("$id_column <>", $_POST[$id]);
		}

		return ($CI->db->count_all_results($table) > 0) ? FALSE : TRUE;
	}
}
// END MY_Form_validation Class

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */