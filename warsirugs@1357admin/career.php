<?php
session_start();
error_reporting(0);
include 'connection.php';

if (!isset($_SESSION['ADMIN_USERID']) || $_SESSION['ADMIN_USERID'] == '') {
    header('location:index.php');
}

// Multiple delete
if (isset($_POST['checked_id']) && count($_POST['checked_id']) > 0) {
    $all = implode(",", $_POST['checked_id']);
    $query = "DELETE FROM `query` WHERE `id` IN($all)";
    if ($d = $conn->exec($query)) {
        $_SESSION['success'] = 'Query has been deleted successfully.';
        header('Location: career.php'); // Redirect after successful deletion
        exit; // Ensure script stops executing
    }
} else {
    $_SESSION['error'] = 'Select checkbox to delete query.';
}

// Search data by date
if (isset($_REQUEST['Search'])) {
    $txtStartDate = isset($_REQUEST['txtStartDate']) ? $_REQUEST['txtStartDate'] : '';
    $txtEndDate = isset($_REQUEST['txtEndDate']) ? $_REQUEST['txtEndDate'] : '';
    $searchStartDate = !empty($txtStartDate) ? "AND CAST(date AS DATE) >= '$txtStartDate'" : '';
    $searchEndDate = !empty($txtEndDate) ? "AND CAST(date AS DATE) <= '$txtEndDate'" : '';
}

// Pagination Code Start Here
$tbl_name = "query";
$adjacents = 3;
$queryCount = $conn->prepare("SELECT COUNT(*) as num FROM $tbl_name WHERE id!='0' $searchStartDate $searchEndDate");
$queryCount->execute();
$total_pages = $queryCount->fetch(PDO::FETCH_ASSOC)['num'];
$targetpage = "career.php";
$limit = 100;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$sqlDataFecth = $conn->prepare("SELECT * FROM $tbl_name WHERE id!='0' $searchStartDate $searchEndDate ORDER BY `id` DESC LIMIT $start, $limit");
$sqlDataFecth->execute();

// Pagination logic
$lastpage = ceil($total_pages / $limit);
$pagination = "";
if ($lastpage > 1) {
    // Previous button
    $prev = $page > 1 ? $page - 1 : 1;
    $pagination .= $page > 1 ? "<a href=\"$targetpage?page=$prev\"> << </a>" : "<a href=\"#\"> << </a>";

    // Page links
    for ($counter = 1; $counter <= $lastpage; $counter++) {
        $pagination .= $counter == $page ? "<a href=\"#\" class=\"active\"> $counter </a>" : "<a href=\"$targetpage?page=$counter\"> $counter </a>";
    }

    // Next button
    $next = $page < $lastpage ? $page + 1 : $lastpage;
    $pagination .= $page < $lastpage ? "<a href=\"$targetpage?page=$next\"> >> </a>" : "<a href=\"#\"> >> </a>";
}
// Pagination Code End Here
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $adminTitle; ?></title>
    <link rel="shortcut icon" href="./images/favicon.png">
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/nav-core.css" rel="stylesheet" />
    <link href="css/nav-layout.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700" rel="stylesheet">
    <style>
        .pagination { display: inline-block; }
        .pagination a { color: Dark lime green; float: left; padding: 8px 16px; text-decoration: none; border: 1px solid #ddd; }
        .pagination a.active { background-color: #4CAF50; color: white; border: 1px solid #4CAF50; }
        .pagination a:hover:not(.active) { background-color: #ddd; }
        .btn-danger { margin-left: 10px !important; }
    </style>
</head>
<body>
    <!-- header -->
    <?php require ('header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 margin-30">
                <div class="content-home">
                    <h2>Query</h2>
                    <form method="POST" align="center" class="margin-25" action="">
                        <div class="row">
                            <div class="col-md-4 marginbtm-15"><input class="form-control" type="date" name="txtStartDate"></div>
                            <div class="col-md-4 marginbtm-15"><input type="date" class="form-control" name="txtEndDate"></div>
                            <div class="col-md-2 marginbtm-15"><input type="submit" name="Search" class="btn-success btn" value="Search Data"></div>
                        </div>
                    </form>
                    <div class="table-responsive margin-30">
                        <form name="bulk_action_form" action="career.php" method="POST" onSubmit="return delete_confirm();">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th width="3%">S.No</th>
                                    <th width="10%">Name</th>
                                    <th width="10%">Email</th>
                                    <th width="10%">Phone</th>
                               
                                    <th width="15%">Message</th>
                                    <th width="10%">Date</th>
                                    <th width="4%"><input type="checkbox" id="select_all" /> <input type="submit" class="btn-xs btn-danger" name="bulk_delete_submit" value="DELETE" /></th>
                                </tr>
                                <?php
                                $i = $start + 1;
                                while ($row = $sqlDataFecth->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['number']; ?></td>
                                        <td><?php echo $row['message']; ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
                                        <td><input type="checkbox" name="checked_id[]" class="checkbox" value="<?php echo $row['id']; ?>" /></td>
                                    </tr>
                                <?php $i++; } ?>
                                <tr>
                                    <td colspan="9">
                                        <div class="pagination">
                                            <?php echo $pagination; ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
        function delete_confirm() {
            if ($('.checkbox:checked').length > 0) {
                return confirm("Are you sure to delete selected users?");
            } else {
                alert('Select at least 1 record to delete.');
                return false;
            }
        }
        $(document).ready(function () {
            $('#select_all').on('click', function () {
                $('.checkbox').prop('checked', this.checked);
            });
            $('.checkbox').on('click', function () {
                $('#select_all').prop('checked', $('.checkbox:checked').length === $('.checkbox').length);
            });
        });
    </script>
</body>
</html>
