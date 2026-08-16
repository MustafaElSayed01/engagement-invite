<?php
$rows = [];
$file = __DIR__ . '/../responses.jsonl';
if (is_file($file)) {
    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $item = json_decode($line, true);
        if (is_array($item)) $rows[] = $item;
    }
}
$total = count($rows);
$attending = count(array_filter($rows, fn($r) => ($r['attendance'] ?? '') === 'yes'));
$declined = $total - $attending;
$totalGuests = array_sum(array_map(fn($r) => (int)($r['guests'] ?? 0), array_filter($rows, fn($r) => ($r['attendance'] ?? '') === 'yes')));
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>RSVP | Nour El Din & Mariam</title><link rel="stylesheet" href="admin.css"></head><body>
<main class="wrap"><header><div><p>PRIVATE RSVP DASHBOARD</p><h1>ردود دعوة الخطوبة</h1></div><button onclick="location.reload()">تحديث</button></header>
<section class="stats"><div><span>إجمالي الردود</span><b><?= $total ?></b></div><div><span>سيحضرون</span><b><?= $attending ?></b></div><div><span>اعتذار</span><b><?= $declined ?></b></div><div><span>إجمالي الحضور</span><b><?= $totalGuests ?></b></div></section>
<div class="tools"><input id="search" placeholder="ابحث باسم الضيف..."><select id="filter"><option value="all">الكل</option><option value="yes">سيحضر</option><option value="no">اعتذر</option></select></div>
<section class="table-wrap"><table><thead><tr><th>الاسم</th><th>الحضور</th><th>العدد</th><th>الرسالة</th><th>وقت الرد</th></tr></thead><tbody id="rows">
<?php foreach (array_reverse($rows) as $r): ?><tr data-attendance="<?= h($r['attendance'] ?? '') ?>"><td><?= h($r['name'] ?? '') ?></td><td><span class="badge <?= ($r['attendance'] ?? '') === 'yes' ? 'yes' : 'no' ?>"><?= ($r['attendance'] ?? '') === 'yes' ? 'سيحضر' : 'اعتذر' ?></span></td><td><?= h($r['guests'] ?? 1) ?></td><td><?= h($r['message'] ?? '—') ?></td><td><?= h($r['submitted_at'] ?? '') ?></td></tr><?php endforeach; ?>
</tbody></table></section></main><script>const s=document.querySelector('#search'),f=document.querySelector('#filter');function filter(){const q=s.value.toLowerCase(),v=f.value;document.querySelectorAll('#rows tr').forEach(r=>{const okText=r.innerText.toLowerCase().includes(q),okFilter=v==='all'||r.dataset.attendance===v;r.style.display=okText&&okFilter?'':'none'})}s.oninput=filter;f.onchange=filter;</script></body></html>
