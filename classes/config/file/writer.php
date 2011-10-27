<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Interface for config writers
 *
 * Specifies the methods that a config writer must implement
 *
 * @package Kohana
 * @author  Kohana Team
 * @copyright  (c) 2008-2010 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Config_File_Writer extends Config_File_Reader implements Kohana_Config_Writer
{
	/**
	 * Writes the passed config for $group
	 *
	 * Returns chainable instance on success or throws
	 * Kohana_Config_Exception on failure
	 *
	 * @param string      $group  The config group
	 * @param string      $key    The config key to write to
	 * @param array       $config The configuration to write
	 * @return boolean
	 */
	public function write($group, $key, $config) {
        $content = '<?php defined(\'SYSPATH\') or die(\'No direct script access.\');

		return ';

        $file = Kohana::find_file ($this->_directory, $group, 'php', FALSE);
        $cfg = file_get_contents($file);

        if($config instanceof Kohana_Config_Group)
            $config = $config->as_array();

        //We're writing to a whole config file
        if($key == null)
            $content .= var_export(Arr::merge($config, $cfg), TRUE);
        else {
            if(!is_array($key))
                $cfg[$key] = $config;
            else
                return false;

            $content .= var_export($cfg, true);
        }
        
        file_put_contents($file, $content.';');
        return true;
    }
}
