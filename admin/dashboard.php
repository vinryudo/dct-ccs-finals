<?php 
    $title = "Dashboard"; 
    require_once '../admin/partials/header.php'; 
    require_once '../admin/partials/side-bar.php';
    require_once '../functions.php';

    $connection = db_connect();

    $subject_count_query = "SELECT COUNT(*) as subject_count FROM subjects";
    $subject_result = $connection->query($subject_count_query);
    $subject_count = 0;
    if ($subject_result && $row = $subject_result->fetch_assoc()) {
        $subject_count = $row['subject_count'];
    }
?>

<!-- Template Files here -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
    <h1 class="h2">Dashboard</h1>        
    
    <div class="row mt-5">
        <div class="col-12 col-xl-3">
            <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white border-primary">Number of Subjects:</div>
                <div class="card-body text-primary">
                    <h5 class="card-title"><?php echo htmlspecialchars($subject_count);?></h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white border-primary">Number of Students:</div>
                <div class="card-body text-success">
                    <h5 class="card-title">0</h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card border-danger mb-3">
                <div class="card-header bg-danger text-white border-danger">Number of Failed Students:</div>
                <div class="card-body text-danger">
                    <h5 class="card-title">0</h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card border-success mb-3">
                <div class="card-header bg-success text-white border-success">Number of Passed Students:</div>
                <div class="card-body text-success">
                    <h5 class="card-title">0</h5>
                </div>
            </div>
        </div>
    </div>    
</main>
<!-- Template Files here -->

<?php 
require_once '../admin/partials/footer.php';
?>