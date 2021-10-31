<?php
include_once "./includes/header.php";

// Если пользователь авторизован:
if (isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])) {
	// Переход на страницу профиля пользователя.
	header('Location: ' . get_url("profile.php"));
	die;
}

// Если в сессии есть сообщение о ошибке:
$error = "";
if (isset($_SESSION["error"]) && !empty($_SESSION["error"])) {
	$error = $_SESSION["error"];
	$_SESSION["error"] = "";
}

// Если в сессии есть сообщение об успехе:
$success = "";
if (isset($_SESSION["success"]) && !empty($_SESSION["success"])) {
	$success = $_SESSION["success"];
	$_SESSION["success"] = "";
}

// Если передан логин пользователя:
if (isset($_POST['login']) && !empty($_POST['login'])) {
	// Зарегистрировать пользователя.
	register_user($_POST);
}
?>
<main class="container">
	<!-- Сообщение об успехе -->
	<?php if (!empty($success)) { ?>
		<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
			<?= $success ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<!-- Сообщение о ошибке -->
	<?php if (!empty($error)) { ?>
		<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
			<?= $error ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<div class="row mt-5">
		<div class="col">
			<h2 class="text-center">Регистрация</h2>
			<p class="text-center">Если у вас уже есть логин и пароль, <a href="<?= get_url("login.php") ?>">войдите на сайт</a></p>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-4 offset-4">
			<form action="" method="post">
				<div class="mb-3">
					<label for="login-input" class="form-label">Логин</label>
					<input type="text" class="form-control" id="login-input" required name="login"><!-- is-valid -->
					<!-- <div class="valid-feedback">Все ок</div> -->
				</div>
				<div class="mb-3">
					<label for="password-input" class="form-label">Пароль</label>
					<input type="password" class="form-control" id="password-input" required name="pass"><!-- is-invalid -->
					<!-- <div class="invalid-feedback">А тут не ок</div> -->
				</div>
				<div class="mb-3">
					<label for="password-input2" class="form-label">Пароль еще раз</label>
					<input type="password" class="form-control" id="password-input2" required name="pass2"><!-- is-invalid -->
					<!-- <div class="invalid-feedback">И тут тоже не ок</div> -->
				</div>
				<button type="submit" class="btn btn-primary">Зарегистрироваться</button>
			</form>
		</div>
	</div>
</main>
<?php include_once "./includes/footer.php"; ?>