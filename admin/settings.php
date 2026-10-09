<?php $role = 'admin';
$page = 'settings';
$title = 'Settings';
require '../inc/top.php';
$c = '2.4fr 120px 110px 90px' ?>
<div class=pn style="overflow-x:auto">
    <div style="min-width:640px">
        <h3>Document types</h3>
        <?php row($c, ['Document', 'Fee (PHP)', 'Allow upload', 'Enabled'], 1);
        foreach ($docs as $d) row($c, [nm($d[0]), '<input class=in value=' . $d[1] . '>', '<input class=sw type=checkbox ' . ($d[2] ? 'checked' : '') . '>', '<input class=sw type=checkbox checked>']) ?>
        <button class="bt g" data-toast="Document type added" style="margin-top:8px">Add document type</button>
    </div>
</div>
<div class=pn>
    <h3>General</h3><label>Cancel requests that need correction after (days)<input class=in type=number value=14></label>
    <label>Default release method<select class=in>
            <option>On-site pickup
            <option>DeliveryS
        </select></label>
    <button class=bt data-toast="Settings saved">Save settings</button>
</div>
<?php require '../inc/bottom.php' ?>