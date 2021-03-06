<!DOCTYPE html>
<html lang="<?php print C_HTML_LANG ?>">
<head>
	<meta charset="utf-8">
	<title><?php print gettext($app->getPageTitle()) ?></title>
	<meta name="description"
		  content="<?php print gettext($app->getPageDescription()) ?>">
	<meta name="viewport"
		  content="width=device-width, initial-scale=1.0">

	<?php print $app->getPageDependenciesHtml() ?>

	<script type="text/javascript">
		$.ajaxSetup({
			url: "<?php print C_PROJECT_URL . $app->getRealUrl() . '/' ?>",
			error: function(jqXHR, exception) {
				if(jqXHR.responseText) {
					alert(jqXHR.responseText.replace(/<.*?>/g, ''));
				} else {
					if(jqXHR.status === 0) {
						alert('Not connected. Please, verify network.');
					} else if(jqXHR.status == 404) {
						alert('Requested page not found. [404]');
					} else if(jqXHR.status == 500) {
						alert('Internal Server Error [500].');
					} else if(exception === 'parsererror') {
						alert('Requested JSON parse failed.');
					} else if(exception === 'timeout') {
						alert('Time out error.');
					} else if(exception === 'abort') {
						alert('Ajax request aborted.');
					} else {
						alert('Uncaught Error.');
					}
				}
			});

		<?php if(session_id() != '') { ?>
		$(document).ajaxSend(function(e, xhr, options) {
			xhr.setRequestHeader("X-CSRF-Token", "<?php print sha1(session_id() . $app->getPageId()) ?>");
		});
		<?php } ?>


		Modernizr.load([
			{
				// The test: does the browser understand Media Queries?
				test: Modernizr.mq("only all"),
				// If not, load the respond.js file
				nope: "<?php print C_LIB_FRONT_END_URL . $conf['dependencies']['respond_js']['default'] ?>"
			}
		]);

		function show_modal(elem_modal, elem_modal_content, content_html, elem_focus) {
			if(elem_focus) {
				elem_modal.on('hidden.bs.modal', function (e) {
					elem_focus.focus();
				});
			}
			elem_modal_content.html(content_html);
			elem_modal.modal("show");
		}

	</script>

</head>

<body>

<!-- Main section
============================================================================ -->
<section class="main">