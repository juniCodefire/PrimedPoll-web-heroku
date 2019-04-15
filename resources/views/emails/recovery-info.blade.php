<!DOCTYPE html>
    <section style="width: 80%;margin: auto;height:600px;box-shadow: 0 0 10px #e6e6e6;color: grey;">
        <div id="head_1" style="background: #e6e6e6; height: 80px;">
        <h2 style="margin: 0;padding: 25px;color: skyblue;background: #e6e6e6;font-family:sans-serif;font-weight: bold;">PrimedPoll</h2><br><br>
        </div>

        <div id="box" style="width: 95%; margin: auto;"><br>
            <h4>Dear <b>{{$user->name}}</b></h4>
            <div id="third_block">
                    <p>Your verification token is <h4>{{$user->verifycode}}</h4></p>

                    <p>Click on the below link to change your password:</p><br><br>
                    <p style="text-align:center;"><a href="http://localhost:8000/password/change?email={{ $user->email }}&verifycode={{ $user->verifycode }}">
                    <button style="background:darkblue; color:white; padding:8px; border: 1px solid darkblue;">RESET PASSWORD</button>
                    </a></p>

                    <h5 style="text-align:right;">PrimedPoll Team</h5>
            </div>
        </div>
    </section>
</html>
