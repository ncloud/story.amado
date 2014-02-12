<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
/**
 * CodeIgniter Notification library
 *
 * Easily set and display notifications or message stacks in an application
 *
 * @location	application/libraries
 * @updated		06/01/2011
 * @package		CodeIgniter
 * @subpackage	Notify
 * @version		1.0
 * @author		Mike Saville <http://mikesaville.net>
 * @author		Saville Resources <http://savilleresources.com>
 * @copyright	2011 Saville Resources
 * @license		Apache License v2.0
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class Notify {
	
	/**
	 * @var		array	An array of the different notification types/styles -> see application/config/notifications.php for more
	 */
	private $_notify_array;
	
	/**
	 * @var		string	A string containing the format that the message(s) will be in
	 */
	private $_notify_message;
	
	/**
	 * @var		array	Any notifications that have already been added to the stack
	 */
	private $_current_notifications;
	
	/**
	 * @var		array	The variables within the '$_notify_message' that we'll be replacing (wrapped in {} braces)
	 */
	private $_message_variables;
	
	
	var $CI;
	
	/**
	 * Constructor. Get the notifications config file and set those options here
	 * Tell the logger that it failed to load or not.
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		if ( ! @include(APPPATH.'config/notifications'.EXT)){
			log_message('debug', "Notify Class Failed. Could not find notifications" . EXT . " file in " . APPPATH . "/config/.");
			return FALSE;
		}
		
		$this->CI->load->library('session');
		
		$this->_notify_array = $notify_array;
		$this->_message_variables = $message_variables;
		$this->_notify_message = $notify_message;
		
		log_message('debug', "Notify library successfully initialized.");
	}
	
	/**
	 * Set a new message for the stack
	 * @param	string		Matches the array keys found in the $_notify_array -> it's the type of message you want to display
	 * @param	string		The message you want to display to the user
	 */
	public function set_message( $notify_type, $message )
	{
		$current_notifications = $this->_current_notifications;
		$current_notifications[$notify_type][] = $message;
		
		$this->_set_notifications($current_notifications);
	}
	
	/**
	 * Display notification message(s) (i.e. 'warning', 'error', 'success', etc)
	 */
	public function output()
	{
		$notifications = $this->CI->session->flashdata('notifications');
		
		if ( empty($notifications) ){
			return;
		}
		
		$notify_message = '';
		
		foreach( $notifications as $key => $messages ){
			foreach( $messages as $message ){
				$notify_message_parts = $this->_notify_message;
				foreach( $this->_message_variables as $name => $wrap_with ){
					if ( ! is_array($wrap_with) ){
						//If no 'wrap_with' information was provided, just set an essentially empty array that was two places filled
						$wrap_with = array('', '');
					}
					if ( isset($this->_notify_array[$key][$name]) ){
						$notify_message_parts = str_replace('{' . $name . '}', $wrap_with[0] . $this->_notify_array[$key][$name] . $wrap_with[1], $notify_message_parts) . "\n";
					}else{
						$notify_message_parts = str_replace('{' . $name . '}', $wrap_with[0] . ${$name} . $wrap_with[1], $notify_message_parts) . "\n";
					}
				}
				$notify_message .= $notify_message_parts;
			}
		}
		
		return $notify_message;
	}
	
	/**
	 * This internal method simply sets the notifications added to the session flashdata 'notifications' array. 
	 * Necessary because flashdata arrays don't like having multiple things added at different times.
	 * 
	 * @param	array	The class variable $_current_notifications will be reset to whatever is passed here.
	 */
	private function _set_notifications( $notifications )
	{
		$this->_current_notifications = $notifications;
		$this->CI->session->set_flashdata('notifications', $this->_current_notifications);
	}
}

/* End of ./application/libraries/Notify.php */
/* Mike Saville, Saville Resources */