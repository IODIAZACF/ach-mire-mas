<?php
require_once "server-side-helpers/file_explorer_fs_helper.php";

$path = $_REQUEST['edson'];
//$path = '/opt/lampp/htdocs/protecseguros/documentos/';

$options = array(
	"base_url" => "https://yoursite.com/yourapp/files/",
	"protect_depth" => 1,  // Protects base_dir + additional directory depth.
	"recycle_to" => "Recycle Bin",
	"temp_dir" => "/tmp",
	"dot_folders" => false,  // Set to true to allow things like:  .git, .svn, .DS_Store
	"allowed_exts" => ".jpg, .jpeg, .png, .gif, .svg, .txt, .xls, .xlsx, .doc, .docx, .ppt, .pptx, .pdf",
	"allow_empty_ext" => true,
	"thumbs_dir" => "/var/www/yourapp/thumbs",
	"thumbs_url" => "https://yoursite.com/yourapp/thumbs/",
	"thumb_create_url" => "https://yoursite.com/yourapp/?action=file_explorer_thumbnail&xsrftoken=qwerasdf",
	"refresh" => true,
	"rename" => true,
	"file_info" => false,
	"load_file" => false,
	"save_file" => false,
	"new_folder" => true,
	"new_file" => ".txt",
	"upload" => true,
	"upload_limit" => 20000000,  // -1 for unlimited or an integer
	"download" => true,
	"copy" => true,
	"move" => true,
	"delete" => true
);

FileExplorerFSHelper::HandleActions("action", "file_explorer_", $path , $options);
?>


