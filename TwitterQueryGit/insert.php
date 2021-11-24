<?php
require_once('TwitterAPIExchange.php');
function insertTweets($user_id,$screen_name,$text,$created_at,$tweet_id,$tweet_url, $media_url){
    $mysqli = new mysqli('readacted', 'readacted', 'readacted', 'readacted'); //database name and login information
    if ($mysqli->connect_errno) {
        return 'Failed to connect to Database: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
    }
    $prepareStmt='INSERT INTO twitter_data(user_id, screen_name, text, created_at, tweet_id, tweet_url, media_url) VALUES (?,?,?,?,?,?,?);';
    if ($insert_stmt = $mysqli->prepare($prepareStmt)){
        $insert_stmt->bind_param('sssssss', $user_id,$screen_name,$text,$created_at,$tweet_id,$tweet_url, $media_url);
        if (!$insert_stmt->execute()) {
            $errormsg = $insert_stmt->error;
            $insert_stmt->close();
            echo 'Tweet ID: ' . $errormsg;
        }elseif($insert_stmt->affected_rows>0){
            $query = "INSERT INTO twitter_users(user_id, user_name) VALUES ('$user_id', '$screen_name')";
            if($mysqli->query($query) === TRUE)
            {
             echo "Yes";
            }
            else{
                echo $mysqli-> error;
            }
            $mysqli->close();
            $insert_stmt->close();
        }else{
            $insert_stmt->close();
        }
    }else{
        return 'Prepare failed: (' . $mysqli->errno . ') ' . $mysqli->error;
    }
}

$settings = array( // twitter dev tokens
    'oauth_access_token' => 'readacted',
    'oauth_access_token_secret' => 'readactedG',
    'consumer_key' => 'readacted',
    'consumer_secret' => 'readacted'
);

if ($_SERVER['REQUEST_METHOD'] === 'POST'):
    $Usernames = $_POST['UsernamesInput'];
    if(str_contains($Usernames, ",") !== false)
    {
        $UsernamesArray = explode(",", $Usernames);
    }
    else
    {
        $UsernamesArray = [$Usernames]; // when there is only one username
    }

    $TweetSelectCount = $_POST['tweetCountSelect']; //make exception case for when user didn't select count
    $twitter = new TwitterAPIExchange($settings);

    $requestMethod = 'GET';
    foreach($UsernamesArray as $username)
    {
        // $url = 'https://api.twitter.com/1.1/users/lookup.json'; // looks data of specific user, e.g. id
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfields = '?screen_name=' . $username . '&' . 'count=' . $TweetSelectCount;

        $resultSet = json_decode($twitter->setGetfield($getfields)
            ->buildOauth($url, $requestMethod)
            ->performRequest(),$assoc = TRUE); // $assoc turns resultSet into array, default turns into object

        //header('Content-Type: application/json'); echo json_encode($resultSet, JSON_PRETTY_PRINT); parse json into a more readable format

        foreach($resultSet as $items)
        {

            $created_at = gmdate('Y-m-d H:i:s', strtotime($items['created_at']));
            $tweet_url = "https://twitter.com/". $items['user']['name']."/status/" . $items['id_str'];
            if ( array_key_exists("media", $items["entities"])) {
                $media_url  = "https://twitter.com/". $items['user']['name']."/status/" . $items['id_str'] . "/photo/1";
            }
            else {
                $media_url = NULL; // if the tweet has no media, insert NULL into mysql media_url column
            }
            echo insertTweets($items['user']['id'],$items['user']['screen_name'],$items['text'],$created_at,$items['id_str'],$tweet_url, $media_url );
        }
    }
    

endif;


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Curb your Twitter Query</title>
    <link rel="stylesheet" href="css/style.css" >
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body style="background-color: #25252A;">
    <div id="intro">
        <h1>Query your Twitter feed</h1><br>
        <h5> Tweets has been inserted into the database, click on the query button to continue </h5>
    </div>


    <div class="col-md-12 text-center">
        <a class="btn btn-primary btn-lg customBack" href="index.html" role="button">BACK</a>
        <a class="btn btn-primary btn-lg customQuery" href="query.php" role="button">QUERY</a>
    </div>

</body>
</html>