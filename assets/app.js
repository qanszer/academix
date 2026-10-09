const $=s=>document.querySelector(s),$$=s=>[...document.querySelectorAll(s)],H=document.documentElement,B=document.body.dataset.role;
const toast=m=>{const t=$('#ts');if(!t)return;t.textContent=m;t.classList.add('o');setTimeout(()=>t.classList.remove('o'),2200)};
$('#th')&&($('#th').onclick=()=>{H.dataset.t=localStorage.t=H.dataset.t=='dark'?'light':'dark'});
$('#cl')&&($('#cl').onclick=()=>localStorage.c=H.classList.toggle('col')?1:0);
const ID={student:'2021-00123',staff:'EMP-0101',admin:'ADMIN-01'};
$$('[name=role]').forEach(r=>r.onchange=()=>$('#uid').value=ID[r.value]||'');
$$('[data-go]').forEach(b=>b.onclick=e=>{e.preventDefault();const n=b.dataset.go;if(n=='done'){toast('Account activated');return setTimeout(()=>location='index.php',1000)}$$('.stp').forEach(s=>s.hidden=s.id!='s'+n)});
$$('.sw').forEach(s=>s.onchange=()=>toast('Saved'));
$$('[data-toast]').forEach(b=>b.onclick=()=>toast(b.dataset.toast));
const tc=$('#tc');if(tc){const run=(b,m,f)=>b.onclick=()=>{$('#cs').textContent='Working...';setTimeout(()=>{$('#cs').textContent='Connected';f();toast(m)},900)};
run(tc,'Connection OK',()=>$('#cm').textContent=(30+Math.random()*40|0)+' ms');run($('#sy'),'Sync complete',()=>$('#ls').textContent='Last sync: just now')}
const dt=$('#dt');if(dt&&$('#fi')){const u=()=>{const o=dt.selectedOptions[0];$('#ot').hidden=o.text!='Other';$('#up').hidden=o.dataset.u!=1;$('#nu').hidden=o.dataset.u==1;$('#fee').textContent=o.dataset.f>0?'₱'+o.dataset.f:'No fee'};dt.onchange=u;u();
$('#fi').onchange=e=>$('#fl').textContent=[...e.target.files].map(f=>f.name).join(', ');
$('#rf').onsubmit=e=>{e.preventDefault();toast('Request submitted');setTimeout(()=>location='history.php',1000)}}
const FL=['Submitted','Under Review','Processing','Approved / Cleared','Ready for Release','Completed'];
const T={'Submitted':['Under Review'],'Under Review':['Processing','Needs Correction','Rejected'],'Needs Correction':['Under Review'],'Processing':['Approved / Cleared'],'Approved / Cleared':['Ready for Release'],'Ready for Release':['Completed']};
const PF={'Needs Correction':'f','Ready for Release':'f','Ready for Pickup':'f','Unpaid':'f','Completed':'m','Rejected':'m x','Cancelled':'m x','Not Applicable':'m','Picked Up':'m','Delivered':'m','Paid':'m'};
const pl=s=>`<span class="p ${PF[s]||''}">${s}</span>`;
const opts=(a,v)=>a.map(x=>`<option ${x==v?'selected':''}>${x}`).join('');
const ini=s=>(s.match(/\b(?!of\b)\w/gi)||[]).slice(0,2).join('').toUpperCase();
const PU=['Employment','Scholarship','Board exam','Transfer'];
const sv=p=>`<svg viewBox="0 0 24 24" width=18 height=18 fill=none stroke=currentColor stroke-width=1.9 stroke-linecap=round stroke-linejoin=round>${p}</svg>`;
const CL=sv('<path d="M21 11l-9 9a5 5 0 0 1-7-7l9-9a3.5 3.5 0 0 1 5 5l-9 9a2 2 0 0 1-3-3l8-8"/>'),SD=sv('<path d="M22 2L11 13M22 2l-7 20-4-9-9-4z"/>');
let cur,row;
const ck=(t,d)=>`<span class="chip ${d?'':'m'}">${d?'✓':'○'} ${t}</span>`;
const ctl=()=>`<div class=ct3><label>Blocking issue<select class=in id=hd><option value="">None${opts(['Outstanding balance','Pending clearance','Unresolved violation','Missing requirement'])}</select></label>
<label>Payment<select class=in onchange="cur.pay=this.value;sync()">${opts(['Unpaid','Paid','Waived'],cur.pay)}</select></label>
<label>Release<select class=in onchange="cur.dv=this.value;sync()">${opts(['Not Applicable','For Pickup','Ready for Pickup','For Delivery','In Transit','Delivered','Picked Up'],cur.dv)}</select></label></div>`;
function thread(){const r=cur,s=B=='staff',i=r.st=='Needs Correction'?1:FL.indexOf(r.st);
const bb=(me,t,w)=>`<div class="bw ${me?'me':''}"><small>${w}</small><div class=bb>${t}</div></div>`;
let h=bb(!s,`Hello, I would like to request my ${r.doc}. Purpose: ${PU[r.id.slice(3)%4]}. I attached my request letter and valid ID.`,(s?r.who:'You')+', '+r.date);
h+=FL.slice(1,i+1).map(x=>`<div class=ev>${x}</div>`).join('');
if(/Rejected|Cancelled/.test(r.st))h+=`<div class=ev>${r.st}</div>`;
if(r.rm)h+=bb(s,r.rm,s?'You':'Registrar staff');
(r.m||[]).forEach(m=>h+=bb(m.s==B,m.t,m.s==B?'You':(s?r.who:'Registrar staff')));
return h}
function draw(){const r=cur,s=B=='staff',a=s?(T[r.st]||[]).map(x=>`<button class="bt g" onclick="set('${x}')">${x=='Under Review'&&r.st=='Submitted'?'Start review':x}</button>`).join(''):r.st=='Submitted'?`<button class="bt g" onclick="set('Cancelled')">Cancel request</button>`:'';
$('#dt').innerHTML=`<div class=dc><div class=dh><span class=av>${ini(s?r.who:r.doc)}</span><div><b>${r.doc}</b><div class=mu>${s?r.who+', '+r.sid+', BS Computer Science, 3rd Year':r.id+', requested '+r.date}</div></div><span class=sp></span>${pl(r.st)}</div>
<div class=pr><span class=chip>${CL} 2 files</span>${ck('Request letter',1)}${ck('Valid ID',1)}${ck('Original form, in person',r.st!='Submitted')}</div>
${s?ctl():`<div class=pr><span class=mu>Release</span>${pl(r.dv)}<span class=mu>Payment</span>${pl(r.pay)}</div>`}</div>
<div class=th>${thread()}</div>
<div class=cm><textarea id=rm rows=2 placeholder="${s?'Write a remark to the student':'Write a message to the registrar'}"></textarea><div class=cf><button class="bt g" onclick="toast('File picker opens here')">${CL} Upload</button><span class=sp></span>${a}<button class=bt onclick=send()>Send ${SD}</button></div></div>`;
const t=$('.th');t.scrollTop=t.scrollHeight}
function sync(){row.dataset.r=JSON.stringify(cur);const e=row.querySelector('[data-k=st]');if(e)e.innerHTML=pl(cur.st)}
const msg=()=>{const m=$('#rm').value.trim();if(m)(cur.m=cur.m||[]).push({s:B,t:m});return m};
function send(){if(!msg())return toast('Write a message first');sync();draw();toast('Message sent')}
function set(x){const h=$('#hd');
if(x=='Approved / Cleared'&&h&&h.value)return toast('Cannot clear: '+h.value);
if(!msg()&&h&&h.value)(cur.m=cur.m||[]).push({s:B,t:h.value});
cur.st=x;if(x=='Ready for Release'&&cur.dv=='Not Applicable')cur.dv='Ready for Pickup';
if(x=='Completed'&&cur.dv.includes('Pickup'))cur.dv='Picked Up';
sync();draw();toast('Status: '+x)}
if($$('.li').length){
$$('.li').forEach(e=>e.onclick=()=>{$$('.li').forEach(x=>x.classList.remove('on'));e.classList.add('on');cur=JSON.parse(e.dataset.r);row=e;draw()});
const flt=()=>{const q=$('#q').value.toLowerCase(),s=$('.seg .on').dataset.f.split(',');let n=0;$$('.li').forEach(t=>{const v=t.textContent.toLowerCase().includes(q)&&(!s[0]||s.includes(JSON.parse(t.dataset.r).st));t.hidden=!v;n+=v});$('#cnt').textContent='Displaying '+(n?'1-'+n:0)+' of '+n+' results.'};
$('#q').oninput=flt;$$('.seg button').forEach(b=>b.onclick=()=>{$$('.seg button').forEach(x=>x.classList.remove('on'));b.classList.add('on');flt()});
flt();const h=location.hash.slice(1);($$('.li').find(e=>JSON.parse(e.dataset.r).id==h)||$('.li')).click()}
