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

        @media screen and (max-width: 640px) {
            .container {
                margin: 50px 25px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- 第一部分 -->
        <div id="section1">
            <h1>基本資料</h1>
            <label for="name">姓名 <span class="star">*</span></label>
            <input type="text" id="name" required>

            <label for="gender">性別</label>
            <select id="gender">
                <option value=""></option>
                <option value="male">男性</option>
                <option value="female">女性</option>
            </select>

            <label for="birthday">生日 <span class="star">*</span></label>
            <input type="date" id="birthday" required>

            <label for="phone">手機號碼 <span class="star">*</span></label>
            <input type="tel" id="phone" required>

            <label for="email">Email <span class="star">*</span></label>
            <input type="email" id="email" required>

            <label for="address">住址 <span class="star">*</span></label>
            <input type="text" id="address" required>

            <label for="emergency-contact">緊急連絡人 <span class="star">*</span></label>
            <input type="text" id="emergency-contact" required>

            <label for="emergency-phone">緊急連絡人電話 <span class="star">*</span></label>
            <input type="tel" id="emergency-phone" required>

            <label>如何知道我們的(若選親友介紹，請在其他的部分填寫親友姓名)</label>
            <div class="checkbox-group">
                <label><input type="checkbox" value="nearby"> 住附近（走路10分鐘內）</label>
                <label><input type="checkbox" value="experience"> 路過被體驗課吸引</label>
                <label><input type="checkbox" value="referral"> 親友介紹</label>
                <label><input type="checkbox" value="social"> 社群網路</label>
                <label><input type="checkbox" value="advertisement"> 看到實體廣告</label>
                <label><input type="checkbox" value="other"> 其他：<input type="text" id="other-info"></label>

            </div>

            <label>體況 (其他請詳細描述體況) <span class="star">*</span></label>
            <div class="checkbox-group">
                <label><input type="checkbox" value="none"> 無</label>
                <label><input type="checkbox" value="heart"> 心血管疾病</label>
                <label><input type="checkbox" value="diabetes"> 糖尿病</label>
                <label><input type="checkbox" value="asthma"> 氣喘</label>
                <label><input type="checkbox" value="arthritis"> 關節炎</label>
                <label><input type="checkbox" value="surgery"> 重大手術</label>
                <label><input type="checkbox" value="wheelchair"> 車禍</label>
                <label><input type="checkbox" value="pregnancy"> 懷孕或半年內生產</label>
                <label><input type="checkbox" value="spine"> 脊椎相關病變</label>
                <label><input type="checkbox" value="anemia"> 貧血</label>
                <label><input type="checkbox" value="infection"> 過去病史</label>
                <label><input type="checkbox" value="medication"> 服用藥物</label>
                <label><input type="checkbox" value="hospitalized"> 半年內住過院</label>
                <label><input type="checkbox" value="other"> 其他：<input type="text" id="condition-other"></label>
            </div>

            <button onclick="nextSection(1)">下一步</button>
        </div>

        <!-- 第二部分 -->
        <div id="section2" class="hidden">
            <h1>為什麼是肌力訓練</h1>
            <div class="image-container">
                <h3>迷思一：怕進來上教 金剛芭比</h3>
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
            <button onclick="submitForm()">完成</button>
        </div>

       
    </div>
</body>
</html>
