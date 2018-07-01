<?php
class App_Classes_JQueryUploadHandler {
	private $options;
	protected $folderPath;
	protected $thumbFolderPath;
	
	function __construct($formNum, $documentId, $options = null) {
		
		// require parameters
		if(null==$formNum || null==$documentId) return false;
		
		// build delete path with form and document ids uncluded
		$delete_script_url = '/Jfile-upload/upload/requestType/delete/form_number/'.$formNum.'/document/'.$documentId;
		
		// build the path to file storage for this form
		/*
		 * Check to see if the isn't an original folder path, 
		 * if not then use new folder structure.  If there is use 
		 * the old structure. 
		 */
		$this->folderPath = APPLICATION_PATH . '/user_images/uploaded_pdf/PDF_' . str_pad ( $formNum, 3, '0', STR_PAD_LEFT ) . '_' . $documentId . '/';
		if (!is_dir ( $this->folderPath )) {
		    $this->folderPath = APPLICATION_PATH . '/user_images/uploaded_pdf_new/'.substr(MD5($documentId), 0, 1).'/'.substr(MD5($documentId), 1, 1).'/'.substr(MD5($documentId), 2, 1).'/PDF_' . str_pad ( $formNum, 3, '0', STR_PAD_LEFT ) . '_' . $documentId . '/';
		} 
		
		// verify/build folder for pdf storage
		if (! is_dir ( $this->folderPath )) {
		    mkdir ( APPLICATION_PATH . '/user_images/uploaded_pdf_new/'.substr(MD5($documentId), 0, 1).'/', 0777 );
		    mkdir ( APPLICATION_PATH . '/user_images/uploaded_pdf_new/'.substr(MD5($documentId), 0, 1).'/'.substr(MD5($documentId), 1, 1).'/', 0777 );
		    mkdir ( APPLICATION_PATH . '/user_images/uploaded_pdf_new/'.substr(MD5($documentId), 0, 1).'/'.substr(MD5($documentId), 1, 1).'/'.substr(MD5($documentId), 2, 1).'/', 0777 );
			$oldumask = umask ( 0 );
			// Michael D changed this trying to get it to work changed to 1777
			mkdir ( $this->folderPath, 0777 ); // or even 01777 so you get the sticky bit set
			chmod ( $this->folderPath, 0777 );
			umask ( $oldumask );
		}
		
// 		// verify/build folder for thumbnail storage
// 		$this->thumbFolderPath = $this->folderPath . '/thumbs/';
// 		if (! is_dir ( $this->thumbFolderPath )) {
// 			$oldumask = umask ( 0 );
// 			mkdir ( $this->thumbFolderPath, 0777 ); // or even 01777 so you get the sticky bit set
// 			chmod ( $this->thumbFolderPath, 0777 );
// 			umask ( $oldumask );
// 		}
		
		// build the options array for the uploader
		$this->options = array (
				'delete_script_url' => $delete_script_url, //$_SERVER['PHP_SELF'],
				'upload_dir' => $this->folderPath, 
				
				// http://localhost:10094/file-list/printpdf/document/2/form_number/017/filename/ELA%201%202011%20-%202.pdf
				// http://localhost:10094/Jfile-upload/upload/filename/ELA%201%202011%20-%202.pdf
// 				'upload_url' => '/Jfile-upload/upload/requestType/get/form_number/'.$formNum.'/document/'.$documentId.'/file/', // path to file for grabbing 
				'upload_url' => '/Jfile-upload/printpdf/form_number/'.$formNum.'/document/'.$documentId.'/file/', // path to file for grabbing 
				'param_name' => 'files', // The php.ini settings upload_max_filesize and post_max_size
				// take precedence over the following max_file_size setting:
				'max_file_size' => null, 
				'min_file_size' => 1, 
				'accept_file_types' => '/.pdf/i', 
				'max_number_of_files' => null, 
				'discard_aborted_uploads' => true, 
				'image_versions' => array (
					// Uncomment the following version to restrict the size of
					// uploaded images. You can also add additional versions with
					// their own upload directories:
					/*
					'large' => array(
							'upload_dir' => dirname(__FILE__).'/files/',
							'upload_url' => dirname($_SERVER['PHP_SELF']).'/files/',
							'max_width' => 1920,
							'max_height' => 1200
							),
							*/
					'thumbnail' => array (
						//                     'upload_dir' => dirname(__FILE__).'/thumbnails/',
						//                     'upload_url' => dirname($_SERVER['PHP_SELF']).'/thumbnails/',
						'upload_dir' => $this->thumbFolderPath, 'upload_url' => $this->thumbFolderPath, 'max_width' => 80, 'max_height' => 80 
					) 
				) );
		if ($options) {
			$this->options = array_replace_recursive ( $this->options, $options );
		}
	}
	
