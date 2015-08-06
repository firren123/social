<?php

/**
 * Class FastDFSHelper
 * 上传图片类
 */
namespace common\helpers;

use Yii;
class FastDFSHelper{
	protected $server;
	protected $storage;
	protected $config;
	
	/**
	 * 构造函数
	 */
	public function __construct(){
		$this->storage = fastdfs_tracker_query_storage_store();
		$this->server = fastdfs_connect_server($this->storage['ip_addr'], $this->storage['port']);
		if(! $this->server){
			echo "<pre>";
			echo fastdfs_get_last_error_no();
			echo fastdfs_get_last_error_info();
			//echo ('连接fastdfs服务失败', fastdfs_get_last_error_no(), "error info: " . fastdfs_get_last_error_info());
			exit(1);
		}
		$this->storage['sock'] = $this->server['sock'];
	}
	
	/**
	 * 通过文件路径上传文件
	 * @param string $filename
	 */
	public function fdfs_upload_by_filename($filename){
		$file_info = fastdfs_storage_upload_by_filename($filename);
		if($file_info){
			return $file_info;
		}
		return false;
	}
	
	/**
	 * 上传文件
	 * @param string $input_name
	 */
	public function fdfs_upload($input_name){
		$file_tmp = $_FILES[$input_name]['tmp_name'];
		$real_name = $_FILES[$input_name]['name'];
		$filename = dirname($file_tmp) . "/" . $real_name;
		@rename($file_tmp, $filename);
		return $this->fdfs_upload_by_filename($filename);
	}
	
	/**
	 * 下载文件
	 * @param string $group_name
	 * @param string $file_id
	 */
	public function fdfs_down($group_name, $file_id){
		$file_content = fastdfs_storage_download_file_to_buff($group_name, $file_id);
		return $file_content;
	}
	
	/**
	 * 删除文件
	 * @param $group_name
	 * @param $file_id
	 */
	public function fdfs_del($group_name, $file_id){
		return fastdfs_storage_delete_file($group_name, $file_id);
	}
	
	/**
	 * 上传水印文件
	 * @param string $input_name
	 */
//	public function fdfs_upload_water($input_name){
//		import("ORG.Util.Image");
//		$Image = new Image();
//		$base_dir = $GLOBALS['pic_base_dir'];
//		$file_tmp = $_FILES[$input_name]['tmp_name'];
//		$real_name = $_FILES[$input_name]['name'];
//		$filename = dirname($file_tmp) . "/" . $real_name;
//		$waterfilename = dirname($file_tmp) . "/water_" . $real_name;
//		@rename($file_tmp, $filename);
//		$Image->water($filename,$GLOBALS['waterpic'],$waterfilename,90);
//		return $this->fdfs_upload_by_filename($waterfilename);
//	}
	
	public function upload(){
		$fds = new FastDFSHelper();
		$data=$fds->fdfs_upload('Filedata');
		echo json_encode($data);
	}
}
