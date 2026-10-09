<?php $role = 'admin';
$page = 'integration';
$title = 'Integration';
require '../inc/top.php' ?>
<div class=g2c>
    <div class=cd>
        <h3>University API connection</h3>
        <label>Base URL<input class=in value="http://192.168.1.20/mock-univ/api/v1"></label>
        <label>API key<input class=in type=password value=mock-key-8f3a21></label>
        <div class=dl><span>Status</span><span><span class=p id=cs>Connected</span> <span class=mu id=cm>42 ms</span></span></div>
        <p class=mu id=ls style="margin-bottom:14px">Last sync: Oct 09, 2026 8:00 AM</p>
        <button class=bt id=tc>Test connection</button> <button class="bt g" id=sy>Sync now</button>
    </div>
    <div class=cd>
        <h3>Data retrieved (read only)</h3>
        <?php foreach (['Student ID', 'Name', 'Email or contact', 'Program', 'Year level', 'Enrollment status', 'Grades'] as $f) echo "<label style='padding:4px 0'><input type=checkbox checked disabled> $f</label>" ?>
        <p class=mu style="margin-top:12px">Academix only reads from the university system. It never changes the source database.</p>
    </div>
</div>
<?php require '../inc/bottom.php' ?>