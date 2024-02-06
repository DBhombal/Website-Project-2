<?php
error_reporting(E_ALL & ~E_NOTICE);

//If controller is accessed by URL instead of by form
if(empty($_POST['page']))
{
    $display_modal_window = 'no-modal';
    $error_msg = '';
    include('view_StartPage.php');
    exit();
}

require('model.php'); //To access DB

//From StartPage
if($_POST['page'] == 'StartPage')
{
    $command = $_POST['command'];
    switch($command)
    {
        case 'LogIn':
 
            if(authenticate_user($_POST['username'], $_POST['password']))
            {
                //If Credentials are valid
                session_start();
                $_SESSION['signed'] = 'YES';
                $_SESSION['username'] = $_POST['username'];
                echo "<script>localStorage.setItem('loggedInUser', '" . $_SESSION['username'] . "');</script>";
                include('view_MainPage.php'); //Routes to MainPage
            }
            else
            {
                //If Credentials are Invalid
                $display_modal_window = 'login';    //Sets modal window to be displayed when StartPage is returned
                $error_msg = "Invalid Credentials"; //Error msg will be displayed on the modal window

                include('view_StartPage.php'); //Returns to StartPage
            }
            exit();
            break;

        case 'SignUp':
            if(create_user($_POST['username'], $_POST['password'], $_POST['email']))
            {
                //If Successful
                $_display_modal_window = 'login';
                $error_msg = '';
                include('view_StartPage.php');
            }
            else
            {
                //If Unsuccessful
                $display_modal_window = 'signup';
                $error_msg = 'User Exists';
                include('view_StartPage.php');
            }
            exit();
            break;

        default:
            echo "Unknown command from StartPage<br>";
            exit();
            break;
    }
}

//From MainPage
else if($_POST['page'] == "MainPage")
{
    session_start();
    $command = $_POST['command'];

    switch($command)
    {
        case 'LogOut':
            session_reset();
            session_destroy();
            $display_modal_window = 'none';
            include('view_StartPage.php');
            exit();
            break;
        
        case 'ShowAllUsers':
            $list_all_users = showAllUsers();
            echo json_encode($list_all_users); //encode the data as JSON and return it
            exit();
            break;

        case 'SearchUsers':
            $matched_users = SearchUsersList($_POST['term']);
            echo json_encode($matched_users);

            exit();
            break;
            
        case 'ShowAllPosts':
            $list_all_posts = showAllPosts();
            echo json_encode($list_all_posts);
            exit();
            break;

        case 'SearchPostsByUser':
            $_SESSION['selectedUser'] = $_POST['clickedUser'];
            $returned_users = showPostsByUser($_SESSION['selectedUser']);
            echo json_encode($returned_users);
            exit();
            break;

        case 'SearchPosts':
            $matched_posts = searchPostList($_POST['term']);
            echo json_encode($matched_posts);
            exit();
            break;

        case 'CreatePost':

            if(!createPost($_POST['postTitle'], $_POST['postContent'], $_SESSION['username']))
            {
                //If unsuccessful
                $post_error_msg = 'Post Title Taken';
                echo "showCreatePostModal();";
            }
            else
            {
                //Now we update the post list
                $list_all_posts = showAllPosts();
                echo json_encode($list_all_posts);
            }
            exit();
            break;
            
        case 'DeletePost':
            deletePost($_POST['postTitle']);
            exit();
            break;

        case 'EditPost':
			$result = editPost($_POST['postTitle'], $_POST['newTitle'], $_POST['newContent']);

            if(!$result)
                $edit_error_msg = "Post Title Taken";
            
            exit();
            break;

        case 'ViewPost':
            $_SESSION['openPost'] = $_POST['postTitle'];
            $postInfo = viewClickedPost($_POST['postTitle']);

            echo "<script>localStorage.setItem('postTitle', '" . $postInfo['Title'] . "');</script>";
            echo "<script>localStorage.setItem('postContent', '" . $postInfo['Content'] . "');</script>";
            echo "<script>localStorage.setItem('postPoster', '" . $postInfo['Poster'] . "');</script>";

            include('view_PostPage.php');
            exit();

            break;



        default:
            echo "Unknown command from MainPage";
    }
}
//From PostPage
else if($_POST['page'] == 'PostPage')
{
    $command = $_POST['command'];
    switch($command)
    {
        case 'LogOut':
            session_reset();
            session_destroy();
            $display_modal_window = 'none';
            include('view_StartPage.php');
            exit();
            break;

        case 'AddComment':
            addCommentToPost($_POST['postTitle'], $_POST['comment'], $_POST['commenter']);
            exit();
            break;

        case 'ShowComments':
            $comments = viewCommentsOnPost($_POST['postTitle']);
            echo json_encode($comments);
            exit();
            break;

        case 'ShowLikes':
            $arr = viewPostLikes($_POST['postTitle']);
            echo json_encode($arr);

            exit();
            break;

        case 'LikePost':

            if (isset($_COOKIE['voted']) && $_COOKIE['voted'] == 'yes') {
                // User has already voted
                setcookie($_POST['postTitle'], '', time() + 3600);

                if($_COOKIE[$_POST['postTitle']] == 'dislike')
                {
                    //Remove Dislike, then Like
                    removeDislike($_POST['title'], $_POST['dislikes']);
                    LikePost($_POST['title'], $_POST['likes']);

                    //Update Cookie
                    setcookie($_POST['postTitle'], 'like', time() + 3600);
                }
                else
                {
                    //Unlike Post
                    removeLike($_POST['title'], $_POST['likes']);

                    setcookie($_POST['postTitle'], '', time() + 3600);
                    setcookie('voted', 'no', time() + 3600);
                }


            } else 
            {
                // User has not voted yet
                LikePost($_POST['title'], $_POST['likes']);

                setcookie($_POST['postTitle'], 'like', time() + 3600);
                setcookie($_POST['voted'], 'yes', time() + 3600);
            }
            exit();
            break;

        case 'DislikePost': 

            exit();
            break;

        case 'ReturnToMainPage':
            include('view_MainPage.php');
            exit();
            break;
    }
}

else
    echo "Unknown command from an unknown page";

?>