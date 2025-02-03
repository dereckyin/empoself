<?php include 'check.php';?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>把力量還給你</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .banner {
            background-color: #C9D3CB;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .menu-icon {
            cursor: pointer;
            font-size: 20px;
            color: #f7f7f7;
        }
        .banner-title {
            font-size: 24px;
            font-weight: 700;
            color: #f7f7f7;
        }
        .menu-icon.invisible{
            color: #C9D3CB;
        }
        .side-menu {
            position: fixed;
            top: 0;
            left: -100vw;
            width: 100vw;
            height: 100%;
            background-color: #f7f7f7;
            box-shadow: 2px 0 6px rgba(0, 0, 0, 0.1);
            transition: left 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .side-menu .side-banner {
            width: 100%;
            padding: 15px 20px;
            background-color: rgb(226, 226, 226);
            text-align: center;
            transition: .5s;
        }
        .side-menu .side-banner span {
            color: white;
            cursor: pointer;
            font-size: 24px;
            font-weight: 700;
        }
        .side-menu.active {
            left: 0;
        }
        .user-info {
            text-align: center;
            padding: 20px;
        }
        .user-info img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .user-info p {
            margin: 5px 0;
            font-size: 18px;
            font-weight: bold;
        }
        .user-info span {
            display: block;
            font-size: 14px;
            color: #666;
        }
        .links {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            list-style-type: none;
            padding: 0 20px;
        }
        .links a {
            text-decoration: none;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px;
            border-radius: 10px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            border: 3px solid #C4CEC6;
        }
        .links a img {
            width: 32px;
            height: 32px;
            margin-bottom: 5px;
        }
        .logout-btn {
            padding: 10px;
            text-align: center;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 20px 20px;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }

        .image-container {
            margin: 25px auto;
            width: 80%;
            max-width: 800px;
            background-image: url('bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .image-container img {
            width: 100%;
        }

    </style>
</head>
<body>
    <div class="banner">
        <div class="menu-icon" onclick="toggleMenu()">☰</div>
        <div class="banner-title">把力量還給你</div>
        <div class="menu-icon invisible">☰</div>
    </div>
    <div class="side-menu" id="sideMenu">

            <div class="side-banner" onclick="toggleMenu()">
                <span>把力量還給你</span>
            </div>
            <div class="user-info">
                <img src="images/man1.jpg" alt="大頭照">
                <p>宋慧喬</p>
                <span>學員</span>
            </div>
            <ul class="links">
                <li><a href="#"><img src="images/timein.png" alt="圖示">上課簽到</a></li>
                <li><a href="#"><img src="images/schedule.png" alt="圖示">教練課列表</a></li>
                <li><a href="#"><img src="images/purchasing.png" alt="圖示">購買記錄</a></li>
                <li><a href="#"><img src="images/profile.png" alt="圖示">會員資料</a></li>
            </ul>
       
        <button class="logout-btn" onclick="logout();">登出</button>
    </div>

    <div class="image-container">
        <img src="images/bg.png">
    </div>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('sideMenu');
            menu.classList.toggle('active');
        }
    </script>
</body>
<script>
    function logout() {
        var res = document.cookie;
            var multiple = res.split(";");
            for(var i = 0; i < multiple.length; i++) {
               var key = multiple[i].split("=");
               document.cookie = key[0]+" =; expires = Thu, 01 Jan 1970 00:00:00 UTC";
            }
        
        localStorage.token = "";
        window.location.href = "login";
    }
</script>
</html>
