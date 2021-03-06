<?php
return "<h3>所有应用</h3>
	<p>
		点左侧菜单应用管理，进入如下界面，可以对应用进行编辑、删除、改变状态等操作。
	</p>

	<p>
		<img src=\"{images}app_list.jpg\" class=\"img-responsive thumbnail\">
	</p>

	<hr>

	<a name=\"form\"></a>
	<h3>创建（编辑）应用</h3>
	<p>
		点左侧子菜单的创建应用或者点击应用列表的编辑菜单，进入如下界面。在此，您可以对应用进行各项操作。
	</p>

	<p>
		<img src=\"{images}app_form.jpg\" class=\"img-responsive thumbnail\">
	</p>

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">填写说明</div>
		<div class=\"panel-body\">
			<h4 class=\"text-success\">应用名称</h4>
			<p>应用的名称。</p>

			<h4 class=\"text-success\">通知接口 URL</h4>
			<p>baigo SSO 在一些特殊情况下，通过此接口，告诉各个应用进行某种操作，比如删除用户、更新用户信息等，详情请看 API 接口。</p>

			<h4 class=\"text-success\">权限</h4>
			<p>选择该应用具备的各种权限。</p>

			<h4 class=\"text-success\">允许通信的 IP</h4>
			<p>允许与 baigo SSO 进行通信的 IP 地址，每行一个 IP 地址，可使用通配符 <mark>*</mark>，如：<mark>192.168.1.*</mark>，此时，只有 <mark>192.168.1</mark> 网段的 IP 地址 <mark>允许</mark> 通信。</p>

			<h4 class=\"text-success\">禁止通信的 IP</h4>
			<p>禁止与 baigo SSO 进行通信的 IP 地址，每行一个 IP 地址，可使用通配符 <mark>*</mark>，如：<mark>192.168.1.*</mark>，此时，<mark>192.168.1</mark> 网段的 IP 地址 <mark>禁止</mark> 通信。</p>

			<h4 class=\"text-success\">状态</h4>
			<p>可选启用、禁用。</p>

			<h4 class=\"text-success\">同步通知</h4>
			<p>如为开启状态，部分对本系统的操作，将通过通知接口 URL 通知各个应用，以供这些应用进行相应操作，详情请看 API 接口。</p>
		</div>
	</div>

	<hr>

	<a name=\"show\"></a>
	<h3>查看应用</h3>
	<p>
		应用列表的查看菜单，进入如下界面。在此，您获取调用 API 接口所需要的信息。如果 APP KEY 泄露，可以在此点击重置 APP KEY 按钮来进行更换，原 APP KEY 将作废。
	</p>

	<p>
		<img src=\"{images}app_show.jpg\" class=\"img-responsive thumbnail\">
	</p>

	<hr>

	<a name=\"belong\"></a>
	<h3>授权用户</h3>
	<p>
		点击应用列表的授权用户菜单，进入如下界面。在此，您可以对应用进行授权用户的操作，授权用户是指应用拥有这些用户的授权，可以编辑、删除取得授权的用户，此功能有助于提高用户数据库的安全性。
	</p>

	<p>
		<img src=\"{images}app_belong.jpg\" class=\"img-responsive thumbnail\">
	</p>";

