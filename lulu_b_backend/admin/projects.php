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
  <title>Projects — Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    :root{--bg:#07102a;--muted:#9aa6b2;--white:#ecf0f3;--accent:#16a34a}
    *{box-sizing:border-box;font-family:'Poppins',sans-serif}
    body{margin:0;background:linear-gradient(180deg,#021026,#061226);color:var(--white);min-height:100vh;padding:20px}
    .wrap{max-width:1100px;margin:20px auto}
    .top{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
    .card{background:rgba(255,255,255,0.03);padding:14px;border-radius:10px;margin-bottom:12px}
    form .row{display:flex;gap:12px;flex-wrap:wrap}
    input,textarea{width:100%;padding:10px;border-radius:8px;border:1px solid rgba(255,255,255,0.04);background:transparent;color:var(--white)}
    .btn{padding:8px 12px;border-radius:8px;background:var(--accent);color:#fff;border:none;cursor:pointer}
    .projects-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:12px;margin-top:12px}
    .proj{background:rgba(255,255,255,0.02);padding:12px;border-radius:8px}
    .proj img{width:100%;height:140px;object-fit:cover;border-radius:6px;margin-bottom:8px}
    .actions a{margin-right:8px;color:#ff6b6b;text-decoration:none}
    .small{font-size:13px;color:var(--muted)}
    @media(max-width:900px){.top{flex-direction:column;gap:12px}}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="top">
      <a href="dashboard.php" class="small" style="color:#9aa6b2">&larr; Back to dashboard</a>
      <div>
        <strong>Manage Projects</strong>
      </div>
    </div>

    <div class="card">
      <h3 style="margin-top:0">Add Project</h3>
      <form id="addProjectForm" enctype="multipart/form-data">
        <div class="row">
          <input name="title" placeholder="Project title" required>
          <input name="demo_link" placeholder="Demo URL (optional)">
          <input name="github_link" placeholder="GitHub URL (optional)">
        </div>
        <div style="margin-top:8px">
          <textarea name="description" rows="4" placeholder="Short description"></textarea>
        </div>
        <div style="margin-top:8px">
          <input type="file" name="image" accept="image/*" required>
        </div>
        <div style="margin-top:10px">
          <button class="btn" type="submit">Add Project</button>
        </div>
        <div id="addMsg" style="margin-top:8px"></div>
      </form>
    </div>

    <div class="card">
      <h3 style="margin-top:0">Existing Projects</h3>
      <div id="projectsWrap">Loading…</div>
    </div>
  </div>

<script>
async function loadProjects(){
  const wrap = document.getElementById('projectsWrap');
  try {
    const r = await fetch('../api/get_projects.php');
    const projects = await r.json();
    if (!projects.length){ wrap.innerHTML = '<div class="small">No projects yet.</div>'; return; }
    wrap.innerHTML = '<div class="projects-grid">'+projects.map(p=>`
      <div class="proj">
        <img src="../uploads/projects/${encodeURIComponent(p.image)}" alt="${escapeHtml(p.title)}" onerror="this.style.display='none'"/>
        <h4 style="margin:6px 0">${escapeHtml(p.title)}</h4>
        <p class="small">${escapeHtml(p.description)}</p>
        <div style="margin-top:8px" class="actions">
          <a href="${p.demo_link||'#'}" target="_blank">Demo</a>
          <a href="${p.github_link||'#'}" target="_blank">GitHub</a>
          <a href="#" onclick="deleteProject(${p.id});return false;">Delete</a>
        </div>
      </div>
    `).join('') + '</div>';
  } catch(e){ wrap.innerHTML = '<div class="small">Failed to load projects</div>'; console.error(e); }
}

function escapeHtml(str){ if(!str) return ''; return str.replace(/[&<>"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[s])); }

document.getElementById('addProjectForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const msg = document.getElementById('addMsg');
  msg.textContent = 'Uploading...';
  const data = new FormData(e.target);
  try {
    const res = await fetch('../api/add_project.php', { method: 'POST', body: data });
    const text = await res.text();
    if (text.trim() === 'success') {
      msg.textContent = 'Project added ✓';
      e.target.reset();
      loadProjects();
    } else {
      msg.textContent = 'Failed to add project.';
    }
  } catch (err) {
    msg.textContent = 'Network error.';
    console.error(err);
  }
});

async function deleteProject(id){
  if (!confirm('Delete this project?')) return;
  try {
    const data = new FormData();
    data.append('id', id);
    const res = await fetch('../api/delete_project.php', { method: 'POST', body: data });
    const text = await res.text();
    if (text.trim() === 'success') {
      loadProjects();
    } else {
      alert('Failed to delete');
    }
  } catch(e){
    alert('Network error');
    console.error(e);
  }
}

loadProjects();
</script>
</body>
</html>
