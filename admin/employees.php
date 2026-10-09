<?php $role='admin';$page='employees';$title='Employees';require '../inc/top.php';$c='2.2fr 1fr 1fr 2.2fr 1.3fr 60px'?>
<div class=pn style="overflow-x:auto"><div style="min-width:840px"><p class=mu style="margin-bottom:14px">Accounts come from the university system. You can turn access on or off.</p>
<?php row($c,['Name','Employee ID','Office','Email','Last login','Access'],1);foreach($E as $e)row($c,[nm($e[0]),$e[1],$e[2],$e[3],$e[4],'<input class=sw type=checkbox '.($e[5]?'checked':'').'>'])?></div></div>
<?php require '../inc/bottom.php'?>
