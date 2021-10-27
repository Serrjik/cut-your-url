<?php include "./includes/header.php"; ?>
<?php
// cl_print_r($_GET[], "\$_GET\[\]:");

// http://cut-your-url/goo

// Если в адресную строку передан URL сайта:
if (isset($_GET['url']) && !empty($_GET['url'])) {
	$url = strtolower(trim($_GET['url']));

	// cl_var_dump($url);

	// Ссылка в БД, соответствующая введённой пользователем.
	$link = db_query("SELECT * FROM `links` WHERE `short_link` = '$url';")->fetch();

	// Если в БД нет ссылки, соответствующей введённой пользователем:
	if (empty($link)) {
		// echo "Такая ссылка не найдена";
		ob_get_clean();
		header(('Location: ' . get_url("404.php")));
		die;
	}

	// cl_var_dump($link["long_link"], '$link["long_link"]');

	db_exec("UPDATE `links` SET `views` = `views` + 1 WHERE `links`.`short_link` = '$url';");
	ob_get_clean();
	header(('Location: ' . $link["long_link"]));
	die;
}
?>
<!-- http://cut-your-url/index.php?url=goo -->
<main class="container">
	<div class="row mt-5">
		<div class="col">
			<h2 class="text-center">Необходимо <a href="<?= get_url("register.php") ?>">зарегистрироваться</a> или <a href="<?= get_url("login.php") ?>">войти</a> под своей учетной записью</h2>
		</div>
	</div>
	<div class="row mt-5">
		<div class="col">
			<h2 class="text-center">Пользователей в системе: <?= $users_count ?></h2>
		</div>
	</div>
	<div class="row mt-5">
		<div class="col">
			<h2 class="text-center">Ссылок в системе: <?= $links_count ?></h2>
		</div>
	</div>
	<div class="row mt-5">
		<div class="col">
			<h2 class="text-center">Всего переходов по ссылкам: <?= $views_count ?></h2>
		</div>
	</div>
</main>
<?php include "./includes/footer.php"; ?>