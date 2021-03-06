<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_CLASS . "api.class.php"); //载入模板类
include_once(BG_PATH_MODEL . "app.class.php"); //载入后台用户类
include_once(BG_PATH_MODEL . "cate.class.php"); //载入后台用户类
include_once(BG_PATH_MODEL . "articlePub.class.php"); //载入后台用户类
include_once(BG_PATH_MODEL . "tag.class.php"); //载入后台用户类
include_once(BG_PATH_MODEL . "attach.class.php"); //载入后台用户类
include_once(BG_PATH_MODEL . "thumb.class.php"); //载入后台用户类

/*-------------文章类-------------*/
class API_ARTICLE {

	private $obj_api;
	private $mdl_app;
	private $mdl_cate;
	private $mdl_articlePub;
	private $mdl_tag;
	private $mdl_attach;
	private $mdl_thumb;

	function __construct() { //构造函数
		$this->obj_api        = new CLASS_API();
		$this->mdl_app        = new MODEL_APP(); //设置管理组模型
		$this->mdl_cate       = new MODEL_CATE(); //设置文章对象
		$this->mdl_articlePub = new MODEL_ARTICLE_PUB(); //设置文章对象
		$this->mdl_tag        = new MODEL_TAG();
		$this->mdl_attach     = new MODEL_ATTACH(); //设置文章对象
		$this->mdl_thumb      = new MODEL_THUMB(); //设置上传信息对象

		if (file_exists(BG_PATH_CONFIG . "is_install.php")) { //验证是否已经安装
			include_once(BG_PATH_CONFIG . "is_install.php");
			if (!defined("BG_INSTALL_PUB") || PRD_CMS_PUB > BG_INSTALL_PUB) {
				$_arr_return = array(
					"str_alert" => "x030416"
				);
				$this->obj_api->halt_re($_arr_return);
			}
		} else {
			$_arr_return = array(
				"str_alert" => "x030415"
			);
			$this->obj_api->halt_re($_arr_return);
		}
	}


	/**
	 * api_list function.
	 *
	 * @access public
	 * @return void
	 */
	function api_get() {
		$this->app_check("get");

		$_num_articleId   = fn_getSafe(fn_get("article_id"), "int", 0);

		if ($_num_articleId == 0) {
			$_arr_return = array(
				"str_alert" => "x120212",
			);
			$this->obj_api->halt_re($_arr_return);
		}

		$_arr_articleRow = $this->mdl_articlePub->mdl_read($_num_articleId);

		if ($_arr_articleRow["str_alert"] != "y120102") {
			$this->obj_api->halt_re($$_arr_articleRow);
		}

		unset($_arr_articleRow["article_url"]);

		$_arr_cateRow = $this->mdl_cate->mdl_readPub($_arr_articleRow["article_cate_id"]);

		if ($_arr_cateRow["str_alert"] != "y110102") {
			$this->obj_api->halt_re($_arr_cateRow);
		}

		if ($_arr_cateRow["cate_status"] != "show") {
			$_arr_return = array(
				"str_alert" => "x110102",
			);
			$this->obj_api->halt_re($_arr_return);
		}

		unset($_arr_cateRow["urlRow"]);

		if ($_arr_cateRow["cate_type"] == "link" && $_arr_cateRow["cate_link"]) {
			$_arr_return = array(
				"str_alert" => "x110218",
				"cate_link" => $_arr_cateRow["cate_link"],
			);
			$this->obj_api->halt_re($_arr_return);
		}

		$_arr_articleRow["cateRow"] = $_arr_cateRow;

		if (strlen($_arr_articleRow["article_title"]) < 1 || $_arr_articleRow["article_status"] != "pub" || $_arr_articleRow["article_box"] != "normal" || $_arr_articleRow["article_time_pub"] > time()) {
			$_arr_return = array(
				"str_alert" => "x120102",
			);
			$this->obj_api->halt_re($_arr_return);
		}

		if ($_arr_articleRow["article_link"]) {
			$_arr_return = array(
				"str_alert" => "x120213",
				"article_link" => $_arr_articleRow["article_link"],
			);
			$this->obj_api->halt_re($_arr_return);
		}

		$_arr_articleRow["tagRows"] = $this->mdl_tag->mdl_list(10, 0, "", "show", "tag_id", $_arr_articleRow["article_id"]);

		if ($_arr_articleRow["article_attach_id"] > 0) {
			$_arr_articleRow["attachRow"]    = $this->mdl_attach->mdl_url($_arr_articleRow["article_attach_id"], $this->attachThumb);
		}

		$this->obj_api->halt_re($_arr_articleRow, true);
	}



