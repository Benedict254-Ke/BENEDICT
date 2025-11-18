<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Messages — Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    :root{--bg:#07102a;--muted:#9aa6b2;--white:#ecf0f3;--accent:#2563eb}
    *{box-sizing:border-box;font-family:'Poppins',sans-serif}
    body{margin:0;background:linear-gradient(180deg,#021026,#061226);color:var(--white);min-height:100vh;padding:20px}
    .wrap{max-width:1100px;margin:20px auto}
    .top{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
    a.back{color:var(--muted);text-decoration:none}
    .card{background:rgba(255,255,255,0.03);padding:14px;border-radius:10px}
    table{width:100%;border-collapse:collapse;margin-top:12px}
    th,td{padding:12px;border-bottom:1px solid rgba(255,255,255,0.03);color:var(--muted);text-align:left}
    .btn{padding:8px 12px;border-radius:8px;background:var(--accent);color:#fff;text-decoration:none;font-weight:600;border:none;cursor:pointer}
    .small{font-size:13px;color:var(--muted)}
    .empty{padding:30px;text-align:center;color:var(--muted)}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="top">
      <a class="back" href="dashboard.php">&larr; Back to dashboard</a>
      <a class="btn" href="projects.php">Manage Projects</a>
    </div>

    <div class="card">
      <h2 style="margin:0 0 8px">Contact Messages</h2>
      <div id="tableWrap">Loading messages…</div>
    </div>
  </div>

<script>
async function loadMessages(){
  const t = document.getElementById('tableWrap');
  try {
    const res = await fetch('../api/get_messages.php');
    const msgs = await res.json();
    if (!msgs.length) { t.innerHTML = '<div class="empty">No messages yet.</div>'; return; }

    const rows = msgs.map(m => `<tr>
      <td><strong>${escapeHtml(m.name)}</strong><div class="small">${m.email} • ${m.sent_at}</div></td>
      <td style="max-width:380px">${escapeHtml(m.subject)}<div class="small" style="margin-top:6px">${escapeHtml(m.message)}</div></td>
    </tr>`).join('');

    t.innerHTML = `<table><thead><tr><th>From</th><th>Subject & Message</th></tr></thead><tbody>${rows}</tbody></table>`;
  } catch(e) {
    t.innerHTML = '<div class="empty">Failed to load messages.</div>';
    console.error(e);
  }
}

function escapeHtml(str){
  if (!str) return '';
  return str.replace(/[&<>"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[s]));
}

loadMessages();
</script>
</body>
</html>
