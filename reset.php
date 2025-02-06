<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>重設密碼</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('images/bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 20vh 0;
            box-sizing: border-box;
            overflow: hidden;
        }

        .container {
            background-color: white;
            padding: 20px 40px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            margin: 0 25px;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 15px;
            margin-top: 15px;
        }

        table.verify_section {
            width: 100%;
        }

        table.verify_section tr:nth-of-type(1) td {
            text-align: left;
        }

        table.verify_section tr:nth-of-type(3) td {
            text-align: center;
            padding-top: 4px;
        }

        table.verify_section tr td:nth-of-type(1) div:nth-of-type(2) {
            margin-bottom: 0;
        }

        table.verify_section tr td:nth-of-type(2) {
            vertical-align: middle;
        }

        table.verify_section span.hint-msg1 {
            color: red;
            font-size: 12px;
        }

        .container hr {
            margin: 10px 0;
        }

        input[type="text"], input[type="password"], input[type="date"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .radio-group {
            display: flex;
            text-align: left;
            margin: 10px 0;
        }
        
        label {
            margin-left: 5px;
        }

        button > span.hint-msg2 {
            position: absolute;
            left: 0;
            right: 0;
            bottom: -20px;
            color: red;
            font-size: 12px;
        }

        @media screen and (max-width: 640px) {
            table.verify_section {
                font-size: smaller;
            }
        }
    </style>
</head>
<body>
    <div class="container" id="app">
        <h1>重設密碼</h1>

        <table class="verify_section">
            <tr>
                <td colspan="2">
                    <label for="birthday">姓名</label>
                    <input type="text" id="name"required v-model="name">
                    <label for="birthday">生日</label>
                    <input type="date" id="birthday" required v-model="birthday">
                </td>
            </tr>
            <tr>
                <td>
                    <div class="radio-group">
                        <input type="radio" id="line" name="verification" required checked>
                        <label for="line">Line 寄送驗證碼</label>
                    </div>
                    <div class="radio-group">
                        <input type="radio" id="email" name="verification" required>
                        <label for="email">Email 寄送驗證碼</label>
                    </div>
                </td>

                <td>
                    <button type="button" @click="send_verify_code()" :disabled="isButtonDisabled">{{ button_text }}</button>
                </td>
            </tr>
            <tr>
                <td colspan="2"><span class="hint-msg1" v-if="showHint">{{ hint }}</span></td>
            </tr>
        </table>

        <hr>

        <input type="text" id="verify-code" placeholder="驗證碼" required v-model="verify_code">
        <input type="password" id="new-password" placeholder="新密碼" required v-model="new_password">
        <input type="password" id="confirm-password" placeholder="再次輸入新密碼" required v-model="confirm_password">
        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
        <button type="submit" style="position: relative;" class="g-recaptcha" 
                                                                    data-sitekey='6LdWV8AqAAAAADF7umMs-HirSiyCAg5QmpU-G3mi'
                                                                    data-callback='onSubmit' 
                                                                    data-action='submit'>提交<span class="hint-msg2" v-if="showSubmitHint">{{ submit_hint }}</span></button>
    </div>

</body>
<script defer src="js/npm/vue/dist/vue.js"></script> 
<script defer src="js/axios.min.js"></script> 
<script defer src="js/npm/sweetalert2@9.js"></script>
<script defer src="js/reset.js"></script>
<script type="text/javascript" src="js/rm/jquery-3.4.1.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>
<script>
    function onSubmit(token) {
     document.getElementById("recaptchaResponse").value = token;
        app.submit_verify_code();
   }

    $(document).keypress(function (e) {
  if (e.which === 13 && $('input[type="password"]').is(':focus')) {
    if ($('input[type="password"]').val().length < 1) {
      e.preventDefault();
    }
    else
        app.submit_verify_code();
  }
});
</script>
</html>