	function api_list() {
		$this->app_check("get");

		$_str_key     = fn_getSafe(fn_get("key"), "txt", "");
		$_str_year    = fn_getSafe(fn_get("year"), "txt", "");
		$_str_month   = fn_getSafe(fn_get("month"), "txt", "");
		$_num_cateId  = fn_getSafe(fn_get("cate_id"), "int", 0);
		$_str_markIds = fn_getSafe(fn_get("mark_ids"), "txt", "");
		$_num_specId  = fn_getSafe(fn_get("spec_id"), "int", 0);
		$_str_tagIds  = fn_getSafe(fn_get("tag_ids"), "txt", "");
		$_num_perPage = fn_getSafe(fn_get("per_page"), "int", BG_SITE_PERPAGE);

		$_arr_markIds = explode("|", $_str_markIds);
		$_arr_tagIds  = explode("|", $_str_tagIds);

		if ($_num_cateId > 0) {
			$_arr_cateRow = $this->mdl_cate->mdl_readPub($_num_cateId);
			if ($_arr_cateRow["str_alert"] == "y110102" && $_arr_cateRow["cate_status"] == "show") {
				$_arr_cateIds   = $this->mdl_cate->mdl_cateIds($_num_cateId);
				$_arr_cateIds[] = $_num_cateId;
				$_arr_cateIds   = array_unique($_arr_cateIds);
			}
		} else {
			$_arr_cateIds = false;
		}

		$_num_articleCount    = $this->mdl_articlePub->mdl_count($_str_key, $_str_year, $_str_month, $_arr_cateIds, $_arr_markIds, $_num_specId, $_arr_tagIds);
		$_arr_page            = fn_page($_num_articleCount, $_num_perPage); //取得分页数据
		$_arr_articleRows     = $this->mdl_articlePub->mdl_list($_num_perPage, $_arr_page["except"], $_str_key, $_str_year, $_str_month, $_arr_cateIds, $_arr_markIds, $_num_specId, $_arr_tagIds);

		foreach ($_arr_articleRows as $_key=>$_value) {
			unset($_arr_articleRows[$_key]["article_url"]);

			$_arr_articleRows[$_key]["tagRows"][$_key_tag] = $this->mdl_tag->mdl_list(10, 0, "", "show", "tag_id", $_value["article_id"]);

			if ($_value["article_attach_id"] > 0) {
				$_arr_articleRows[$_key]["attachRow"]    = $this->mdl_attach->mdl_url($_value["article_attach_id"], $this->attachThumb);
			}
			$_arr_cateRow      = $this->mdl_cate->mdl_readPub($_value["article_cate_id"]);
			if ($_arr_cateRow["str_alert"] == "y110102" && $_arr_cateRow["cate_status"] == "show") {
				unset($_arr_cateRow["urlRow"]);
				$_arr_articleRows[$_key]["cateRow"] = $_arr_cateRow;
			}
		}

		$_arr_return = array(
			"pageRow"        => $_arr_page,
			"articleRows"    => $_arr_articleRows,
		);

		//print_r($_arr_return);

		$this->obj_api->halt_re($_arr_return, true);
	}


	/**
	 * app_check function.
	 *
	 * @access private
	 * @param mixed $num_appId
	 * @param string $str_method (default: "get")
	 * @return void
	 */
	private function app_check($str_method = "get") {
		$this->appGet = $this->obj_api->app_get($str_method);

		if ($this->appGet["str_alert"] != "ok") {
			$this->obj_api->halt_re($this->appGet);
		}

		$_arr_appRow = $this->mdl_app->mdl_read($this->appGet["app_id"]);
		if ($_arr_appRow["str_alert"] != "y190102") {
			$this->obj_api->halt_re($_arr_appRow);
		}
		$this->appAllow = $_arr_appRow["app_allow"];

		$_arr_appChk = $this->obj_api->app_chk($this->appGet, $_arr_appRow);
		if ($_arr_appChk["str_alert"] != "ok") {
			$this->obj_api->halt_re($_arr_appChk);
		}

		$this->attachThumb = $this->mdl_thumb->mdl_list(100);
	}

}
