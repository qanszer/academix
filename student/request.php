<?php $role = 'student';
$page = 'request';
$title = 'New request';
require '../inc/top.php' ?>
<form class=g2s id=rf>
    <div class=cd>
        <label>Document type<select class=in id=dt><?php foreach ($docs as $d): ?><option data-f=<?= $d[1] ?> data-u=<?= $d[2] ?>><?= $d[0] ?></option><?php endforeach ?></select></label>
        <label id=ot hidden>Which document do you need?<input class=in placeholder="Describe the document"></label>
        <label>Purpose of request<input class=in placeholder="Employment, scholarship, transfer" required></label>
        <label>Supporting information<textarea class=in rows=3></textarea></label>
        <label>Preferred release method<select class=in>
                <option>On-site pickup
                <option disabled>Delivery (coming soon)
            </select></label>
    </div>
    <div class=cd>
        <h3>Requirements</h3>
        <div id=up><label class=dz>Click to upload files<input type=file id=fi multiple hidden></label>
            <p class=mu id=fl></p>
            <p class=mu style="margin-bottom:14px">Originals and signed forms must be submitted in person at the Registrar.</p>
        </div>
        <p class=mu id=nu style="margin-bottom:14px">No upload needed. Staff will tell you if anything else is required.</p>
        <h3>Fee</h3>
        <h2 id=fee></h2>
        <p class=mu style="margin-bottom:14px">Payment is recorded by the Cashier. No online payment.</p>
        <button class=bt>Submit request</button>
    </div>
</form>
<?php require '../inc/bottom.php' ?>