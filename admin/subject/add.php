<?php
$title = "Add a New Subject";
require_once '../partials/header.php'; 
require_once '../partials/side-bar.php';
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <h1 class="h2">Add a New Subject</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add a New Subject</li>
        </ol>
    </nav>

    <form method="post" action="">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="subject_code" name="subject_code" placeholder="Subject Code" >
            <label for="subject_code">Subject Code</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="subject_name" name="subject_name" placeholder="Subject Name">
            <label for="subject_name">Subject Name</label>
        </div>
        <div class="mb-3">
            <button type="submit" name="add_subject" class="btn btn-primary w-100">Add Subject</button>
        </div>
    </form>

    <!-- Subject List -->
    <h3 class="mt-5">Subject List</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Subject Name</th>
                <th>Option</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<?php require_once '../partials/footer.php'; ?>
