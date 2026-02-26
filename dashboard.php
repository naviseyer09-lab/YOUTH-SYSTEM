<?php include('includes/header.php'); 

if(!isset($_SESSION['user_id'])) {
  header('Location: /youth-system/login.php');
  exit;
}

$user_role = $_SESSION['user_role'] ?? 'youth';
?>

<div class="dashboard-wrapper">
<div class="dashboard-container">
  <!-- Side Menu Bar -->
  <aside class="sidebar" id="dashboard-sidebar">
    <div class="sidebar-header">
      <h3>Menu</h3>
    </div>
    
    <nav class="sidebar-nav">
      <?php if($user_role === 'youth'): ?>
        <a href="/youth-system/jobs.php" class="sidebar-link <?php echo nav_active(['jobs.php'], $current_page); ?>">
          <span class="icon">💼</span> All Jobs
        </a>
        <a href="/youth-system/match_jobs.php" class="sidebar-link <?php echo nav_active(['match_jobs.php', 'add_skill.php'], $current_page); ?>">
          <span class="icon">🎯</span> Matched Jobs
        </a>
        <a href="/youth-system/trainings.php" class="sidebar-link <?php echo nav_active(['trainings.php'], $current_page); ?>">
          <span class="icon">📚</span> Trainings
        </a>
        <a href="/youth-system/my_enrollments.php" class="sidebar-link <?php echo nav_active(['my_enrollments.php', 'register_training.php'], $current_page); ?>">
          <span class="icon">✅</span> My Trainings
        </a>
        <a href="/youth-system/certifications.php" class="sidebar-link <?php echo nav_active(['certifications.php'], $current_page); ?>">
          <span class="icon">🏆</span> Certifications
        </a>
        
      <?php elseif($user_role === 'company'): ?>
        <a href="/youth-system/post_job.php" class="sidebar-link <?php echo nav_active(['post_job.php'], $current_page); ?>">
          <span class="icon">➕</span> Post Job
        </a>
        <a href="/youth-system/my_jobs.php" class="sidebar-link <?php echo nav_active(['my_jobs.php'], $current_page); ?>">
          <span class="icon">📋</span> My Job Posts
        </a>
        <a href="/youth-system/post_training.php" class="sidebar-link <?php echo nav_active(['post_training.php'], $current_page); ?>">
          <span class="icon">➕</span> Post Training
        </a>
        <a href="/youth-system/my_trainings.php" class="sidebar-link <?php echo nav_active(['my_trainings.php'], $current_page); ?>">
          <span class="icon">📚</span> Posted Trainings
        </a>
      <?php endif; ?>
    </nav>

    <div class="sidebar-divider"></div>

    <nav class="sidebar-nav">
      <a href="/youth-system/profile.php" class="sidebar-link <?php echo nav_active(['profile.php'], $current_page); ?>">
        <span class="icon">👤</span> Profile
      </a>
      <a href="/youth-system/logout.php" class="sidebar-link sidebar-logout">
        <span class="icon">🚪</span> Logout
      </a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="dashboard-main">
    <div class="dashboard-header">
      <button class="sidebar-toggle" id="sidebar-toggle">☰</button>
      <h1>Dashboard</h1>
      <p class="welcome-text">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?> 👋</p>
    </div>

    <section class="dashboard-content">
      <h2 style="margin-top:0;color:var(--red-800);margin-bottom:24px">Quick Actions</h2>
      
      <?php if($user_role === 'youth'): ?>
        <div class="dashboard-grid">
          <div class="dashboard-card">
            <h3>📋 Available Jobs</h3>
            <p>Browse through all available job opportunities and find positions that match your qualifications and career goals.</p>
            <a href="/youth-system/jobs.php" class="btn">Explore Opportunities</a>
          </div>

          <div class="dashboard-card">
            <h3>🎯 Matched Jobs</h3>
            <p>View personalized job recommendations based on your skills, experience, and profile information.</p>
            <a href="/youth-system/match_jobs.php" class="btn">View Your Matches</a>
          </div>

          <div class="dashboard-card">
            <h3>📚 Training Programs</h3>
            <p>Discover and enroll in training courses to develop new skills and improve your career prospects.</p>
            <a href="/youth-system/trainings.php" class="btn">Browse Trainings</a>
          </div>

          <div class="dashboard-card">
            <h3>✅ My Enrollments</h3>
            <p>Track your progress in enrolled training programs and stay updated on your development journey.</p>
            <a href="/youth-system/my_enrollments.php" class="btn">View Enrollments</a>
          </div>

          <div class="dashboard-card">
            <h3>🏆 Certifications</h3>
            <p>Access and manage all your earned certifications and professional credentials.</p>
            <a href="/youth-system/certifications.php" class="btn">View Certificates</a>
          </div>

          <div class="dashboard-card">
            <h3>👤 My Profile</h3>
            <p>Update your profile, add skills, and manage your personal information to attract better opportunities.</p>
            <a href="/youth-system/profile.php" class="btn">Edit Profile</a>
          </div>
        </div>

      <?php elseif($user_role === 'company'): ?>
        <div class="dashboard-grid">
          <div class="dashboard-card">
            <h3>➕ Post New Job</h3>
            <p>Create and publish new job openings to attract qualified candidates for your company.</p>
            <a href="/youth-system/post_job.php" class="btn">Create Job Posting</a>
          </div>

          <div class="dashboard-card">
            <h3>📋 My Job Posts</h3>
            <p>Manage, edit, and track all your current and past job postings and applications.</p>
            <a href="/youth-system/my_jobs.php" class="btn">View Job Posts</a>
          </div>

          <div class="dashboard-card">
            <h3>➕ Create Training</h3>
            <p>Develop and publish training programs to build workforce skills and capabilities.</p>
            <a href="/youth-system/post_training.php" class="btn">Add Training Program</a>
          </div>

          <div class="dashboard-card">
            <h3>📚 My Training Programs</h3>
            <p>Manage your training offerings, track enrollments, and monitor participant progress.</p>
            <a href="/youth-system/my_trainings.php" class="btn">View Programs</a>
          </div>

          <div class="dashboard-card">
            <h3>👤 Company Profile</h3>
            <p>Update your company information, description, and profile details to improve visibility.</p>
            <a href="/youth-system/profile.php" class="btn">Edit Profile</a>
          </div>

          <div class="dashboard-card">
            <h3>📊 Analytics</h3>
            <p>View insights about your job postings and training program performance.</p>
            <a href="/youth-system/profile.php" class="btn">View Analytics</a>
          </div>
        </div>
      <?php endif; ?>
    </section>
  </main>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  var sidebar = document.getElementById('dashboard-sidebar');
  var toggle = document.getElementById('sidebar-toggle');
  
  // Show toggle on mobile
  var mediaQuery = window.matchMedia('(max-width: 900px)');
  function updateToggle(e) {
    if(e.matches) {
      toggle.style.display = 'block';
    } else {
      toggle.style.display = 'none';
      sidebar.classList.remove('open');
    }
  }
  updateToggle(mediaQuery);
  mediaQuery.addListener(updateToggle);
  
  // Toggle sidebar
  toggle.addEventListener('click', function(){
    sidebar.classList.toggle('open');
  });
  
  // Close sidebar when clicking outside
  document.addEventListener('click', function(e){
    if(!sidebar.contains(e.target) && !toggle.contains(e.target) && sidebar.classList.contains('open')){
      sidebar.classList.remove('open');
    }
  });
});
</script>

<?php include('includes/footer.php'); ?>
