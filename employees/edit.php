
<?php

require_once '../config/database.php';

$database = new Database();
$conn = $database->connect();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("Invalid employee ID");
}

$stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->execute([$id]);

$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    die("Employee not found");
}

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

            $sql = "UPDATE employees
                    SET name = ?, email = ?, phone = ?,
                        department = ?, salary = ?
                    WHERE id = ?";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                $name,
                $email,
                $phone,
                $department,
                $salary === '' ? null : $salary,
                $id
            ]);

            header("Location: index.php");
            exit;

        } catch (PDOException $e) {

            $error = "Unable to update employee. Check whether the email already exists.";

        }
    }

    $employee = array_merge($employee, [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'department' => $department,
        'salary' => $salary
    ]);
}

require_once '../includes/header.php';

?>

<div class="container">

    <h2>Edit Employee</h2>

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
                <input type="text" name="name"
                    value="<?= htmlspecialchars($employee['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"
                    value="<?= htmlspecialchars($employee['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone"
                    value="<?= htmlspecialchars($employee['phone'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Department</label>
                <input type="text" name="department"
                    value="<?= htmlspecialchars($employee['department'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Salary</label>
                <input type="number" name="salary" min="0" step="0.01"
                    value="<?= htmlspecialchars($employee['salary'] ?? '') ?>">
            </div>

            <button type="submit" class="btn">
                Update Employee
            </button>

            <a href="index.php" class="btn delete-btn">
                Cancel
            </a>

        </form>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
