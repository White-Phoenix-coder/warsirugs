<?php
session_start();
error_reporting(0);
include 'connection.php';
if (!isset($_SESSION['ADMIN_USERID']) && $_SESSION['ADMIN_USERID'] == '') {
    header('location:index.php');
}
// multiple delete                                                              
// if (isset($_POST['checked_id']) && count($_POST['checked_id']) > 0) {
//     $all = implode(",", $_POST['checked_id']);
//     $query = "DELETE FROM `query` WHERE `id` IN($all)";
//     if ($d = $conn->exec($query)) {
//         $_SESSION['success'] = 'Query has been deleted successfully.';
//     }
//  else {
//     $_SESSION['error'] = 'Select checkbox to delete query.';   //sabaject
// }
// }

if (isset($_POST['checked_id']) && count($_POST['checked_id']) > 0) {
    $all = implode(",", $_POST['checked_id']);
    $query = "DELETE FROM `query` WHERE `id` IN($all)";
    if ($d = $conn->exec($query)) {
        $_SESSION['success'] = 'Query has been deleted successfully.';
    }
else {
    $_SESSION['error'] = 'Select checkbox to delete Query.';
}
}

//end multiple delete

// search data by date
if (isset($_REQUEST['Search'])) {
    $txtStartDate = isset($_REQUEST['txtStartDate']) ? $_REQUEST['txtStartDate'] : '';
    $txtEndDate = isset($_REQUEST['txtEndDate']) ? $_REQUEST['txtEndDate'] : '';
    if (!empty($txtStartDate)) {
        $searchStartDate = "AND CAST(date AS DATE) >= '" . $txtStartDate . "'";
    } else {
        $searchStartDate = '';
    }
    if (!empty($txtEndDate)) {
        $searchEndDate = "AND CAST(date AS DATE) <= '" . $txtEndDate . "'";
    } else {
        $searchEndDate = '';
    }
}
// search data by date
// ------------Pagination Code Start Here -----------------//
$tbl_name = "query";
$adjacents = 3;
$queryCount = $conn->prepare("SELECT COUNT(*) as num FROM $tbl_name WHERE id!='0' $searchStartDate $searchEndDate");
$queryCount->execute();
// $queryCount->debugDumpParams();
$total_pages = $queryCount->fetch(PDO::FETCH_ASSOC);
$total_pages = $total_pages['num'];
$targetpage = "query.php";
$limit = 100;
$page = $_GET['page'];
if ($page)
    $start = ($page - 1) * $limit;
else
    $start = 0;
