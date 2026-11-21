
<?php

require_once '../config/database.php';

$database = new Database();
$conn = $database->connect();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $salary = $_POST['salary'] ?? '';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid name and email.";

    } elseif ($salary !== '' && (!is_numeric($salary) || $salary < 0)) {

        $error = "Please enter a valid salary.";

    } else {

        try {

            $sql = "INSERT INTO employees
                    (name, email, phone, department, salary)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                $name,
                $email,
                $phone,
                $department,
                $salary === '' ? null : $salary
            ]);

            header("Location: index.php");
            exit;

        } catch (PDOException $e) {

            $error = "Unable to save employee. Check whether the email already exists.";

        }
    }
}

require_once '../includes/header.php';

?>

<div class="container">

    <h2>Add Employee</h2>

    <br>

    <div class="form-box">

        <?php if ($error): ?>
            <p style="color:red;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST">

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone">
            </div>

            <div class="form-group">
                <label>Department</label>
                <input type="text" name="department">
            </div>

            <div class="form-group">
                <label>Salary</label>
                <input type="number" name="salary" min="0" step="0.01">
            </div>

            <button type="submit" class="btn">
                Save Employee
            </button>

            <a href="index.php" class="btn delete-btn">
                Cancel
            </a>

        </form>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