	private function get_file_object($file_name) {
		$file_path = $this->options ['upload_dir'] . $file_name;
		if (is_file ( $file_path ) && $file_name [0] !== '.') {
			$file = new stdClass ();
			$file->name = $file_name;
			$file->size = filesize ( $file_path );
			$file->url = $this->options ['upload_url'] . rawurlencode ( $file->name );
			
			foreach ( $this->options ['image_versions'] as $version => $options ) {
				if (is_file ( $options ['upload_dir'] . $file_name )) {
					$file->{$version . '_url'} = $options ['upload_url'] . rawurlencode ( $file->name );
				}
			}
			$file->delete_url = $this->options ['delete_script_url'] . '/file/' . rawurlencode ( $file->name );
			$file->delete_type = 'DELETE';
			return $file;
		}
		return null;
	}
	
	private function get_file_objects() {
		return array_values ( array_filter ( array_map ( array ($this, 'get_file_object' ), scandir ( $this->options ['upload_dir'] ) ) ) );
	}
	
	private function create_scaled_image($file_name, $options) {
		$file_path = $this->options ['upload_dir'] . $file_name;
		$new_file_path = $options ['upload_dir'] . $file_name;
		list ( $img_width, $img_height ) = @getimagesize ( $file_path );
		if (! $img_width || ! $img_height) {
			return false;
		}
		$scale = min ( $options ['max_width'] / $img_width, $options ['max_height'] / $img_height );
		if ($scale > 1) {
			$scale = 1;
		}
		$new_width = $img_width * $scale;
		$new_height = $img_height * $scale;
		$new_img = @imagecreatetruecolor ( $new_width, $new_height );
		switch (strtolower ( substr ( strrchr ( $file_name, '.' ), 1 ) )) {
			case 'jpg' :
			case 'jpeg' :
				$src_img = @imagecreatefromjpeg ( $file_path );
				$write_image = 'imagejpeg';
				break;
			case 'gif' :
				@imagecolortransparent ( $new_img, @imagecolorallocate ( $new_img, 0, 0, 0 ) );
				$src_img = @imagecreatefromgif ( $file_path );
				$write_image = 'imagegif';
				break;
			case 'png' :
				@imagecolortransparent ( $new_img, @imagecolorallocate ( $new_img, 0, 0, 0 ) );
				@imagealphablending ( $new_img, false );
				@imagesavealpha ( $new_img, true );
				$src_img = @imagecreatefrompng ( $file_path );
				$write_image = 'imagepng';
				break;
			default :
				$src_img = $image_method = null;
		}
		$success = $src_img && @imagecopyresampled ( $new_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height ) && $write_image ( $new_img, $new_file_path );
		// Free up memory (imagedestroy does not delete files):
		@imagedestroy ( $src_img );
		@imagedestroy ( $new_img );
		return $success;
	}
	
	private function has_error($uploaded_file, $file, $error) {
		if ($error) {
			return $error;
		}
		if (! preg_match ( $this->options ['accept_file_types'], $file->name )) {
			return 'acceptFileTypes';
		}
		if ($uploaded_file && is_uploaded_file ( $uploaded_file )) {
			$file_size = filesize ( $uploaded_file );
		} else {
			$file_size = $_SERVER ['CONTENT_LENGTH'];
		}
		if ($this->options ['max_file_size'] && ($file_size > $this->options ['max_file_size'] || $file->size > $this->options ['max_file_size'])) {
			return 'maxFileSize';
		}
		if ($this->options ['min_file_size'] && $file_size < $this->options ['min_file_size']) {
			return 'minFileSize';
		}
		if (is_int ( $this->options ['max_number_of_files'] ) && (count ( $this->get_file_objects () ) >= $this->options ['max_number_of_files'])) {
			return 'maxNumberOfFiles';
		}
		return $error;
	}
	
	private function trim_file_name($name, $type) {
		// Remove path information and dots around the filename, to prevent uploading
		// into different directories or replacing hidden system files.
		// Also remove control characters and spaces (\x00..\x20) around the filename:
		$file_name = trim ( basename ( stripslashes ( $name ) ), ".\x00..\x20" );
		// Add missing file extension for known image types:
		if (strpos ( $file_name, '.' ) === false && preg_match ( '/^image\/(gif|jpe?g|png)/', $type, $matches )) {
			$file_name .= '.' . $matches [1];
		}
		return $file_name;
	}
	
