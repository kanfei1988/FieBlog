<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Create a Directory Map
 *
 * Reads the specified directory and builds an array
 * representation of it.  Sub-folders contained with the
 * directory will be mapped as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	int		depth of directories to traverse (0 = fully recursive, 1 = current dir, etc)
 * @return	array
 */
if ( ! function_exists('get_file_tree'))
{
	function get_file_tree($source_dir, $directory_depth = 0, $hidden = FALSE, $is_root = TRUE)
	{
		static $str;
		static $root;

		if ($fp = @opendir($source_dir))
		{
			$filedata	= array();
			$new_depth	= $directory_depth - 1;
			$source_dir	= rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

			if ($is_root) $root = $source_dir;

			while (FALSE !== ($file = readdir($fp)))
			{
				// Remove '.', '..', and hidden files [optional]
				if ( ! trim($file, '.') OR ($hidden == FALSE && $file[0] == '.'))
				{
					continue;
				}

				if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir.$file))
				{
					$str .= '<li><span class="folder">'.$file.'</span><ul>';

					get_file_tree($source_dir.$file.DIRECTORY_SEPARATOR, $new_depth, $hidden, FALSE);

					$str .= '</ul></li>';
				}
				else
				{
					$info = pathinfo($source_dir.$file);
					if (in_array(strtolower($info['extension']), array('php', 'js', 'css')))
					{
						$str .= '<li><span class="file">'.anchor(site_url('admin/themes/manage/'.url_base64_encode(substr($source_dir.$file, strlen($root)))), $file).'</span>';
					}
				}
			}

			closedir($fp);
			return $str;
		}

		return FALSE;
	}
}

/* End of file MY_directory_helper.php */
/* Location: ./application/helpers/MY_directory_helper.php */