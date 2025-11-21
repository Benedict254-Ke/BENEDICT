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
    .wrap{display:flex;gap:20px;max-width:1400px;margin:30px auto;padding:20px}
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
    
    /* Tabs */
    .tabs{display:flex;gap:10px;margin-bottom:20px;border-bottom:1px solid rgba(255,255,255,0.05);padding-bottom:10px}
    .tab{background:transparent;border:none;color:var(--muted);padding:8px 16px;cursor:pointer;border-radius:6px}
    .tab.active{background:var(--accent);color:white}
    
    /* Content Areas */
    .tab-content{display:none}
    .tab-content.active{display:block}
    
    /* Tables */
    table{width:100%;border-collapse:collapse;margin-top:15px}
    th,td{padding:12px;text-align:left;border-bottom:1px solid rgba(255,255,255,0.03);color:var(--muted)}
    .actions a{margin-right:8px;color:var(--accent);text-decoration:none}
    
    /* Forms */
    .form-group{margin-bottom:15px}
    input,textarea,select{width:100%;padding:10px;border-radius:6px;border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.05);color:var(--white)}
    .btn{padding:10px 20px;background:var(--accent);color:white;border:none;border-radius:6px;cursor:pointer}
    .btn-danger{background:#dc3545}
    
    @media(max-width:900px){.wrap{flex-direction:column}.grid{grid-template-columns:repeat(1,1fr)}.sidebar{width:100%}}
  </style>
</head>
<body>
  <div class="wrap">
    <aside class="sidebar">
      <div class="brand">Lulu B — Admin</div>
      <div class="small">Complete Management Dashboard</div>

      <nav class="nav" aria-label="Admin navigation">
        <a href="#dashboard" class="active" onclick="showTab('dashboard')">📊 Dashboard</a>
        <a href="#messages" onclick="showTab('messages')">📨 Messages</a>
        <a href="#projects" onclick="showTab('projects')">🚀 Projects</a>
        <a href="#newsletter" onclick="showTab('newsletter')">📧 Subscribers</a>
        <a href="logout.php" style="color:#ffb4b4">🚪 Logout</a>
      </nav>
    </aside>

    <main class="main">
      <div class="topbar">
        <div>
          <h2 style="margin:0" id="pageTitle">Dashboard</h2>
          <div class="small">Welcome back, <?php echo $_SESSION['admin']; ?></div>
        </div>
        <div class="small"><?php echo date('Y-m-d H:i:s'); ?></div>
      </div>

      <!-- Dashboard Tab -->
      <div id="dashboard" class="tab-content active">
        <div class="grid">
          <div class="card">
            <h3 id="countMessages">0</h3>
            <p class="small">Total Messages</p>
          </div>
          <div class="card">
            <h3 id="countProjects">0</h3>
            <p class="small">Total Projects</p>
          </div>
          <div class="card">
            <h3 id="countSubscribers">0</h3>
            <p class="small">Newsletter Subscribers</p>
          </div>
        </div>

        <div class="card">
          <h3>Recent Messages</h3>
          <div id="recentMessages">Loading...</div>
        </div>
      </div>

      <!-- Messages Tab -->
      <div id="messages" class="tab-content">
        <div class="card">
          <h3>Contact Messages</h3>
          <div id="messagesList">Loading messages...</div>
        </div>
      </div>

      <!-- Projects Tab -->
      <div id="projects" class="tab-content">
        <div class="card">
          <h3>Add New Project</h3>
          <form id="addProjectForm">
            <div class="form-group">
              <input type="text" name="title" placeholder="Project Title" required>
            </div>
            <div class="form-group">
              <input type="text" name="demo_link" placeholder="Demo URL">
            </div>
            <div class="form-group">
              <input type="text" name="github_link" placeholder="GitHub URL">
            </div>
            <div class="form-group">
              <textarea name="description" placeholder="Project Description" rows="3"></textarea>
            </div>
            <div class="form-group">
              <input type="file" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn">Add Project</button>
            <div id="projectMessage" class="small" style="margin-top:10px"></div>
          </form>
        </div>

        <div class="card">
          <h3>Existing Projects</h3>
          <div id="projectsList">Loading projects...</div>
        </div>
      </div>

      <!-- Newsletter Tab -->
      <div id="newsletter" class="tab-content">
        <div class="card">
          <h3>Newsletter Subscribers</h3>
          <div id="subscribersList">Loading subscribers...</div>
        </div>
      </div>
    </main>
  </div>

<script>
// Tab navigation
function showTab(tabName) {
    // Update navigation
    document.querySelectorAll('.nav a').forEach(tab => {
        tab.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // Update content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });
    document.getElementById(tabName).classList.add('active');
    
    // Update page title
    const titles = {
        'dashboard': 'Dashboard',
        'messages': 'Messages',
        'projects': 'Projects', 
        'newsletter': 'Newsletter Subscribers'
    };
    document.getElementById('pageTitle').textContent = titles[tabName];
    
    // Load tab data
    if (tabName === 'messages') loadMessages();
    if (tabName === 'projects') loadProjects();
    if (tabName === 'newsletter') loadSubscribers();
}

// Load dashboard data
async function loadDashboard() {
    try {
        // Load counts
        const [messagesRes, projectsRes, subscribersRes] = await Promise.all([
            fetch('../api/get_messages.php'),
            fetch('../api/get_projects.php'),
            fetch('../api/get_newsletter.php')
        ]);
        
        const messages = await messagesRes.json();
        const projects = await projectsRes.json();
        const subscribers = await subscribersRes.json();
        
        // Update counts
        document.getElementById('countMessages').textContent = messages.length;
        document.getElementById('countProjects').textContent = projects.length;
        document.getElementById('countSubscribers').textContent = subscribers.length;
        
        // Show recent messages
        const recentHtml = messages.slice(0, 5).map(msg => `
            <div style="padding:10px; border-bottom:1px solid rgba(255,255,255,0.05)">
                <strong>${escapeHtml(msg.name)}</strong> 
                <span class="small">(${msg.email})</span>
                <div class="small">${escapeHtml(msg.subject)}</div>
            </div>
        `).join('') || '<div class="small">No messages yet</div>';
        
        document.getElementById('recentMessages').innerHTML = recentHtml;
        
    } catch (error) {
        console.error('Dashboard load error:', error);
    }
}

// Load messages
async function loadMessages() {
    try {
        const res = await fetch('../api/get_messages.php');
        const messages = await res.json();
        
        const messagesHtml = messages.map(msg => `
            <div style="padding:15px; border-bottom:1px solid rgba(255,255,255,0.05)">
                <strong>${escapeHtml(msg.name)}</strong> 
                <span class="small">${msg.email}</span>
                <div><strong>Subject:</strong> ${escapeHtml(msg.subject)}</div>
                <div><strong>Message:</strong> ${escapeHtml(msg.message)}</div>
                <div class="small">Received: ${msg.sent_at || msg.timestamp}</div>
            </div>
        `).join('') || '<div class="small">No messages yet</div>';
        
        document.getElementById('messagesList').innerHTML = messagesHtml;
    } catch (error) {
        document.getElementById('messagesList').innerHTML = '<div class="small">Error loading messages</div>';
    }
}

// Load projects
async function loadProjects() {
    try {
        const res = await fetch('../api/get_projects.php');
        const projects = await res.json();
        
        const projectsHtml = projects.map(project => `
            <div style="padding:15px; border-bottom:1px solid rgba(255,255,255,0.05); display:flex; gap:15px; align-items:start;">
                ${project.image ? `<img src="../uploads/projects/${encodeURIComponent(project.image)}" style="width:80px; height:60px; object-fit:cover; border-radius:6px;" onerror="this.style.display='none'">` : ''}
                <div style="flex:1">
                    <strong>${escapeHtml(project.title)}</strong>
                    <div class="small">${escapeHtml(project.description)}</div>
                    <div style="margin-top:8px">
                        ${project.demo_link ? `<a href="${project.demo_link}" target="_blank" style="color:var(--accent); margin-right:10px">Demo</a>` : ''}
                        ${project.github_link ? `<a href="${project.github_link}" target="_blank" style="color:var(--accent); margin-right:10px">GitHub</a>` : ''}
                        <a href="#" onclick="deleteProject('${project._id}'); return false;" style="color:#dc3545">Delete</a>
                    </div>
                </div>
            </div>
        `).join('') || '<div class="small">No projects yet</div>';
        
        document.getElementById('projectsList').innerHTML = projectsHtml;
    } catch (error) {
        document.getElementById('projectsList').innerHTML = '<div class="small">Error loading projects</div>';
    }
}

// Load subscribers
async function loadSubscribers() {
    try {
        const res = await fetch('../api/get_newsletter.php');
        const subscribers = await res.json();
        
        const subscribersHtml = subscribers.map(sub => `
            <div style="padding:10px; border-bottom:1px solid rgba(255,255,255,0.05)">
                ${escapeHtml(sub.email)}
                <span class="small">- Subscribed: ${sub.timestamp || sub.subscribed_at}</span>
            </div>
        `).join('') || '<div class="small">No subscribers yet</div>';
        
        document.getElementById('subscribersList').innerHTML = subscribersHtml;
    } catch (error) {
        document.getElementById('subscribersList').innerHTML = '<div class="small">Error loading subscribers</div>';
    }
}

// Add project
document.getElementById('addProjectForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const messageDiv = document.getElementById('projectMessage');
    messageDiv.textContent = 'Uploading...';
    
    try {
        const formData = new FormData(this);
        const res = await fetch('../api/add_project.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await res.text();
        if (result.trim() === 'success') {
            messageDiv.textContent = 'Project added successfully!';
            this.reset();
            loadProjects();
        } else {
            message