<?php
    $auth_token = (isset($_COOKIE['auth_token']) ? $_COOKIE['auth_token'] : null);

    if(!$auth_token) {
        $token = bin2hex(random_bytes(16)); // Generate a random token
        //$expiration = time() + (20 * 60); // Set expiration time for 20 minutes
        $expiration = 0;
        
        $ip = $_SERVER['REMOTE_ADDR']; // Get the user's IP address
        $user_id = 0;
        $user_agent = $_SERVER['HTTP_USER_AGENT']; // Get the user's user agent

        // Set the cookie
        setcookie("auth_token", $token, $expiration, "/", "", true, true); // Secure and HttpOnly flags

        include_once 'api/config/core.php';
        include_once 'api/objects/access_token.php';
        include_once 'api/config/database.php';

        $database = new Database();
        $database->getConnection();

        $access_token = new AccessToken($database);
        $ret = $access_token->insert($user_id, $token, $ip, $user_agent);

        //echo $ret;
    }

    
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>諮詢</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-image: url('images/bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
        }

        .container {
            width: 90%;
            max-width: 820px;
            margin: 50px 80px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 1);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
        }

        span.star {
            color: red;
        }

        .space-between {
            display: flex;
            justify-content: space-between;
        }

        .space-between button.load-data {
            width: unset;
            padding: 5px;
            font-size: 13px;
            margin: unset;
        }

        input, select, textarea, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0 25px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .hidden {
            display: none;
        }

        .checkbox-group label {
            display: block;
            margin: 5px 0 5px 10px;
        }

        .checkbox-group input[type="checkbox"] {
            margin-right: 5px;
            margin-bottom: 10px;
            width: unset;
        }

        .checkbox-group input[type="text"] {
            width: calc(100% - 80.45px);
            margin-left: 10px;
            margin-top: 5px;
            margin-bottom: 25px;
        }

        .image-container {
            width: 100%;
            border-radius: 10px;
            background-color: rgba(224, 224, 224, 0.5);
            padding: 15px 30px;
            margin: 30px 0;
            box-sizing: border-box;
        }

        .image-container img {
            width: 100%;
            border-radius: 10px;
            margin: 10px 0;
        }

        .file-upload {
            border: 1px dashed #ccc;
            padding: 20px;
            text-align: center;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .mask {
            position: fixed;
            background: rgba(0, 0, 0, 0.5);
            width: 100%;
            height: 100%;
            top: 0;
            z-index: 1;
            display: none;
        }

        .popup-dialog {
            position: fixed;
            top: calc(50vh - 245px);
            left: calc(50vw - 150px);
            /* transition: left 0.3s ease; */
            background-color: white;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 0 auto;
            z-index: 2;
            display: none;
            box-sizing: border-box;
        }

        .popup-dialog h1 {
            margin-top: 5px;
        }

        .popup-dialog input {
            margin-bottom: 10px;
        }

        .popup-dialog > button > span.hint-msg {
            position: absolute;
            left: 0;
            right: 0;
            top: 40px;
            color: red;
            font-size: 12px;
        }

        .popup-dialog .button-container {
            display: flex;
            justify-content: space-around;
        }
        .popup-dialog .button-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            width: 100px;
        }

        .popup-dialog .button-container button:hover {
            background-color: #45a049;
        }

        .popup-dialog .button-container .cancel-btn {
            padding: 10px;
            text-align: center;
            background-color: #F0502F;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .popup-dialog .button-container .cancel-btn:hover {
            background-color: #FDB72F;
        }

        @media screen and (max-width: 640px) {
            .container {
                margin: 50px 25px;
            }
        }
    </style>
