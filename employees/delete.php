
<?php

require_once '../config/database.php';

$database = new Database();
$conn = $database->connect();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("Invalid employee ID");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare(
        "DELETE FROM employees WHERE id = ?"
    );

    $stmt->execute([$id]);

    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare(
    "SELECT id, name FROM employees WHERE id = ?"
);

$stmt->execute([$id]);

$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    die("Employee not found");
}

require_once '../includes/header.php';

?>

<div class="container">

    <div class="form-box">

        <h2>Delete Employee</h2>

        <p>
            Are you sure you want to delete
            <?= htmlspecialchars($employee['name']) ?>?
        </p>

        <br>

        <form method="POST">

            <button type="submit" class="btn delete-btn">
                Yes, Delete
            </button>

            <a href="index.php" class="btn">
                Cancel
            </a>

        </form>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
