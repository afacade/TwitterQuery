<?php
include_once "dao/Connection.php";
$db = new Connection();
if ($_SERVER['REQUEST_METHOD'] === 'GET'):
    //user table

    $query = "select * from twitter_users";
    $stmtuserlist = $db->selectData($query);

    // data table
    $query = "select * from twitter_data where text  like :all";
    $param = [
        "all" => "%{$_GET['Keyword']}%"
    ];
    $stmt = $db->selectDataParam($query, $param);

endif;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Curb your Twitter Query</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" >
</head>

<body style="background-color: #25252A;">
<div id="intro">
    <h1>Query your Twitter feed</h1>
    <h5> It's time to query that data </h5><br>
</div>


<form action="" method="GET">

    <!--    table for user registered in database-->
    <table class="table table-bordered table-dark col-md-2 usernameTable" border="1" cellpadding="0" cellspacing="0">
        <thead class="thead-light">
        <tr>
            <th>Usernames currently registered</th>
        </tr>
        </thead>
        <?php while ($row = $stmtuserlist->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $row['user_name']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table><br><hr>
    <!--end table-->
    <div class="form-group form_keyword col-md-4">
        <label for="Keyword">Enter keyword/string to search for<br> (e.g. Bitcoin | I love Bitcoin): </label>
        <textarea class="form-control" name="Keyword" id="Keyword" rows="1"></textarea>
    </div>

    <hr>

    <div class="form-group form_usernames col-md-4" >
            <label for="UsernamesKeyword">Enter usernames whose tweets you'd like to search in (separated by comma and no space)<br> (taylorswift13,elonmusk,...): </label>
            <textarea class="form-control" name="UsernamesKeyword" id="UsernamesKeyword" rows="1"></textarea>
        </div>
    <div class="col-md-12 text-center">
        <!-- <a class="btn btn-primary btn-lg customInsert" href="insert.php" role="button">INSERT</a> -->
        <a class="btn btn-primary btn-lg customBack" href="index.html" role="button">HOME</a>
        <input class="btn btn-primary btn-lg customQuery" type="submit" value="SUBMIT">
    </div>
</form>
<hr>
<!--main table for twitter data-->
<table class="table table-bordered table-dark col-md-10 usernameTable" border="1" cellpadding="0" cellspacing="0">
    <thead class="thead-light">
        <tr>
            <th>Username</th>
            <th>Tweet</th>
            <th style="width:200px">Date and time</th>
            <th>Tweet URL</th>
            <th>Media URL</th>
        </tr>
    </thead>
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $row['screen_name']; ?></td>
            <td><?= $row['text']; ?></td>
            <td><?= $row['created_at']; ?></td>
            <td><?= $row['tweet_url']; ?></td>
            <td><?= $row['media_url']; ?></td>
        </tr>
    <?php endwhile; ?>
</table>
<!--end table-->

</body>
</html>
