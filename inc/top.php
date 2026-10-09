<?php session_start();
if(($_SESSION['role']??'')!=$role){header('Location: ../index.php');exit;}
require __DIR__.'/data.php';
$N=['student'=>[['dashboard','Home','home','Overview'],['request','New request','edit','Documents'],['history','My requests','list','Documents']],'staff'=>[['queue','Request queue','list','Processing']],'admin'=>[['integration','Integration','link','Configuration'],['employees','Employees','users','Configuration'],['settings','Settings','sliders','Configuration']]];
$L=['student'=>'Student','staff'=>'Registrar office','admin'=>'Administrator'];
$U=['student'=>$me['name'],'staff'=>'Maria Santos','admin'=>'System Admin'];
?><!doctype html><html lang=en><head><meta charset=utf-8><meta name=viewport content="width=device-width,initial-scale=1"><title><?=$title?> | Academix</title><link rel=stylesheet href=../assets/style.css>
<script>document.documentElement.dataset.t=localStorage.t||'light';localStorage.c==1&&document.documentElement.classList.add('col')</script></head>
<body data-role="<?=$role?>"><aside class=sb><div class=br><b>A</b><span class=lb>Academix</span></div>
<?php $g='';foreach($N[$role] as $n):if($n[3]!=$g){$g=$n[3];echo "<div class='gl lb'>$g</div>";}?><a class="nv<?=$n[0]==$page?' on':''?>" href="<?=$n[0]?>.php"><?=ic($n[2])?><span class=lb><?=$n[1]?></span></a>
<?php endforeach?><a class=nv style="margin-top:auto" href="../index.php?out=1"><?=ic('out')?><span class=lb>Log out</span></a></aside>
<div class=mn><header class=tb><button class=ib id=cl title="Collapse sidebar"><?=ic('menu')?></button><h1><?=$title?></h1><span class=sp></span>
<?php if($role=='student'):?><a class=bt href=request.php><?=ic('edit')?>New request</a><?php endif?>
<button class=ib id=th title="Toggle theme"><?=ic('moon')?></button><button class=ib data-toast="No new notifications"><?=ic('bell')?></button>
<span class=uc><span class=av><?=ini($U[$role])?></span><span class=lb2><b><?=$U[$role]?></b><small class=mu><?=$L[$role]?></small></span></span></header><main class=ct>
