<?php if (!defined('SPEBLOG')) exit('You can not directly access the file.');

/**
 * SpeBlog 逻辑处理
 * @author 熊二哈
 * @link http://www.xlogs.cn
 */

global $mod;
global $action;
global $mysqli;

$WebConfig = $mysqli->db->executeQuery("SELECT * FROM  `spe_config`", true);
if($WebConfig) {
	$title = "后台管理";
	$sitename = $WebConfig[0]['value'];
	$keywords = $WebConfig[1]['value'];
	$description = $WebConfig[2]['value'];
	$version = $WebConfig[3]['value'];
	$whecomment = $WebConfig[4]['value'];
}

$WebMenu = $mysqli->db->executeQuery("SELECT * FROM  `spe_system` WHERE `name` = 'menu'", true);
if($WebMenu) $tomenu = $WebMenu['box'];

$WebLinks = $mysqli->db->executeQuery("SELECT * FROM  `spe_system` WHERE `name` = 'links'", true);
if($WebLinks) $links = $WebLinks['box'];

$WebCss = $mysqli->db->executeQuery("SELECT * FROM  `spe_system` WHERE `name` = 'css'", true);
if($WebCss) $css = $WebCss['box'];

# ======> Login status
if(isset($_COOKIE['user_check'])) $Admin = $mysqli->db->executeQuery("SELECT * FROM `spe_user` WHERE `user_check` = '{$_COOKIE['user_check']}'", true);


