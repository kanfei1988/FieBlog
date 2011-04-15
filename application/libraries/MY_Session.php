<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Session extends CI_Session
{
	public function __construct($params = array())
	{
		parent::__construct($params);
	}

	public function set_msg($condition, $success_msg, $error_msg)
	{
		if ($condition)
		{
			$this->set_flashdata('success', $success_msg);
		}
		else
		{
			$this->set_flashdata('error', $error_msg);
		}
	}
}
// END MY_Session Class

/* End of file MY_Session.php */
/* Location: ./application/libraries/MY_Session.php */