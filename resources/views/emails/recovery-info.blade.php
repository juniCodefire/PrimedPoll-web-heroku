<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>New Password</title>
        </head>
            <body>
    <section style="width: 80%;margin: auto;height:auto; padding-bottom:20px;box-shadow: 0 0 10px #f58731;color: grey; border-radius: 5px;">
        <div id="head_1" style="background: #f58731; height: 80px;">
         <h2 style="margin: 0;padding: 25px;color: white;background: #f58731;font-family:sans-serif;font-weight: bold;">PrimedPoll</h2><br><br>
        </div>

        <div id="box" style="width: 95%; margin: auto;"><br>
            <h4>Hello <b>{{$user->first_name}} --- <span style="color: #f58731;">{{$user->username}}</span></b></h4>
            <div id="third_block">
                    <p>Please follow the below link to change your password!</p><br><br>
                    <p style="text-align:center;"><a href="https://polledapp-9fa3c.firebaseapp.com/password_change.html?email={{ $user->email }}&verifycode={{ $user->verifycode }}">
                    <button style="background:#f58731; color:white; padding:15px 70px 15px 70px; border: 1px solid #f58731;border-radius: 5px;">RESET PASSWORD</button>
                    </a></p>

                    <h5 style="text-align:right;">PrimedPoll Team</h5>
                    <p style="text-align:left;">You are getting this email because you have requested to change you password, if this was not authorize by you please discard.</p>
            </div>
        </div>
    </section>
</html>
