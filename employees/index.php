
<?php

require_once '../config/database.php';

$database = new Database();
$conn = $database->connect();

$sql = "SELECT * FROM employees ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';

?>

<div class="container">

    <div class="header">

        <h2>Employee Management</h2>

        <a href="create.php" class="btn">
            + Add Employee
        </a>

    </div>

    <div class="table-box">

        <table>

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Salary</th>
                    <th>Action</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach ($employees as $employee): ?>

                <tr>

                    <td>
                        <?= htmlspecialchars($employee['id']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($employee['name']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($employee['email']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($employee['phone'] ?? '') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($employee['department'] ?? '') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($employee['salary'] ?? '') ?>
                    </td>

                    <td>

                        <a href="edit.php?id=<?= urlencode($employee['id']) ?>"
                           class="btn edit-btn">
                            Edit
                        </a>

                        <a href="delete.php?id=<?= urlencode($employee['id']) ?>"
                           class="btn delete-btn">
                            Delete
                        </a>

                    </td>

                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
