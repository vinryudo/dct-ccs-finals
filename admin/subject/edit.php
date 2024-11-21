<?php
    $title = "Edit Subject";

    require_once '../../functions.php';
    require_once '../partials/header.php';
    require_once '../partials/side-bar.php';

    $error_message = '';
    $success_message = '';

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_subject'])) {
            $subject_name = trim($_POST['subject_name']);
            $subject_code = trim($_POST['subject_code']);

            if (empty($subject_name)) {
                $error_message = "Subject name cannot be empty.";
            } 
            elseif (empty($subject_code)) {
                $error_message = "Subject code cannot be empty.";
            } 
            else {
                $duplicate_query = "SELECT * FROM subjects WHERE (subject_name = ? OR subject_code = ?) AND id != ?";
                $duplicate_stmt = $connection->prepare($duplicate_query);
                $duplicate_stmt->bind_param('ssi', $subject_name, $subject_code, $subject_id);
                $duplicate_stmt->execute();
                $duplicate_result = $duplicate_stmt->get_result();
    
                if ($duplicate_result->num_rows > 0) {
                    $error_message = "A subject with the same name or code already exists.";
                } 
                else {
                    $update_query = "UPDATE subjects SET subject_name = ?, subject_code = ? WHERE id = ?";
                    $update_stmt = $connection->prepare($update_query);
                    $update_stmt->bind_param('ssi', $subject_name, $subject_code, $subject_id);
    
                    if ($update_stmt->execute()) {
                        header("Location: /admin/subject/add.php?message=Subject+updated+successfully");
                        exit();
                    } 
                    else {
                        $error_message = "Failed to update the subject. Please try again.";
                    }
                }
            }
        }
    }
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <h1 class="h2">Edit Subject</h1>

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
            <span class="breadcrumb-item active">Edit Subject</span>
        </nav>

        <div class="card mt-4">
            <div class="card-body">
                <form method="post">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="subject_code" name="subject_code" placeholder="Subject Code" readonly value="<?php echo htmlspecialchars($subject['subject_code']); ?>">
                        <label for="subject_code">Subject Code</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="subject_name" name="subject_name" placeholder="Subject Name" value="<?php echo htmlspecialchars($subject['subject_name']); ?>">
                        <label for="subject_name">Subject Name</label>
                    </div>
                    <div class="mb-3">
                        <button type="submit" name="update_subject" class="btn btn-primary w-100">Update Subject</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php
require_once '../partials/footer.php';
?>