	private function handle_file_upload($uploaded_file, $name, $size, $type, $error) {
		$file = new stdClass ();
		$file->name = $this->trim_file_name ( $name, $type );
		$file->size = intval ( $size );
		$file->type = $type;
		$error = $this->has_error ( $uploaded_file, $file, $error );
		if (! $error && $file->name) {
			$file_path = $this->options ['upload_dir'] . $file->name;
			$append_file = ! $this->options ['discard_aborted_uploads'] && is_file ( $file_path ) && $file->size > filesize ( $file_path );
			clearstatcache ();
			if ($uploaded_file && is_uploaded_file ( $uploaded_file )) {
				// multipart/formdata uploads (POST method uploads)
				if ($append_file) {
					file_put_contents ( $file_path, fopen ( $uploaded_file, 'r' ), FILE_APPEND );
				} else {
					move_uploaded_file ( $uploaded_file, $file_path );
				}
			} else {
				// Non-multipart uploads (PUT method support)
				file_put_contents ( $file_path, fopen ( 'php://input', 'r' ), $append_file ? FILE_APPEND : 0 );
			}
			$file_size = filesize ( $file_path );
			if ($file_size === $file->size) {
				$file->url = $this->options ['upload_url'] . rawurlencode ( $file->name );
				foreach ( $this->options ['image_versions'] as $version => $options ) {
					if ($this->create_scaled_image ( $file->name, $options )) {
						$file->{$version . '_url'} = $options ['upload_url'] . rawurlencode ( $file->name );
					}
				}
			} else if ($this->options ['discard_aborted_uploads']) {
				unlink ( $file_path );
				$file->error = 'abort';
			}
			$file->size = $file_size;
			$file->delete_url = $this->options ['delete_script_url'] . '/file/' . rawurlencode ( $file->name );
			$file->delete_type = 'DELETE';
		} else {
			$file->error = $error;
		}
		return $file;
	}
	
	public function get($file_name=null) {
// 		$file_name = isset ( $_REQUEST ['file'] ) ? basename ( stripslashes ( $_REQUEST ['file'] ) ) : null;
		if ($file_name) {
// 			echo $file_name;die();
			$info = $this->get_file_object ( $file_name );
		} else {
			$info = $this->get_file_objects ();
		}
		header ( 'Content-type: application/json' );
		echo json_encode ( $info );
	}
	
	public function post() {
		$upload = isset ( $_FILES [$this->options ['param_name']] ) ? $_FILES [$this->options ['param_name']] : null;
		$info = array ();
		if ($upload && is_array ( $upload ['tmp_name'] )) {
			foreach ( $upload ['tmp_name'] as $index => $value ) {
				$info [] = $this->handle_file_upload ( $upload ['tmp_name'] [$index], isset ( $_SERVER ['HTTP_X_FILE_NAME'] ) ? $_SERVER ['HTTP_X_FILE_NAME'] : $upload ['name'] [$index], isset ( $_SERVER ['HTTP_X_FILE_SIZE'] ) ? $_SERVER ['HTTP_X_FILE_SIZE'] : $upload ['size'] [$index], isset ( $_SERVER ['HTTP_X_FILE_TYPE'] ) ? $_SERVER ['HTTP_X_FILE_TYPE'] : $upload ['type'] [$index], $upload ['error'] [$index] );
			}
		} elseif ($upload) {
			$info [] = $this->handle_file_upload ( $upload ['tmp_name'], isset ( $_SERVER ['HTTP_X_FILE_NAME'] ) ? $_SERVER ['HTTP_X_FILE_NAME'] : $upload ['name'], isset ( $_SERVER ['HTTP_X_FILE_SIZE'] ) ? $_SERVER ['HTTP_X_FILE_SIZE'] : $upload ['size'], isset ( $_SERVER ['HTTP_X_FILE_TYPE'] ) ? $_SERVER ['HTTP_X_FILE_TYPE'] : $upload ['type'], $upload ['error'] );
		}
		header ( 'Vary: Accept' );
		if (isset ( $_SERVER ['HTTP_ACCEPT'] ) && (strpos ( $_SERVER ['HTTP_ACCEPT'], 'application/json' ) !== false)) {
			header ( 'Content-type: application/json' );
		} else {
			header ( 'Content-type: text/plain' );
		}
		echo json_encode ( $info );
	}
	
	public function delete($file_name) {
		//         $file_name = isset($_REQUEST['file']) ?
		//             basename(stripslashes($_REQUEST['file'])) : null;
		

		$file_path = $this->options ['upload_dir'] . $file_name;
		$success = is_file ( $file_path ) && $file_name [0] !== '.' && unlink ( $file_path );
		if ($success) {
			foreach ( $this->options ['image_versions'] as $version => $options ) {
				$file = $options ['upload_dir'] . $file_name;
				if (is_file ( $file )) {
					unlink ( $file );
				}
			}
		}
		header ( 'Content-type: application/json' );
		echo json_encode ( $success );
	}
}