<style>
  .sidebar {
    width: 220px;
    height: 100vh;
    background: #343a40;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 3rem;
    color: white;
  }
  .sidebar a {
    display: block;
    padding: 15px 20px;
    color: #ccc;
    text-decoration: none;
  }
  .sidebar a:hover, .sidebar a.active {
    background: #495057;
    color: #fff;
  }
  .main-content {
    margin-left: 220px;
    padding: 2rem;
  }
</style>

<div class="bg-dark text-white position-fixed vh-100 p-3" style="width: 240px;">
  <h4 class="mb-4">📚 Grading System</h4>
  <ul class="nav flex-column">
    <li class="nav-item mb-2">
      <a class="nav-link text-white" href="/grading-system/dashboard.php">🏠 Dashboard</a>
    </li>
    <li class="nav-item mb-2">
      <a class="nav-link text-white" href="/grading-system/grading/index.php">📝 Grading</a>
    </li>
    <li class="nav-item mb-2">
      <a class="nav-link text-white" href="/grading-system/packaging/index.php">📦 Packaging</a>
    </li>
    <li class="nav-item mb-2">
      <a class="nav-link text-white" href="/grading-system/transport/index.php">🚚 Transport</a>
    </li>
    <!-- Inspector Link Added -->
    <li class="nav-item mb-2">
      <a class="nav-link text-white" href="/grading-system/inspector/index.php">🕵️‍♂️ Inspectors</a>
    </li>
    <li class="nav-item mt-4">
      <a class="nav-link text-danger" href="/grading-system/auth/logout.php">🚪 Logout</a>
    </li>
  </ul>
</div>
