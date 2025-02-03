<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
        }

        .background {
            background-image: url('images/bg.png');
            background-size: cover;
            background-position: center;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: black;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
            margin: 0 25px;
        }

        .login-container h1 {
            font-size: 36px;
            margin-bottom: 20px;
            margin-top: -5px;
        }

        .login-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }

        .login-container button > span.hint-msg {
            position: absolute;
            left: 0;
            right: 0;
            bottom: -20px;
            color: red;
            font-size: 12px;
        }

        .login-container button:hover {
            background-color: #45a049;
        }

        .forgot-password {
            display: block;
            margin-top: 10px;
            color: #4CAF50;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="background" id="app">
        <div class="login-container">
            <h1>把力量還給你</h1>
            <form>
                <input type="text" placeholder="手機號碼" required v-model="phone">
                <input type="password" placeholder="密碼" required v-model="password">
                <a href="#" class="forgot-password" onclick="window.location.href='reset'">忘記密碼？</a>
                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                <button type="submit" style="position: relative;" class="g-recaptcha" 
                                                                    data-sitekey='6LdWV8AqAAAAADF7umMs-HirSiyCAg5QmpU-G3mi'
                                                                    data-callback='onSubmit' 
                                                                    data-action='submit'>登入<span class="hint-msg" v-if="showHint">{{ hint }}</span></button>
            </form>
        </div>
    </div>
</body>
<script defer src="js/npm/vue/dist/vue.js"></script> 
<script defer src="js/axios.min.js"></script> 
<script defer src="js/npm/sweetalert2@9.js"></script>
<script defer src="js/login.js"></script>
<script type="text/javascript" src="js/rm/jquery-3.4.1.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>
<script>
    function onSubmit(token) {
     document.getElementById("recaptchaResponse").value = token;
        app.checkLogin();
   }

    $(document).keypress(function (e) {
  if (e.which === 13 && $('input[type="password"]').is(':focus')) {
    if ($('input[type="password"]').val().length < 1) {
      e.preventDefault();
    }
    else
        app.checkLogin();
  }
});
</script>
</html>