$sqlDataFecth = $conn->prepare("SELECT * FROM $tbl_name WHERE id!='0' $searchStartDate $searchEndDate ORDER BY `id` DESC LIMIT $start, $limit");
$sqlDataFecth->execute();
if ($page == 0)
    $page = 1;
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_pages / $limit);
$lpm1 = $lastpage - 1;
$pagination = "";
if ($lastpage > 1) {
    if ($page > 1)
        $pagination .= "<a href=\"$targetpage?page=$prev\"> << </a>";
    else
        $pagination .= " <a href=\"#\"> << </a>";
    //pages 
    if ($lastpage < 7 + ($adjacents * 2)) //not enough pages to bother breaking it up
    {
        for ($counter = 1; $counter <= $lastpage; $counter++) {
            if ($counter == $page)
                $pagination .= "<a href=\"\" class=\"active\"> $counter </a>";
            else
                $pagination .= "<a href=\"$targetpage?page=$counter\"> $counter </a>";
        }
    } elseif ($lastpage > 5 + ($adjacents * 2))  //enough pages to hide some
    {
        //close to beginning; only hide later pages
        if ($page < 1 + ($adjacents * 2)) {
            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                if ($counter == $page)
                    $pagination .= "<a href=\"\" class=\"active\"> $counter </a>";
                else
                    $pagination .= "<a href=\"$targetpage?page=$counter\"> $counter </a>";
            }
            $pagination .= "<a href=\"$targetpage?page=$lpm1\"> $lpm1 </a>";
            $pagination .= "<a href=\"$targetpage?page=$lastpage\"> $lastpage </a>";
        }
        //in middle; hide some front and some back
        elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
            $pagination .= "<a href=\"$targetpage?page=1\">1</a>";
            $pagination .= "<a href=\"$targetpage?page=2\">2</a>";
            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                if ($counter == $page)
                    $pagination .= "<a href=\"\" class=\"active\"> $counter </a>";
                else
                    $pagination .= "<a href=\"$targetpage?page=$counter\"> $counter </a>";
            }
            $pagination .= "<a href=\"$targetpage?page=$lpm1\"> $lpm1 </a>";
            $pagination .= "<a href=\"$targetpage?page=$lastpage\"> $lastpage< /a>";
        }
        //close to end; only hide early pages
        else {
            $pagination .= "<a href=\"$targetpage?page=1\"> 1 </a>";
            $pagination .= "<a href=\"$targetpage?page=2\"> 2 </a>";
            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination .= "<a href=\"\" class=\"active\"> $counter </a>";
                else
                    $pagination .= "<a href=\"$targetpage?page=$counter\"> $counter </a>";
            }
        }
    }
    //next button
    if ($page < $counter - 1)                                                          // ajax
        $pagination .= "<a href=\"$targetpage?page=$next\"> >>  </a>";
    else
        $pagination .= "<a href=\"#\"> >> </a>";
}
// ------------Pagination Code End Here ------------------//
?>
<!doctype html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php echo $adminTitle; ?>
    </title>
    <link rel="shortcut icon" href="images/favicon.png">
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/nav-core.css" rel="stylesheet" />
    <link href="css/nav-layout.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700" rel="stylesheet">
    <style>
        .pagination {
            display: inline-block;
        }

        .pagination a {
            color:  Dark lime green;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
            border: 1px solid #ddd;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }

        .btn-danger {
            margin-left: 10px !important;
        }
    </style>
</head>

<body>
    <!-- header -->
    <?php require ('header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 margin-30">
                <div class="content-home">
                    <h2>Query Us</h2>

                    <form method="POST" align ="center" class="margin-25" action="">
                        <div class="row">
                            <div class="col-md-4 marginbtm-15"><input class="form-control" type="date" name="txtStartDate"></div>
                            <div class="col-md-4 marginbtm-15"><input type="date" class="form-control" name="txtEndDate"></div>
                            <div class="col-md-2 marginbtm-15"><input type="submit" name="Search" class="btn-success btn"
                                    value="search Data"></div>
                            <!-- <div class="col-md-2"><a class="btn-success btn "
                                    href="query-exportdata.php?txtStartDate=<?php echo $_REQUEST['txtstartdate']; ?>&txtEndDate=<?php echo $_REQUEST['txtEnddate']; ?>"
                                    target="_blank">Export</a></div> -->
                        </div>
                    </form>
                    <div class="table-responsive margin-30">
                        <form name="bulk_action_form" action="query.php" method="POST"
                            onSubmit="return delete_confirm();">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th width="3%">S.No</th>
                                <th width="10%">Name</th>
                                <th width="10%">Email</th>
                                <th width="10%">Phone</th>
                                <!-- <th width="5%">View</th> -->
                                
                                <th width="10%">Date</th>
                                <th width="4%"><input type="checkbox" id="select_all" value="" /><input type="submit"
                                        class="btn-xs btn-danger" name="bulk_delete_submit" value="DELETE" /></th>
                            </tr>
                            <?php
                            $i = $start + 1;
                            while ($row = $sqlDataFecth->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['fullname']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['email']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['phone']; ?>
                                    </td>
                                    <td>
                                        <?php echo isset($row['created_at']) ? date('Y-m-d', strtotime($row['created_at'])) : ''; ?>
                                    </td>

                                     
                                    <td><input type="checkbox" name="checked_id[]" class="checkbox"
                                            value="<?php echo $row['id']; ?>" /></td>

                                     
                                </tr>
                                <?php $i++;
                            } ?>
                            <tr>
                                <td colspan="7">
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
    <div id="dataModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Query Us</h4>
                </div>
                <div class="modal-body" id="query_detail">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/bootstrap.js" type="text/javascript"></script>
    <script src="js/nav.jquery.min.js" type="text/javascript"></script>
    <script>
        $('.nav').nav();
    </script>

    
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
$(document).ready(function() {
    <?php if (isset($_SESSION['success'])): ?>
        toastr.success("<?php echo $_SESSION['success']; ?>");
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        toastr.error("<?php echo $_SESSION['error']; ?>");
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
});
</script>


    <script type="text/javascript">
        function delete_confirm() {
            if ($('.checkbox:checked').length > 0) {
                var result = confirm("Are you sure to delete selected users?");
                if (result) {
                    return true;
                } else {
                    return false;
                }
            } else {
                alert('Select at least 1 record to delete.');
                return false;
            }
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#select_all').on('click', function () {
                if (this.checked) {
                    $('.checkbox').each(function () {
                        this.checked = true;
                    });
                } else {
                    $('.checkbox').each(function () {
                        this.checked = false;
                    });
                }
            });
            $('.checkbox').on('click', function () {
                if ($('.checkbox:checked').length == $('.checkbox').length) {
                    $('#select_all').prop('checked', true);
                } else {
                    $('#select_all').prop('checked', false);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.view_data').click(function () {

                var id = $(this).attr("id");
                $.ajax({
                    url: "ajax-query.php",
                    method: "post",
                    data: {
                        Id: id
                    },
                    success: function (data) {
                        $('#query_detail').html(data);
                        $('#dataModal').modal("show");
                    }
                });
            });
        });
    </script>
</body>

</html>