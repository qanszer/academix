<!doctype html>
<html lang=en>

<head>
    <meta charset=utf-8>
    <meta name=viewport content="width=device-width,initial-scale=1">
    <title>Activate | Academix</title>
    <link rel=stylesheet href=assets/style.css>
    <script>
        document.documentElement.dataset.t = localStorage.t || 'light'
    </script>
</head>

<body class=au>
    <form class=cd>
        <h1>Activate account</h1>
        <p class=mu>Your details come from the university system.</p>
        <div class=tabs><label><input type=radio name=role value=student checked><span>Student</span></label><label><input type=radio name=role value=staff><span>Employee</span></label></div>
        <div class=stp id=s1><label>Student or employee ID<input class=in id=uid value=2021-00123></label>
            <label>Registered email<input class=in type=email placeholder="Email on file with the university"></label>
            <p class=mu style="margin-bottom:14px">An ID alone is not enough. We send a code to the email on file.</p>
            <button class=bt data-go=2>Send code</button>
        </div>
        <div class=stp id=s2 hidden><label>6-digit code<input class=in maxlength=6 placeholder="Any 6 digits in this demo"></label><button class=bt data-go=3>Verify code</button></div>
        <div class=stp id=s3 hidden><label>New password<input class=in type=password></label><label>Confirm password<input class=in type=password></label><button class=bt data-go=done>Activate account</button></div>
        <p class=mu style="margin-top:14px;text-align:center"><a href=index.php style="color:var(--ac);font-weight:600">Back to log in</a></p>
    </form>
    <div class=ts id=ts></div>
    <script src=assets/app.js></script>
</body>

</html>