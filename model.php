<?php

$conn = mysqli_connect('localhost', 'w3mbhombal', 'w3mbhombal136', 'C354_w3mbhombal');

//Returns true if the user exists
function user_exists($u)
{
    global $conn;

    $sql = "SELECT * from projectUsers where Username='$u'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0)
        return true;
    else
        return false;
}

//SignUp Function
function create_user($u, $p, $e)
{
    global $conn;

    if(user_exists($u))
        return false;
        
    $sql = "INSERT into projectUsers values (NULL, '$u', '$p', '$e')";
    mysqli_query($conn, $sql);
    return true;
}

//Login Function
function authenticate_user($u, $p)
{
    global $conn;

    $sql = "SELECT * from projectUsers where Username='$u' and Password='$p'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0)
        return true;
    else   
        return false;

}

//Returns the list of users
function showAllUsers()
{
    global $conn;

    $sql = "SELECT * from projectUsers";
    $result = mysqli_query($conn, $sql);
    $list = [];
    while($row = mysqli_fetch_assoc($result))
        $list[] = $row;

    return $list;
}

//Searches the list of users
function searchUsersList($term)
{
    global $conn;

    $sql = "SELECT * from projectUsers where Username like '%$term%'"; //Checks for matches 
    $result = mysqli_query($conn, $sql);
    $list = []; 
    while($row = mysqli_fetch_assoc($result))
        $list[] = $row;

    return  $list;
}

function postTitleExists($title)
{
    global $conn;

    $sql = "SELECT * from projectPosts where Title='$title'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0)
        return  true;
    else
        return false;
}

function createPost($title, $content, $poster)
{
    global $conn;

    if(postTitleExists($title))
        return false;

    $sql = "INSERT INTO projectPosts Values(NULL, '$title', '$content', '$poster', 0)";
    mysqli_query($conn, $sql);
    $sqlLikes = "INSERT INTO projectLikes Values('$title', 0, 0)";
    mysqli_query($conn, $sqlLikes);

    return true;
}

function deletePost($title)
{
    global $conn;

    $sql = "DELETE from projectPosts where Title='$title'";
    mysqli_query($conn, $sql);
}

function editPost($oldTitle, $newTitle, $newContent)
{
	global $conn;

    if(postTitleExists($newTitle))
        return false;
    else
    // Get the poster of the old post
    $sqlGetPoster = "SELECT Poster from projectPosts where Title='$oldTitle'";
    $result = mysqli_query($conn, $sqlGetPoster);
    $row = mysqli_fetch_assoc($result);
    $poster = $row['Poster'];

    // Update the post
    $sqlUpdate = "UPDATE projectPosts SET Title='$newTitle', Content='$newContent', Poster='$poster' WHERE Title='$oldTitle'";
    $result = mysqli_query($conn, $sqlUpdate);

    return true;
}

function showAllPosts()
{
    global $conn;

    $sql = "SELECT * from projectPosts";
    $result = mysqli_query($conn, $sql);
    $list = [];
    while($row = mysqli_fetch_assoc($result))
        $list[] = $row;

    return $list;
}

function searchPostList($term)
{
    global $conn;

    $sql = "SELECT * from projectPosts where (Title like '%$term%') or (Poster like '%$term%')";
    $result = mysqli_query($conn, $sql);
    $list = [];
    while($row = mysqli_fetch_assoc($result))
        $list[] = $row;

    return $list;
}

function showPostsByUser($selectedUser)
{
    global $conn;

    $sql = "SELECT * from projectPosts where Poster='$selectedUser'";
    $result = mysqli_query($conn, $sql);
    $list = [];

    while($row = mysqli_fetch_assoc($result))
        $list[] = $row;

    return $list;
}

function viewClickedPost($title)
{
    global $conn;

    $sql = "SELECT * from projectPosts where title ='$title'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row;
}

function addCommentToPost($post, $comment, $commenter)
{
    global $conn;

    $sql = "INSERT INTO projectComments VALUES('$post', '$comment', '$commenter')";
    $result = mysqli_query($conn, $sql);
    return $result;
}

function viewCommentsOnPost($title)
{
    global $conn;

    $sql = "SELECT * from projectComments WHERE Post='$title'";
    $result = mysqli_query($conn, $sql);
    $list = [];

    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_assoc($result))
            $list[] = $row;

        return $list;
    }
    else
        return false;
}

function viewPostLikes($post) 
{
    global $conn;
    $sql = "SELECT * from projectLikes WHERE Post='$post'";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row;
}

function LikePost($post, $likes)
{
    global $conn;
    $sql = "UPDATE projectLikes SET Likes Value($likes + 1) WHERE Post='$post'";
    mysqli_query($conn, $sql);
    return true;
}

function removeLike($post, $likes)
{
    global $conn;
    $sql = "UPDATE projectLikes SET Likes Value($likes - 1) WHERE Post='$post'";
    mysqli_query($conn, $sql);
    return true;
}

function DislikePost($post, $dislikes)
{
    global $conn;
    $sql = "UPDATE projectLikes SET Dislikes Value($dislikes + 1) WHERE Post='$post'";
    mysqli_query($conn, $sql);
    return true;
}

function removeDislike($post, $dislikes)
{
    global $conn;
    $sql = "UPDATE projectLikes SET Dislikes Value($dislikes - 1) WHERE Post='$post'";
    mysqli_query($conn, $sql);
    return true;
}


?>