# ======> Processing AJAX requests
if($action == "login") {
	header("content-type:text/plain; charset=utf-8");
	$result = array();
	$username = param_filter("username");
	$password = md5(param_filter("password"));
	if(!is_empty($username) && !is_empty($password)) {
		$login = $mysqli->db->executeQuery("SELECT * FROM `spe_user` WHERE `username` = '{$username}' AND `password` = '{$password}'") > 0 ? true:false;
		if($login) {
			$data = time();
			$ip = getIP();
			$user_check = base64_encode(md5("SPEBLOG" . getRandString(12, 0) . $date));
			setcookie('user_check', $user_check, time() + (60 * 60 * 24 * 30));
			$login = $mysqli->db->executeQuery("UPDATE `spe_user` SET `user_check` = '{$user_check}', `sign_ip` = '{$ip}', `createdate` = $data WHERE `username` = '{$username}' AND `password` = '{$password}'") > 0 ? true:false;
			if($login) {
				$result['code'] = 1;
			}
		} else {
			$result['code'] = 0;
		}
	}
	exit(json_encode($result));
}
if($Admin) {
	if ($action == "logout") {
		header("content-type:text/plain; charset=utf-8");
		$result = array();
		setcookie('user_check', '', time() - 3600);
		$logout = $mysqli->db->executeQuery("UPDATE `spe_user` SET `user_check` = 0 WHERE `user_check` = '{$Admin['user_check']}'") > 0 ? true:false;
		if($logout) {
			$result['code'] = 1;
		} else {
			$result['code'] = 0;
		}
		exit(json_encode($result));
	} elseif ($action == "setSystem") {
		header("content-type:text/plain; charset=utf-8");
		$result = array();
		$sitename = param_filter("sitename");
		$keywords = param_filter("keywords");
		$description = param_filter("description");
		$setSystem = $mysqli->db->executeMultiQuery(
			"UPDATE `spe_config` SET `value` =  '{$sitename}' WHERE `key` = 'sitename';".
			"UPDATE `spe_config` SET `value` =  '{$keywords}' WHERE `key` = 'keywords';".
			"UPDATE `spe_config` SET `value` =  '{$description}' WHERE `key` = 'description';"
			);
		if($setSystem) {
			$result['code'] = 1;
		} else {
			$result['code'] = 0;
		}
		exit(json_encode($result));
	} elseif ($action == "setMenu") {
		header("content-type:text/plain; charset=utf-8");
		$result = array();
		$menuhtm = $_POST['menuhtm'];
		$setMenu = $mysqli->db->executeQuery("UPDATE `spe_system` SET `box` = '{$menuhtm}' WHERE `name` = 'menu'") > 0 ? true:false;
		if($setMenu) {
			$result['code'] = 1;
		} else {
			$result['code'] = 0;
		}
		exit(json_encode($result));
	} elseif ($action == "setLinks") {
		header("content-type:text/plain; charset=utf-8");
		$result = array();
		$linkshtm = $_POST['linkshtm'];
		$setLinks = $mysqli->db->executeQuery("UPDATE `spe_system` SET `box` = '{$linkshtm}' WHERE `name` = 'links'") > 0 ? true:false;
		if($setLinks) {
			$result['code'] = 1;
		} else {
			$result['code'] = 0;
		}
		exit(json_encode($result));
	} elseif ($action == "setCss") {
		header("content-type:text/plain; charset=utf-8");
		$result = array();
		$css = $_POST['css'];
		$setCss = $mysqli->db->executeQuery("UPDATE `spe_system` SET `box` = '{$css}' WHERE `name` = 'css'") > 0 ? true:false;
		if($setCss) {
			$result['code'] = 1;
		} else {
			$result['code'] = 0;
		}
		exit(json_encode($result));
	} elseif ($action == "setData") {
		header("content-type:text/plain; charset=utf-8");
		$result = array();
		$username = param_filter("username");
		if (!is_empty($username)) {
			$setData = $mysqli->db->executeQuery("UPDATE `spe_user` SET `username` = '{$username}' WHERE `user_check` = '{$Admin['user_check']}'") > 0 ? true:false;
			if($setData) {
				$result['code'] = 1;
			} else {
				$result['code'] = 0;
			}
		}
		exit(json_encode($result));
	} elseif ($action == "setPassWord") {
		header("content-type:text/plain; charset=utf-8");
		$result = array();
		$pass1 = md5(param_filter("pass1"));
		$pass2 = md5(param_filter("pass2"));
		$passCheck = $mysqli->db->executeQuery("SELECT * FROM `spe_user` WHERE `user_check` = '{$Admin['user_check']}' AND `password` = '{$pass1}'") > 0 ? true:false;
		if($passCheck) {
			$setPass = $mysqli->db->executeQuery("UPDATE `spe_user` SET `password` = '{$pass2}' WHERE `user_check` = '{$Admin['user_check']}' AND `password` = '{$pass1}'") > 0 ? true:false;
			if($setPass) {
				$result['code'] = 1;
			} else {
				$result['code'] = 0;
			}
		} else {
			$result['code'] = 2;
		}
		exit(json_encode($result));
	} elseif ($action == "addArticle") {
		header("content-type:text/plain; charset=utf-8");
		$result = array();
		$article_title = htmlspecialchars(param_filter("articles_title"));
		$article_html = param_filter("articles_html");
		if(!is_empty($article_title) && !is_empty($article_html)) {
			$ip = getIP();
			$createdata = time();
			$addarticle = $mysqli->db->executeQuery("INSERT INTO `spe_articles` (`title`, `author`, `box`, `ip`, `createdate`) VALUES ('{$article_title}', '{$Admin['username']}', '{$article_html}', '{$ip}', {$createdata})") > 0 ? true:false;
			if($addarticle) {
				$result['code'] = 1;
			}
		}
		exit(json_encode($result));
	} elseif ($action == "editArticle") {
		header("content-type:text/plain; charset=utf-8");
		$result = array();
		$id = param_filter("id");
		if(!is_empty($id)) {
			$editArticle = $mysqli->db->executeQuery("SELECT * FROM `spe_articles` WHERE `id` = {$id}", true);
			if($editArticle) {
				$result['code'] = 1;
				$result['data'] = $editArticle;
			}
		}
		exit(json_encode($result));
	} elseif ($action == "delArticle") {
		header("content-type:text/plain; charset=utf-8");
		$result = array();
		$id = param_filter("id");
		if(!is_empty($id)) {
			$delArticle = $mysqli->db->executeQuery("DELETE FROM `spe_articles` WHERE `id` = {$id}") > 0 ? true:false;
			if($delArticle) {
				$result['code'] = 1;
			}
		}
		exit(json_encode($result));
	} elseif ($action == "editArticles") {
		header("content-type:text/plain; charset=utf-8");
		$result = array();
		$id = param_filter("id");
		$title = param_filter("article_title");
		$box = param_filter("article_box");
		if(!is_empty($id) && !is_empty($title) && !is_empty($box)) {
			$editArticles = $mysqli->db->executeQuery("UPDATE `spe_articles` SET `title` = '{$title}', `box` = '{$box}', `author` = '{$Admin['username']}' WHERE `id` = {$id}") > 0 ? true:false;
			if($editArticles) {
				$result['code'] = 1;
			}
		}
		$result['data'] = $title;
		exit(json_encode($result));
	} elseif ($action == "systemUpdate") {
		header("content-type:text/plain; charset=utf-8");
		$result = array();
		$v = param_filter("v");
		if(!is_empty($v)) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://api.speblog.ga/getUpdate.php");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;
			$result = curl_exec($ch);
			curl_close($ch);
		}
		exit($result);
	} elseif ($action == "setComment") {
		header("content-type:text/plain; charset=utf-8");
		$result = array();
		$neb = param_filter("neb");
		if(!is_empty($neb)) {
			$swi = $mysqli->db->executeQuery("UPDATE `spe_config` SET `value` = '{$neb}' WHERE `key` = 'whecomment'") > 0 ? true:false;
			if($swi) $result['code'] = 1;
		}
		exit(json_encode($result));
	} elseif ($action == "delReplys") {
		header("content-type:text/plain; charset=utf-8");
		$result = array();
		$replys_id = param_filter("id");
		if(!is_empty($replys_id)) {
			$delReplys = $mysqli->db->executeQuery("DELETE FROM `spe_comment` WHERE `id` = {$replys_id}") > 0 ? true:false;
			if($delReplys) $result['code'] = 1;
		}
		exit(json_encode($result));
	}
}

require VIEW_ROUTE . "common/header.inc.php";
$Admin ? require VIEW_ROUTE . "admin/admin.index.inc.php" : require VIEW_ROUTE . "admin/admin.login.inc.php";
require VIEW_ROUTE . "common/footer.inc.php";
?>