<?php session_start();
if(isset($_GET['out']))session_destroy();
if($_POST){$m=['student'=>'student/dashboard','staff'=>'staff/queue','admin'=>'admin/integration'];$r=$_SESSION['role']=isset($m[$_POST['role']])?$_POST['role']:'student';header('Location: '.$m[$r].'.php');exit;}
?><!doctype html><html lang=en><head><meta charset=utf-8><meta name=viewport content="width=device-width,initial-scale=1"><title>Log in | Academix</title><link rel=stylesheet href=assets/style.css><script>document.documentElement.dataset.t=localStorage.t||'light'</script></head>
<body class=au><form class=cd method=post>
<h1>Academix</h1><p class=mu>Request and track university documents.</p>
<div class=tabs><label><input type=radio name=role value=student checked><span>Student</span></label><label><input type=radio name=role value=staff><span>Staff</span></label><label><input type=radio name=role value=admin><span>Admin</span></label></div>
<label>ID<input class=in id=uid value=2021-00123></label>
<label>Password<input class=in type=password value=demo1234></label>
<button class=bt style="width:100%">Log in</button>
<p class=mu style="margin-top:14px;text-align:center">First time here? <a href=activate.php style="color:var(--ac);font-weight:600">Activate your account</a></p>
</form><script src=assets/app.js></script></body></html>
