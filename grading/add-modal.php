<!-- This can be included via AJAX or inside a Bootstrap modal -->
<form method="POST" action="add-grade.php">
  <div class="mb-3">
    <label>Grade Name</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Score</label>
    <input type="number" name="score" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-success">Add Grade</button>
</form>
