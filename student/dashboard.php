<?php $role='student';$page='dashboard';$title='Home';require '../inc/top.php';
$n=fn($st)=>count(array_filter($m,fn($r)=>in_array($r['st'],$st)));
$fix=array_values(array_filter($m,fn($r)=>$r['st']=='Needs Correction'))[0]??0;?>
<div class=gr4>
<div class=pn><p class=mu>Active requests</p><h2><?=$n(['Submitted','Under Review','Processing','Approved / Cleared'])?></h2></div>
<div class=pn><p class=mu>Needs correction</p><h2><?=$n(['Needs Correction'])?></h2></div>
<div class=pn><p class=mu>Ready for release</p><h2><?=$n(['Ready for Release'])?></h2></div>
<div class=pn><p class=mu>Completed</p><h2><?=$n(['Completed'])?></h2></div></div>
<?php if($fix):?><div class=al><b>Action needed on <?=$fix['id']?></b><br><?=$fix['rm']?> <a href="history.php#<?=$fix['id']?>" style="color:var(--ac);font-weight:700">Open request</a></div><?php endif?>
<div class=g2s><div class=pn><h3>Recent requests</h3><?php foreach($m as $r):?><a class=rw href="history.php#<?=$r['id']?>"><span class=av><?=ini($r['doc'])?></span><span class=rb><b><?=$r['doc']?></b><span class=mu><?=$r['id']?>, <?=$r['date']?></span></span><?=pill($r['st'])?></a><?php endforeach?></div>
<div><div class=pn style="margin-bottom:16px"><h3>Your details</h3><?php foreach(['Student ID'=>'id','Name'=>'name','Email'=>'email','Program'=>'prog','Year level'=>'yr','Status'=>'st'] as $l=>$f):?><div class=dl><span class=mu><?=$l?></span><b><?=$me[$f]?></b></div><?php endforeach?><small class=mu>Read from the university system.</small></div>
<div class=pn><h3>Pickup hours</h3><p class=mu>Registrar window, Monday to Friday, 8 AM to 4 PM.</p></div></div></div>
<?php require '../inc/bottom.php'?>