</head>
<body>
    <div class="mask"></div>

    <div class="container" id="app">
        <!-- 第一部分 -->
        <div id="section1">
            <h1>基本資料</h1>
            <div class="space-between">
                <label for="name">姓名 <span class="star">*</span></label>
                <button class="load-data" onclick="toggle_popup();">載入資料</button>
            </div>
            <input type="text" id="name" required v-model="name">

            <label for="gender">性別</label>
            <select id="gender" v-model="gender">
                <option value=""></option>
                <option value="male">男性</option>
                <option value="female">女性</option>
            </select>

            <label for="birthday">生日 <span class="star">*</span></label>
            <input type="date" id="birthday" required v-model="birthday">

            <label for="phone">手機號碼 <span class="star">*</span></label>
            <input type="tel" id="phone" required v-model="phone">

            <label for="email">Email <span class="star">*</span></label>
            <input type="email" id="email" required v-model="email">

            <label for="address">住址 <span class="star">*</span></label>
            <input type="text" id="address" required v-model="address">

            <label for="emergency-contact">緊急連絡人 <span class="star">*</span></label>
            <input type="text" id="emergency-contact" required v-model="emergency_contact">

            <label for="emergency-phone">緊急連絡人電話 <span class="star">*</span></label>
            <input type="tel" id="emergency-phone" required v-model="emergency_contact_phone">

            <label>如何知道我們的(若選親友介紹，請在其他的部分填寫親友姓名)</label>
            <div class="checkbox-group">
                <label><input type="checkbox" value="nearby" v-model="referral_source"> 住附近（走路10分鐘內）</label>
                <label><input type="checkbox" value="experience" v-model="referral_source"> 路過被體驗課吸引</label>
                <label><input type="checkbox" value="referral" v-model="referral_source"> 親友介紹</label>
                <label><input type="checkbox" value="social" v-model="referral_source"> 社群網路</label>
                <label><input type="checkbox" value="advertisement" v-model="referral_source"> 看到實體廣告</label>
                <label><input type="checkbox" value="other" v-model="referral_source"> 其他：<input type="text" id="other-info" v-model="referral_source_other"></label>

            </div>

            <label>體況 (其他請詳細描述體況) <span class="star">*</span></label>
            <div class="checkbox-group">
                <label><input type="checkbox" value="none" v-model="health_condition"> 無</label>
                <label><input type="checkbox" value="heart" v-model="health_condition"> 心血管疾病</label>
                <label><input type="checkbox" value="diabetes" v-model="health_condition"> 糖尿病</label>
                <label><input type="checkbox" value="asthma" v-model="health_condition"> 氣喘</label>
                <label><input type="checkbox" value="arthritis" v-model="health_condition"> 關節炎</label>
                <label><input type="checkbox" value="surgery" v-model="health_condition"> 重大手術</label>
                <label><input type="checkbox" value="wheelchair" v-model="health_condition"> 車禍</label>
                <label><input type="checkbox" value="pregnancy" v-model="health_condition"> 懷孕或半年內生產</label>
                <label><input type="checkbox" value="spine" v-model="health_condition"> 脊椎相關病變</label>
                <label><input type="checkbox" value="anemia" v-model="health_condition"> 貧血</label>
                <label><input type="checkbox" value="infection" v-model="health_condition"> 過去病史</label>
                <label><input type="checkbox" value="medication" v-model="health_condition"> 服用藥物</label>
                <label><input type="checkbox" value="hospitalized" v-model="health_condition"> 半年內住過院</label>
                <label><input type="checkbox" value="other" v-model="health_condition"> 其他：<input type="text" id="condition-other" v-model="health_condition_other"></label>
            </div>

            <button @click="nextSection()">下一步</button>
            
        </div>

        <!-- 第二部分 -->
        <div id="section2" class="hidden">
            <h1>為什麼是肌力訓練</h1>
            <div class="image-container">
                <h3>迷思一：怕進來上教練課會變金剛芭比</h3>
                <img src="images/image1.jpg" alt="圖片1">
            </div>
            <div class="image-container">
                <h3>迷思二：我平常上班就有在動了，為什麼還要來運動？</h3>
                <img src="images/image2.jpg" alt="圖片2">
            </div>
            <div class="image-container">
                <h3>迷思三：我已經有在爬山、游泳、散步、跑操場，為何還要來上教練課？</h3>
                <img src="images/image3.jpg" alt="圖片3">
            </div>
            <button @click="submitForm()">完成</button>
        </div>

        <div class="popup-dialog">
            <h1>載入資料</h1>

            <label for="birthday">姓名</label>
            <input type="text" id="name"  v-model="pop_name" required>
            <label for="birthday">生日</label>
            <input type="date" id="birthday" required v-model="pop_birthday">
            <button type="button" style="position: relative; margin-bottom: 35px;" @click="send_verify_code()" :disabled="isButtonDisabled">
            發送驗證碼 {{ button_text }}
                <span class="hint-msg" v-if="showHint">{{ hint }}</span>
            </button>
            
            <input type="text" id="verify-code" placeholder="請輸入驗證碼" required v-model="verify_code">

            <div class="button-container">
                <button class="cancel-btn" onclick="toggle_popup();">取消</button>

                <button type="submit" style="position: relative;" class="g-recaptcha" 
                                                                    data-sitekey='6LdWV8AqAAAAADF7umMs-HirSiyCAg5QmpU-G3mi'
                                                                    data-callback='onSubmit' 
                                                                    data-action='submit'>提交</button>

            </div>

        </div>
    </div>


</body>
<script defer src="js/npm/vue/dist/vue.js"></script> 
<script defer src="js/axios.min.js"></script> 
<script defer src="js/npm/sweetalert2@9.js"></script>
<script defer src="js/consult.js"></script>
<script type="text/javascript" src="js/rm/jquery-3.4.1.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>

<script>
    function onSubmit(token) {
     document.getElementById("recaptchaResponse").value = token;
        app.submit_verify_code();
   }

    function toggle_popup() {
        $(".mask").toggle();
        $(".popup-dialog").toggle();
        app.pop_name = "";
        app.pop_birthday = "";
        app.verify_code = "";
        app.hint = "";
    }

</script>

</html>
