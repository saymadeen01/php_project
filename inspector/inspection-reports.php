<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch inspection reports from the database
$query = "SELECT * FROM inspection_reports INNER JOIN inspectors ON inspection_reports.inspector_id = inspectors.id";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$reports = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reports[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .table th {
            background-color: #f8f9fa;
            color: #495057;
        }
        .btn-custom {
            background-color: #28a745;
            color: white;
        }
        .btn-custom:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar (included) -->
        <div class="bg-dark text-white p-3" style="min-width: 250px; height: 100vh;">
            <?php include '../includes/sidebar.php'; ?>
        </div>

        <!-- Main content -->
        <div class="flex-grow-1 p-4">

        <h2 class="mb-4">Inspection Reports</h2>

        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by Inspector, Grade, or Comments">
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-custom" onclick="exportToCSV()">Export to CSV <i class="fa fa-file-csv"></i></button>
                <button class="btn btn-primary ms-2" onclick="exportToPDF()">Export to PDF <i class="fa fa-file-pdf"></i></button>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Inspection Reports List</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="reportTable">
                    <thead>
                        <tr>
                            <th>Inspector</th>
                            <th>Grade</th>
                            <th>Comments</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($reports) > 0): ?>
                            <?php foreach ($reports as $report): ?>
                                <tr>
                                    <td><?= htmlspecialchars($report['name']) ?></td>
                                    <td><?= htmlspecialchars($report['grade']) ?></td>
                                    <td><?= htmlspecialchars($report['comments']) ?></td>
                                    <td><?= htmlspecialchars($report['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No reports found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination (optional) -->
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Script for Search Filter -->
    <script>
        const searchInput = document.getElementById("searchInput");
        searchInput.addEventListener("keyup", function() {
            const filter = searchInput.value.toLowerCase();
            const rows = document.querySelector("#reportTable tbody").rows;

            Array.from(rows).forEach(row => {
                const inspector = row.cells[0].textContent.toLowerCase();
                const grade = row.cells[1].textContent.toLowerCase();
                const comments = row.cells[2].textContent.toLowerCase();
                if (inspector.includes(filter) || grade.includes(filter) || comments.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });

        // Export to CSV (using plain JavaScript)
        function exportToCSV() {
            let csvContent = "data:text/csv;charset=utf-8,";
            const rows = document.querySelectorAll("table tr");
            rows.forEach(row => {
                const cols = row.querySelectorAll("td, th");
                const rowData = Array.from(cols).map(col => col.textContent).join(",");
                csvContent += rowData + "\r\n";
            });
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "inspection_reports.csv");
            document.body.appendChild(link);
            link.click();
        }

        // Export to PDF (you can use a library like jsPDF to generate PDFs)
        function exportToPDF() {
            alert('PDF export feature is not implemented. Please integrate jsPDF or similar library.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </div> <!-- end of flex-grow-1 -->
    </div> <!-- end of d-flex -->

</body>

</html>
