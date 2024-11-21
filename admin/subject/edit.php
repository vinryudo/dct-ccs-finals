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
                        <input type="text" class="form-control" id="subject_code" name="subject_code" placeholder="Subject Code" readonly>
                        <label for="subject_code">Subject Code</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="subject_name" name="subject_name" placeholder="Subject Name">
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