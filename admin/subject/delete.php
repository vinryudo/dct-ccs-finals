<?php
    $title = "Delete Subject";
    
    ob_start();

    require_once '../../functions.php';
    require_once '../partials/header.php';
    require_once '../partials/side-bar.php';

    $error_message = '';
    $success_message = '';
    
    // Ensures that it navigates back to a certain subject
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: /admin/subject/add.php");
        exit();
    }
    
    $subject_id = intval($_GET['id']);

    $connection = db_connect();
    $query = "SELECT * FROM subjects WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('i', $subject_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $subject = $result->fetch_assoc();
    
    if (!$subject) {
        $error_message = "Subject not found.";
    } else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_subject'])) {
            $delete_query = "DELETE FROM subjects WHERE id = ?";
            $delete_stmt = $connection->prepare($delete_query);
            $delete_stmt->bind_param('i', $subject_id);
    
            if ($delete_stmt->execute()) {
                header("Location: /admin/subject/add.php");
                exit();
            }
        }
    }
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <h1 class="h2">Delete Subject</h1>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($subject)): ?>
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="/admin/dashboard.php">Dashboard</a>
            <a class="breadcrumb-item" href="/admin/subject/add.php">Add Subject</a>
            <span class="breadcrumb-item active">Delete Subject</span>
        </nav>

        <div class="card mt-4">
            <div class="card-body">
                <p>Are you sure you want to delete the following subject record?</p>
                <ul>
                    <li><strong>Subject Code:</strong> <?php echo htmlspecialchars($subject['subject_code']); ?></li>
                    <li><strong>Subject Name:</strong> <?php echo htmlspecialchars($subject['subject_name']); ?></li>
                </ul>
                <form method="post">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='/admin/subject/add.php'">Cancel</button>
                    <button type="submit" name="delete_subject" class="btn btn-primary">Delete Subject Record</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php
require_once '../partials/footer.php';
?>