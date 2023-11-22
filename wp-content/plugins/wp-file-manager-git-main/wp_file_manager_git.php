<?php 
/*
  Plugin Name: WP File Manager Git
  Plugin URI: https://filemanagerpro.io/product/file-manager-git/
  Description: Github integration addon for WP File Manager
  Author: mndpsingh287
  Version: 1.1.1
  Author URI: https://profiles.wordpress.org/mndpsingh287
  License: GPLv2
*/
if(!class_exists('wp_file_manager_git')) {
	
	class wp_file_manager_git {
		var $gitver = '1.1.1';
		public function __construct() {
			add_action( 'init', array(&$this,'check_fm_updates'));
			add_action('wp_ajax_mk_file_folder_manager_pull_git_request', 
			array(&$this, 'mk_file_folder_manager_pull_git_request_action_callback'));
			add_action('wp_ajax_mk_file_folder_manager_check_git_changes', 
			array(&$this, 'mk_file_folder_manager_check_git_changes_action_callback'));
			add_action('wp_ajax_mk_file_folder_manager_push_git_changes', 
			array(&$this, 'mk_file_folder_manager_push_git_change_action_callback'));
			add_action('wp_ajax_mk_file_folder_manager_push_full_git_changes', 
			array(&$this, 'mk_file_folder_manager_push_full_git_changes_action_callback'));			
			add_action('admin_menu', array(&$this, 'wp_file_manager_git_menu_page'));
			
		}
		 /* Adding Submenu */
		 public function wp_file_manager_git_menu_page() {
			add_submenu_page( 'wp_file_manager', __( 'Github', 'wp-file-manager-git' ), __( 'Github', 'wp-file-manager-git' ), 'manage_options', 'wp_file_manager_github', array(&$this, 'wp_file_manager_github'));
		 }
		public function mk_file_folder_manager_pull_git_request_action_callback() {
			$settings = get_option('wp_file_manager_pro_git');
			$username = $settings['ELFINDER_GIT_USERNAME'];
			$password = $settings['ELFINDER_GIT_PASSWORD'];
			$gitreponame = $settings['ELFINDER_GIT_ACCESS_URL']; 
			if(!empty($settings['ELFINDER_GIT_ACCESS_DIRECTORY']) && !empty($settings['ELFINDER_GIT_ACCESS_URL'])) {
				//running git command
				$dir = $settings['ELFINDER_GIT_ACCESS_DIRECTORY']; // path
				chdir( $dir );
				//$pull = shell_exec('git clone '.$settings['ELFINDER_GIT_ACCESS_URL'].''); //fix - Private Repo
				$pull = exec("git clone https://".$username.":".$password."@github.com/".$username."/".$gitreponame." ".$gitreponame."", $result);
				echo 'Repository '.$gitreponame.' cloned successfully on destination '.$settings['ELFINDER_GIT_ACCESS_DIRECTORY'].'';
			} else {
				echo 'Error! Please save settings first.';
			}
			 //ob_flush();
             //flush();
			die;
		}
		public function mk_file_folder_manager_check_git_changes_action_callback() {
			$settings = get_option('wp_file_manager_pro_git');
			if(!empty($settings['ELFINDER_GIT_ACCESS_DIRECTORY']) && !empty($settings['ELFINDER_GIT_ACCESS_URL'])) {
				//running git command
				$dir = $settings['ELFINDER_GIT_MASTER_ACCESS_DIRECTORY']; // path
				chdir( $dir );
				echo $pull = shell_exec('git status');
			} else {
				echo 'Error! Please save settings first.';
			}
			die;
		}
		public function mk_file_folder_manager_push_git_change_action_callback() {
			$settings = get_option('wp_file_manager_pro_git');
			if(!empty($settings['ELFINDER_GIT_ACCESS_DIRECTORY']) && !empty($settings['ELFINDER_GIT_ACCESS_URL'])) {
				//running git command
				$email = $settings['ELFINDER_GIT_EMAIL'];
				$username = $settings['ELFINDER_GIT_USERNAME'];
				$password = $settings['ELFINDER_GIT_PASSWORD'];
				$message = $_POST['message'];
				$gitreponame = $settings['ELFINDER_GIT_ACCESS_URL'];
				$git_url = 'github.com/'.$username.'/'.$gitreponame;
				$dir = $settings['ELFINDER_GIT_MASTER_ACCESS_DIRECTORY']; // path 
				chdir( $dir );
				echo shell_exec('git add .');
				//echo shell_exec('git config --global user.email "'.$email.'"');
				//echo shell_exec('git config --global user.name "'.$username.'"');
				$user_cpanel = shell_exec("whoami");
                $check = shell_exec('git -c user.name="'.$user_cpanel.'" -c user.email="'.$email.'" commit -m "'.$message.'" ');
                if ($check == null) {
					$check =  shell_exec('git commit -m "'.$message.'"');
                }
				echo shell_exec('git push https://'.$username.':'.$password.'@'.$git_url.' --all');
				echo "Commit Successfull !! ";
				//echo 'Here';
			} else {
				echo 'Error! Please save settings first.';
			}
			die;
		}
		// Push Full Git Changes
		public function mk_file_folder_manager_push_full_git_changes_action_callback() {
			$settings = get_option('wp_file_manager_pro_git');
			if(!empty($settings['ELFINDER_GIT_ACCESS_DIRECTORY']) && !empty($settings['ELFINDER_GIT_ACCESS_URL'])) {
				//running git command
				$email = $settings['ELFINDER_GIT_EMAIL'];
				$username = $settings['ELFINDER_GIT_USERNAME'];
				$password = $settings['ELFINDER_GIT_PASSWORD'];
				$message = $_POST['message'];
				//$git_url = str_replace(array('https://','http://'),'',$settings['ELFINDER_GIT_ACCESS_URL']);
				$gitreponame = $settings['ELFINDER_GIT_ACCESS_URL'];
				//echo $gitreponame;die;
				$git_url = 'https://github.com/'.$username.'/'.$gitreponame.'.git';
				$dir = $settings['ELFINDER_GIT_ACCESS_DIRECTORY']; // path
				chdir( $dir ); 
				//changed
				$output1 = 	 shell_exec('git init');
			//	$output2 =  shell_exec('git status');
				$output3 =  shell_exec('git add .');	
				$output4 = 	 shell_exec('git config --global user.email "'.$email.'"');
				$output5 = 	shell_exec('git config --global user.name "'.$username.'"');
				$output6 =  shell_exec('git commit -m "'.$message.'"');
			
				$output7 = 	shell_exec('git branch -M main');
				$output8 =   shell_exec('git remote add origin "'.$git_url.'"');	
				$output9 = 	shell_exec('git remote -v');
				$output0 =   shell_exec('git push -u origin main');

				
				/*echo shell_exec('git config --global user.email "'.$email.'"');
				echo shell_exec('git commit -m "'.$message.'"');
				echo shell_exec('git push https://'.$username.':'.$password.'@'.$git_url.' --all');*/


				// $user_cpanel = shell_exec("whoami");
                // $check = shell_exec('git -c user.name="'.$user_cpanel.'" -c user.email="'.$email.'" commit -m "'.$message.'" ');
                // if ($check == null) {
				// 	$check =  shell_exec('git commit -m "'.$message.'"');
                // }
				// echo 'git push https://'.$username.':'.$password.'@'.$git_url.' --all';die;
				// echo shell_exec('git push https://'.$username.':'.$password.'@'.$git_url.' --all');
				echo "Commit Successfull !! ";

			} else {
				echo 'Error! Please save settings first.';
			}
			die;
		}
		
		// verify
		 public function is_verify() {
			$opt = get_option('wp_file_manager_pro_git_verify');
			if(!empty($opt['ispro']) && !empty($opt['serialkey'])){
				return true;
			} else {
				return false;
			}
		 }
		 /* Error Msg */	
	    public function error($msg){
		  _e('<div id="setting-error-settings_updated" class="error settings-error notice"><p><strong>'.$msg.'</strong></p></div>','wp-file-manager-git');
	    }
	    /* Success Msg */
	    public function success($msg){
		  _e('<div id="setting-error-settings_updated" class="updated settings-error notice"><p><strong>'.$msg.'</strong></p></div>','wp-file-manager-git'); 
	    }
		 /* Redirect */
		 public function redirect($loc) {
			 $script = '<script>';
			 $script .= 'window.location.href="'.$loc.'"';
			 $script .= '</script>';
			 echo $script;
		 }
		 /* Verify */
		 public function verify($oid, $lk, $red) {
			$orderID = $oid;
			$licenceKey = $lk; 
			$wp_file_manager_pro = array();	
			if(function_exists('curl_version')) {
				$API = 'https://filemanagerpro.io/pluginsapi.php';	
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $API);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // save to returning 1
				curl_setopt($curl, CURLOPT_POSTFIELDS, "orderid=".$orderID."&licencekey=".$licenceKey."&nonce=ungt56ghsdewj87h");
				$result = curl_exec ($curl); 
				$data = json_decode($result,true);
				curl_close ($curl);
				if (!$data) {
					$API = "https://filemanagerpro.io/pluginsapi.php?orderid=".$orderID."&licencekey=".$licenceKey."&nonce=ungt56ghsdewj87h";
                    $result = file_get_contents($API);
                    $data = json_decode($result, true);
                }
			} else {
			   $API = "https://filemanagerpro.io/pluginsapi.php?orderid=".$orderID."&licencekey=".$licenceKey."&nonce=ungt56ghsdewj87h";
               $result = file_get_contents($API);
               $data = json_decode($result,true);	
			}
			if($data['error'] == '0') {
			   $this->success('Congratulations. Your Plugin is verified successfully.');
			   $wp_file_manager_pro['ispro'] = 'yes';	
			   $wp_file_manager_pro['serialkey'] = $data['serialkey'];
			   $wp_file_manager_pro['orderid'] = $data['orderid'];
			   if(is_multisite()) { // Multisite Fix
				   $sites = get_sites();
				    foreach( $sites as $site ) {
					switch_to_blog( $site->blog_id );
					delete_option('wp_file_manager_pro_git_verify');
			        $updated = update_option('wp_file_manager_pro_git_verify', $wp_file_manager_pro );
					restore_current_blog();
					}                  
			   } else {
				 delete_option('wp_file_manager_pro_git_verify');  
			     $updated = update_option('wp_file_manager_pro_git_verify', $wp_file_manager_pro );
			   }
			   if($updated):
			    $this->redirect('admin.php?page='.$red);
			   endif;
			}
			else {
				$this->error($data['error']);
			}
	   }
	   /* check for updates */
		 public function check_fm_updates() {
			$path = $_SERVER['REQUEST_URI'];
			$file = basename($path, ".php");
			$file_name = explode('?', $file);
		    $orderDetails = get_option('wp_file_manager_pro_git_verify');
		     $dir = plugin_dir_path( __FILE__ );
	        include($dir.'update.php');
			$plugin_current_version = $this->gitver;
			$plugin_remote_path = 'https://webdesi9.com/plugin_server/wp_file_manager_git/update.php';
			$plugin_slug = plugin_basename( __FILE__ );
			$license_order = isset($orderDetails['orderid']) ? intval($orderDetails['orderid']) : '' ;
			$license_key = isset($orderDetails['serialkey']) ? sanitize_text_field($orderDetails['serialkey']) : '';	
			if(($file_name[0] == 'plugins.php') || ($file_name[0] == 'plugins')) {
		      new FMGIT_AutoUpdate( $plugin_current_version, $plugin_remote_path, $plugin_slug, $license_order, $license_key );
			} 
		 }
		
	  /* Git Hub */
		public function wp_file_manager_github(){
			if(is_admin() && current_user_can('manage_options')) {
			    if($this->is_verify()) {		 
				 include('inc/git.php'); 
				} else {
				 include('inc/verify.php');	
				}	
			}
		}	
		
	}

/* Required to hook with WP File Manager */
add_action('load_filemanager_extensions', 'wp_file_manager_git_load');
function wp_file_manager_git_load() {
	new wp_file_manager_git;
}
	
}