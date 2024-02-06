<?php
    if (!empty($_SESSION['signed']) && $_SESSION['signed'] != 'YES') {
        include ('view_StartPage.php');
        exit();
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Main Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <style>
            #layoutMain
            {
                height:100%;
                width:100%;
                background-color:SkyBlue;
            }
            #usersList
            {
                position: absolute;
                height: 90vh;
                width: 20vw;
                border-right: 1px black solid;
            }

            #postList
            {
                position: absolute;
                height: 90vh;
                width: 80vw;
                right: 0;
                border-bottom: 1px black solid;
            }

            #btnLogOut
            {
                position: absolute;
                height: 5vh;
                width: 80%;
                bottom: 0;
                right: 0;
            }

            #btnCreatePost
            {
                position: absolute;
                height: 5vh;
                width: 80%;
                bottom: 5vh;
                right:0;
            }

            #usersListHeader, #postListHeader
            {
                border-bottom: 1px solid black;
                padding-bottom: 5px;
            }

            #postListHeader > input
            {
                width: 20vw;
            }

            #usersListBody, #postListBody
            {
                border: 1px solid gray;
                border-radius: 5px;
                overflow-y: auto;
            }

            .userIndividual, .postIndividual
            {
                overflow-x: hidden;
                overflow-y: auto;
                width: 100%;
                border-bottom: 1px solid gray;
            }

            .userIndividual:hover, .postIndividual:hover
            {
                background-color: rgb(66, 142, 255);
            }

            #modal-create-post
            {
                width:80vw;
                height: 80vh;
            }

            #btnDeletePost
            {
                position: absolute;
                right:20px;
            }

            p
            {
                margin-left:15px;
            }

            .btn_btn-outline-danger
            {
                z-index: 9000;
            }
        </style>
    </head>

    <body>
        <div id=layoutMain>
            <div id="usersList">
                <div id="usersListHeader">

                    <center><h2>User List</h2></center>
                    <div id="searchUsers">
                        <!-- Form Should be sent using AJAX - Page state should not change -->
                        <form action="controller.php" method='post' id='formSearchUsers'>
                            <input type="hidden" name="page" value="MainPage">
                            <input type="hidden" name="command" value="SearchUsers">

                            <center><input type='text' name='username' placeholder='Search For Users'>
                            <button type='button' class='btn btn-outline-primary' id='btnSearchUsers'>Search Users</button></center>
                        </form>
                    </div>
                </div>

                <div id="usersListBody">
                </div>
            </div>
            
            <div id="postList">
                <div id='postListHeader'>
                    <center><h2>Posts</h2></center>
                    <div id="searchPosts">
                        <!-- Form should be sent using AJAX -->
                        <form action="controller.php" method='post' id='formSearchPosts'>
                            <input type="hidden" name="page" value="MainPage">
                            <input type="hidden" name="command" value="SearchPosts">

                            <center>
                                <input type='text' name='searchTerm' placeholder='Search Posts by Title or Poster'>
                                <button type='button' id='btnSearchPosts' class='btn btn-outline-primary'>Search Posts</button>
                            </center>
                        </form>
                    </div>
                </div>
                <div id='postListBody'>  
                
                </div>
            </div>


                <button type='button' class='btn btn-outline-primary' id='btnCreatePost'>Create Post</button>


                <form action="controller.php" method='post' id='LogOut'>
                    <input type='hidden' name='page' value='MainPage'>
                    <input type='hidden' name='command' value='LogOut'>
                    <button class="btn btn-outline-danger" id="btnLogOut">Log Out</button>
                </form>
                

        </div>

        <!-- Create Post Modal -->
        <div class='modal fade' id='modal-create-post'>
            <div class='modal-dialog modal-xl'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <center><h2>Create Post</h2></center>
                    </div>

                    <div class='modal-body'>
                        <label class='control-label' for='postTitle'>Title: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <input type='text' name='title' required>
                        <?php if(!empty($post_error_msg)) echo $post_error_msg ?>
                        <br>
                        <br>

                        <div class='input-group'>
                            <label class='control-label' for='postContent'>Post Content:&nbsp;</label>
                            <textarea class='form-control' id='postContent' rows='4' name='content' required></textarea>
                            <br>
                        </div>
                    </div>

                    <div class='modal-footer'>
                        <button type='button' class='btn btn-outline-danger' data-bs-dismiss='modal'>Cancel</button>
                        <button type='button' class='btn btn-outline-primary' id='CreatePost'>Create Post</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Post Modal -->
        <div class='modal fade' id='modal-edit-post'>
            <div class='modal-dialog modal-xl'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <center><h2>Edit Post</h2></center>
                    </div>

                    <div class='modal-body'>
                        <label class='control-label' for='postTitle'>New Title:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <input type='text' name='newTitle' required>
                        <?php if(!empty($edit_error_msg)) echo $edit_error_msg ?>
                        <br>
                        <br>

                        <div class='input-group'>
                            <label class='control-label' for="newContent">New Content:&nbsp;</label>
                            <input class='form-control input-lg' type='text' name='newContent' required>
                            <textarea class='form-control' id='postContent' rows='4' name='newContent' required></textarea>
                        </div>
                    </div>

                    <div class='modal-footer'>
                        <button type='button' class='btn btn-outline-danger' data-bs-dismiss='modal'>Cancel</button>
                        <button type='button' class='btn btn-outline-primary' id='EditPost'>Edit Post</button>
                    </div>
                </div>
            </div>
        </div>
        

    </body>
</html>

<script>

    //Session Timer
    let timer = setTimeout(LogOut, 10000); //10000ms = 10 seconds

    //Reset timer with either keydown or mousemove
    //mousemove eventlistener
    window.addEventListener('mousemove', function() {
        clearTimeout(timer); //Clears the timer
        timer = setTimeout(LogOut, 60000); //Resets the timer to 1 minute 
    });

    //keydown eventlistener
    window.addEventListener('keydown', function() {
        clearTimeout(timer); //Clears the timer
        timer = setTimeout(LogOut, 60000); //Resets the timer to 1 minute 
    });

    function LogOut()
    {
        document.getElementById('LogOut').submit();
    }

    function showCreatePostModal()
    {
        $('#modal-create-post').modal('show');
    }

    function hideCreatePostModal() 
    {
        $('#modal-create-post').modal('hide');
    }

    function showEditPostModal()
    {
        $('#modal-edit-post').modal('show');
    }

    function hideEditPostModal()
    {
        $('#modal-edit-post').modal('hide');
    }

    //Function is called when any of the users on the list are clicked
    //Background color of that user should go blue
    //Post list should also update to only posts by that user
    function SearchPostsByUser()
    {
        strPosts = '';
        var selectedUser = this.getAttribute('id');
        var formData = 
        {
            page: "MainPage",
            command: "SearchPostsByUser",
            clickedUser: this.getAttribute('id')
        };

        $.ajax({
            url: "controller.php",
            type:'POST',
            data: formData,
            success: function(response)
            {
                $('.postIndividual').unbind();
                var data = JSON.parse(response);

                if( $('#' + selectedUser).css('background-color') == 'rgb(66, 142, 255)')
                {
                    //Deselect it
                    $('.userIndividual').css('background-color', '');
                    location.reload();
                }
                else
                {
                    //Select it
                    $('.userIndividual').css('background-color', '');
                    $('#' + selectedUser).css('background-color', 'rgb(66, 142, 255)');
                }
                postsToHtml(data);
            }
        })
    }

    //Searches the list of users based on the user input
    function SearchUsersList()
    {
        var formData = {
            page: "MainPage",
            command: "SearchUsers",
            term: $('input[name=username]').val()
        }

        $.ajax({
            url: "controller.php",
            type: 'POST',
            data: formData,
            success: function(response)
            {
                $('.userIndividual').unbind();
                console.log("Unbound all userList EventListeners");
                //Update list of users to the matches
                var data = JSON.parse(response);
                usersToHtml(data);
            }
        })
    }

    //Creates the post
    function CreatePost()
    {
        alert($('#postContent').val());

        var formData = {
            page: "MainPage",
            command: "CreatePost",
            postTitle: $("input[name=title]").val(),
            postContent: $('#postContent').val()
        };

        $.ajax({
            url: "controller.php",
            type: 'POST',
            data: formData,
            success: function(response)
            {
                location.reload();
            }
        });
    }

    //Deletes the post
    function DeletePost()
    {
        var formData = 
        {
            page: "MainPage",
            command: "DeletePost",
            postTitle: this.parentElement.getAttribute('id')
        };

        $.ajax(
        {
            url: "controller.php",
            type: "POST",
            data: formData,
            success: function()
            {
                location.reload(); //Reloads the page to update postList
            }
        });
    }

    //Searching the posts
    function searchPosts()
    {
        var formData = 
        {
            page: "MainPage",
            command: "SearchPosts",
            term: $('input[name=searchTerm]').val()
        }

        $.ajax({
            url: "controller.php",
            type: 'POST',
            data: formData,
            success: function(response)
            {
                $('.postIndividual').unbind();

                var data = JSON.parse(response);
                postsToHtml(data);
            }
        })
    }

    function editPost(btnclicked)
    {
		var postTitle = $(btnclicked).closest('.postIndividual').attr('id');
        console.log(postTitle);

        var formData = {
            page: "MainPage",
            command: "EditPost",
            postTitle: postTitle,
            newTitle: $('input[name=newTitle]').val(),
            newContent: $('input[name=newContent]').val()
        }
        console.log(formData.postTitle);
        console.log(formData.newTitle);

        $.ajax({
            url: "controller.php",
            type: "post",
            data: formData,
            success: function(response)
            {
                location.reload();
            }
        })
    }
    
    function postsToHtml(data)
    {
        var loggedInUser = localStorage.getItem('loggedInUser');
        var strPosts = "";
        console.log("loggedInUser: " + loggedInUser);
        // assuming the logged-in user's name is stored in a variable called "loggedInUser"

        var postHTML = '';


        for (let i = 0; i < data.length; i++) 
        {
            const post = data[i];
            const userIsPoster = (post.Poster === loggedInUser);

            // check if the logged-in user is the poster
            
            // construct the HTML for the post with the delete and edit buttons

            
            postHTML += "<div class='postIndividual' id='" + post.Title + "' poster='" + post.Poster + "'>";
            postHTML += "<form action='controller.php' id='postViewForm" + i + "' method='post'>";
            postHTML += "<input type='hidden' name='page' value='MainPage'>";
            postHTML += "<input type='hidden' name='command' value='ViewPost'>";
            postHTML += "<input type='hidden' name='postTitle' value='" + post.Title + "'>";
            postHTML += "<h3 class='postTitle'>" + post.Title + "</h3>";
            postHTML += "</form>";
            //Only show edit and delete buttons if the user is the poster
            if(userIsPoster)
            {
                postHTML += "<button type='button' style='position:absolute; right:130px;' class='btn btn-outline-primary' id='btnEditPost" + i + "'>Edit Post</button>";
                postHTML += "<button type='button' style='position:absolute; right:20px;' class='btn btn-outline-danger' id='btnDeletePost" + i + "'>Delete Post</button>";
            }
            postHTML += "<p> By: " + post.Poster + "</p>";
            postHTML += "</div>";
        }

        $('#postListBody').html(postHTML);

        for (let i = 0; i < data.length; i++)
        {
            const post = data[i];
            const userIsPoster = (post.Poster === loggedInUser);
            // set event listeners for the delete and edit buttons
            if (userIsPoster) {
                $("#btnDeletePost" + i).click(DeletePost);
                $("#btnEditPost" + i).click(function () {
                    $('#modal-edit-post').modal('show');
                    $('#EditPost').click(function () {
                        editPost("#btnEditPost" + i);
                    });
                });
            }
            $('#' + post.Title).click(viewPost);
        }
            
    }

    function viewPost()
    {
        post = document.getElementById(this.getAttribute('id')).firstElementChild;
        document.getElementById(post.getAttribute('id')).submit();
    }

    function usersToHtml(data)
    {
        var str ="";

        //iterate over each object in the array and display the name property
        for(var i = 0; i < data.length; i++) 
        {
            str += "<div class='userIndividual'>";
            str += "<center><h3 id='" + data[i].Username + "'>" + data[i].Username + "</h3></center>";
            str += "</div>";
        }
        $('#usersListBody').html(str);

        for(i = 0; i < data.length; i++)
        {
            $('#' + data[i].Username).unbind().click(SearchPostsByUser);

            console.log("Set EventListener: " + data[i].Username);
        }
    }

    //Buttons
    $('#btnLogOut').click(LogOut);
    $('#btnSearchUsers').click(SearchUsersList);
    $('#btnCreatePost').click(showCreatePostModal);
    $('#CreatePost').click(CreatePost);
    $('#btnSearchPosts').click(searchPosts);
    

    window.addEventListener('unload', LogOut);

    //On Page Load
    window.addEventListener('load', function() 
    {
        //List All Users
        var formData = {
            page: "MainPage",
            command: "ShowAllUsers"
        };

        $.ajax({
            url: "controller.php",
            type:'POST',
            data: formData,
            success: function(response) 
            {
                var data = JSON.parse(response);
                usersToHtml(data);
            }
        });

        //List All Posts
        var postsFormData = {
            page: "MainPage",
            command: "ShowAllPosts"
        }

        $.ajax({
            url: "controller.php",
            type: 'POST',
            data: postsFormData,
            success: function(response)
            {       
                var data = JSON.parse(response);
                postsToHtml(data);
            }
        })
    });

</script>