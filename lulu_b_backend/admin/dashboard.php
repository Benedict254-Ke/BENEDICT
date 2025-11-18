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
  <title>Dashboard — Lulu B Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    :root{--bg:#071020;--card:#071827;--accent:#2563eb;--muted:#9aa6b2;--white:#ecf0f3}
    *{box-sizing:border-box;font-family:'Poppins',sans-serif}
    body{margin:0;background:linear-gradient(180deg,#021026, #061226);color:var(--white);min-height:100vh}
    .wrap{display:flex;gap:20px;max-width:1200px;margin:30px auto;padding:20px}
    .sidebar{width:260px;background:rgba(255,255,255,0.03);border-radius:12px;padding:20px}
    .brand{font-weight:700;font-size:18px;margin-bottom:8px}
    .nav{margin-top:18px}
    .nav a{display:block;padding:10px;border-radius:8px;color:var(--muted);text-decoration:none;margin-bottom:6px}
    .nav a.active, .nav a:hover{background:rgba(255,255,255,0.02);color:var(--white)}
    .main{flex:1}
    .topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}
    .card{background:rgba(255,255,255,0.03);padding:18px;border-radius:12px;margin-bottom:16px}
    .grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
    .small{color:var(--muted);font-size:13px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;text-align:left;border-bottom:1px solid rgba(255,255,255,0.03);color:var(--muted)}
    .actions a{margin-right:8px;color:var(--accent);text-decoration:none}
    @media(max-width:900px){.wrap{flex-direction:column}.grid{grid-template-columns:repeat(1,1fr)}.sidebar{width:100%}}
  </style>
</head>
<body>
  <div class="wrap">
    <aside class="sidebar">
      <div class="brand">Lulu B — Admin</div>
      <div class="small">Manage messages, projects & subscribers</div>

      <nav class="nav" aria-label="Admin navigation">
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="messages.php">Messages</a>
        <a href="projects.php">Projects</a>
        <a href="newsletter.php">Subscribers</a> <!-- ✅ NEW BUTTON -->
        <a href="logout.php" style="color:#ffb4b4">Logout</a>
      </nav>
    </aside>

    <main class="main">
      <div class="topbar">
        <div>
          <h2 style="margin:0">Dashboard</h2>
          <div class="small">Welcome back, Admin.</div>
        </div>
        <div class="small">Logged in as admin</div>
      </div>

      <!-- ✅ Dashboard Cards -->
      <div class="grid">
        <div class="card">
          <h3 id="countMessages">Messages</h3>
          <p class="small">Total contact messages</p>
        </div>

        <div class="card">
          <h3 id="countProjects">Projects</h3>
          <p class="small">Total projects</p>
        </div>

        <div class="card">
          <h3 id="countSubscribers">Subscribers</h3>
          <p class="small">Newsletter subscribers</p>
        </div>
      </div>

      <!-- ✅ Recent Messages -->
      <div class="card" style="margin-top:10px">
        <h3 style="margin-top:0">Recent Messages</h3>
        <div id="recentList">Loading…</div>
      </div>
    </main>
  </div>

<script>
async function fetchCounts(){
  try {
    // ✅ Get messages
    const msgRes = await fetch('../api/get_messages.php');
    const msgs = await msgRes.json();
    document.getElementById('countMessages').textContent = msgs.length;

    let latest = document.getElementById('latestText');
if (latest) {
    latest.textContent = msgs.length ? 
        msgs[0].name + ' — ' + msgs[0].subject : 'No messages yet';
}

    // ✅ Get projects
    const projRes = await fetch('../api/get_projects.php');
    const projects = await projRes.json();
    document.getElementById('countProjects').textContent = projects.length;

    // ✅ Get newsletter subscribers
    const subRes = await fetch('../api/get_newsletter.php');
    const subscribers = await subRes.json();
    document.getElementById('countSubscribers').textContent = subscribers.length;

    // ✅ Recent 5 messages
    const list = msgs.slice(0,5).map(m => 
      `<div style="padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.03)">
        <strong>${m.name}</strong> — <span style="color:#9aa6b2">${m.subject}</span>
      </div>`
    ).join('');

    document.getElementById('recentList').innerHTML = list || '<div class="small">No messages yet</div>';

  } catch (err) {
    console.error(err);
    document.getElementById('recentList').innerHTML = '<div class="small">Failed to load data</div>';
  }
}

fetchCounts();
</script>
</body>
</html>
