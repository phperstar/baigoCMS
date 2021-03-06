<?php
return "<h3>API 概述</h3>
	<p>
		各种应用整合 baigo CMS 都是通过 API 接口实现的，您可以在各类应用程序中使用该接口，通过发起 HTTP 请求方式调用 baigo CMS 服务，返回 JSON 数据。
	</p>
	<p>
		使用 API 接口，您需先在 baigo CMS 创建应用，创建成功后会给出 APP ID 和 APP KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。
	</p>

	<hr>

	<h3>应用的验证</h3>
	<p>
		baigo CMS 的所有 API 接口均需要验证应用以及验证应用的权限。详情请查看具体接口说明。
	</p>

	<hr>

	<h3>返回结果</h3>
	<p>
		返回的结果均为 <mark>Base64 编码</mark>，需要进行 <mark>Base64 解码</mark>。
	</p>";
