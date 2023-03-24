<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Latuce</title>
	<link rel="stylesheet" href="style.css">
	<link rel="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" href="style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

	<style>
		.login-button {
			display: flex;
			width: 90%;
			background-color: #00C164;
			color: white;
			padding: 24px 0px;
			cursor: pointer;
			text-decoration: none;
			align-items: center;
			justify-content: center;
			height: 24px;
			font-size: 16px;
			border-radius: 8px;
			border: 1px solid #ccc;
		}
	</style>
</head>

<body class="imageBack">

	<img class="left-image" src="https://files.etrade.vn/images/276/files/6c9cca0d1997ffc9a686.jpeg" alt="Mô tả hình ảnh">

	<div class="righthand">
		<h1 style=" position: absolute;
					font-style: normal;
					font-weight: 700;
					font-size: 50px;
					line-height: 73px;
					text-align: center;

					color: #00C164;
					float: left; margin-left: 70px;">Latuce.</h1>
		<h3 style=" position: absolute;
					height: 29px;
					margin-left: 70px;
					top: 10%;			
					text-align: center;

					font-style: normal;
					font-weight: 300;
					font-size: 22px;
					line-height: 27px;
					color: #535353; ">Hệ thống tưới nhỏ giọt cho cây xà lách</h3>

		<form onsubmit="loginSubmit(event);">
			<h1 style="float: left; margin-top: 40px; font-weight: 500; color: #00C164;">Đăng nhập</h1>

			<label for="username">Tài khoản</label>
			<input type="text" id="username" name="username" required>

			<label for="password">Mật khẩu</label>
			<input type="password" id="password" name="password" required>

			<div class="forgot-password">
				<a href="forgot-password">Quên mật khẩu?</a>
			</div>

			<button class="login-button">Đăng nhập</button>

			<div style=" font-size: 14px;" class="dangki">
				Chưa có tài khoản?
				<div class="register">
					<a style="color: #00C164; text-decoration: none;" href="register">Đăng kí ngay</a>
				</div>
			</div>
		</form>
	</div>


	<script>
		function loginSubmit(event) {
			event.preventDefault();
			$.ajax({
				type: "post",
				url: "/DADN_HK222/login/login_process",
				data: {
					"username": $("#username").val(),
					"password": $("#password").val(),
				},
				success: function(response) {
					if (response == 1) {
						window.location.href = "/DADN_HK222"
					}
				}
			});
		}
	</script>
</body>

</html>