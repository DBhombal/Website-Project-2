<?php
    if (!empty($_SESSION['signed']) && $_SESSION['signed'] != 'YES') {
        include ('view_StartPage.php');
        exit();
    }
?>

<!-- 
    Page to view the current forum post
    Should dynamically fill the content part of the page with the desired content
-->

<!DOCTYPE html>
<html>
    <head>
        <title>Post Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <style>
            #layout
            {
                width: 100vw;
                height: 100vh;
            }

            #layoutPost
            {
                position:relative;
                width:90vw;
                height: 95vh;
                top:5vh;
                margin-left:5vw;
                margin-right: 5vw;
                border-radius: 5px;
                border-style: solid;
                border-color: gray;
                border-width: 2px;
            }

            #pageTitle
            {
                position: absolute;
                background:Gray;
                width: 90vw;
                height: 5vh;
                border-bottom:3px black solid;
            }
            #postContent
            {
                position: absolute;
                background:SkyBlue;
                top: 5vh;
                width: 100%;
                height: 30vh;
                border-bottom: 3px black solid;
                overflow-y:auto:

            }

            #commentContent
            {
                position: absolute;
                background: beige;
                width: 100%;
                height: 30vh;
                top: 35vh;
                border-bottom: 3px black solid;
                overflow-y:auto;
            }

            #footer
            {
                position:absolute;
                background: teal;
                bottom:0;
                width: 100%;
                height: fit-content;
                top: 65vh;
            }

            h2
            {
                top:0;
                text-align:center;
                border-bottom: 1px gray solid;
            }

            #btnLogOut
            {
                bottom:0;
                width: 100%;
                height: 5%;
                position: absolute;
            }

            #btnBack
            {
                bottom:5%;
                width: 100%;
                height: 5%;
                position: absolute;
            }

            #btnAddComment
            {
                position: absolute;
                width:100%;
                height: 5%;
                bottom:10%;
            }

            .commentIndividual
            {
                border-bottom: 1px gray solid;
            }

            #inputComment
            {
                bottom: 10%;
                background: orange;
            }

            p
            {
                border-bottom: 1px gray solid;
                margin-left: 15px;
            }

            h6
            {
                white-space: nowrap;
                overflow: hidden; 
                text-overflow: ellipsis; 
                padding: 0 5px; 
            }

            #content
            {
                width: 100%;
            }

            #likePost
            {
                position:absolute;
                width:100%;
                height:5vh;
                bottom: 10vh;
            }

            #dislikePost
            {
                position: absolute;
                width:100%;
                height:5vh;
                bottom:5vh;
            }
        </style>
    </head>

    <body>
        <div id='layout'>
            <div id='layoutPost'>
                <div id='pageTitle'>                
                    <h2>View Posts</h2>
                </div>

                <div id='content'>
                    <div id='postContent'>
                        <h2 id='postTitle'>Post Content</h2>
                    </div>
                    
                    <div id='commentContent'>
                        <h2> Comments </h2>
                    </div>
                </div>
                 <div id='footer'>
                    <h2>Add Comments</h2>
                    <div class="form-group">
                    <textarea class="form-control" rows="4" id="comment" name='inputComment' placeHolder='Add Comment' style="background-color:LightSlateGray"></textarea>
                    </div>
                </div>
                <button type='button' class='btn btn-outline-primary' id='btnAddComment'>Add Comment</button>
                <form action="controller.php" method='post' id='formBack'>
                    <input type="hidden" name='page' value='PostPage'>
                    <input type='hidden' name='command' value='ReturnToMainPage'>
                    <button type='button' class='btn btn-outline-danger' id='btnBack'>Back To MainPage</button>
                </form>
                <form action="controller.php" id='formLogOut'>
                    <input type='hidden' name='page' value='PostPage'>
                    <input type='hidden' name='command' value='LogOut'>
                    <button type='button' class='btn btn-outline-danger' id='btnLogOut'>LogOut</button>
                </form>
            </div>   
        </div>
    </body>
</html>
<script>
    window.addEventListener('load', showAll);

    //Session Timer
    let timer = setTimeout(LogOut, 10000); //10000ms = 10 seconds

    //Reset timer with either keydown or mousemove
    //mousemove eventlistener
    window.addEventListener('mousemove', function() {
        clearTimeout(timer); //Clears the timer
        timer = setTimeout(LogOut, 100000000); //Resets the timer to 1 minute 
    });

    //keydown eventlistener
    window.addEventListener('keydown', function() {
        clearTimeout(timer); //Clears the timer
        timer = setTimeout(LogOut, 100000000); //Resets the timer to 1 minute 
    });


    $('#btnAddComment').click(function() 
    {
        var formData = {
            page: "PostPage",
            command: "AddComment",
            postTitle: localStorage.getItem('postTitle'),
            comment: $('#comment').val(),
            commenter: '<?php echo $_SESSION["username"]; ?>'
        };

        $.ajax({
            url: 'controller.php',
            type: 'POST',
            data: formData,
            success: function() {
                location.reload();
            }
        });
    });

    $('#btnBack').click(function() {
        $('#formBack').submit();
    });

    $('#btnLogOut').click(function() {
        $('#formLogOut').submit();
    });

    $('#likePost').click(function() {
        var formData = {
            page: "PostPage",
            command: "LikePost",
            title: localStorage.getItem('postTitle'),
            likes: localStorage.getItem('Likes'),
            dislikes: localStorage.getItem('Dislikes')
        }

        $.ajax({
        url: "controller.php",
        type: 'POST',
        data: formData,
        success: function(response) 
        {
            alert('success');
            location.reload();
        },
        error: function(xhr, status, error) 
        {
            console.log("Error: " + error);
        }
    });
});


    $('#dislikePost').click(function() {

    })



    function showAll()
    {
        showLikes();
        showPost();
        ShowComments();
    }

    function showLikes() 
    {
        var formData = {
            page: "PostPage",
            command: "ShowLikes",
            postTitle: localStorage.getItem('postTitle')
        };

        $.ajax(
        {
            url: 'controller.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);
                console.log("Success: " + data.Likes);
                console.log("Success: " + data.Dislikes);

                localStorage.setItem('Likes', data.Likes);
                localStorage.setItem('DisLikes', data.Dislikes);
            }
            
        });
        
    }

    function ShowComments()
    {
        var title = localStorage.getItem('postTitle');
        var formData = {
            page: "PostPage",
            command: "ShowComments",
            postTitle: title
        };
        $.ajax({
            url: 'controller.php',
            type: 'POST',
            data: formData,
            success: function(response)
            {               
                var data = JSON.parse(response);
                var commentHTML = '';

                if(data.length > 0)
                {
                    for(var i = 0; i < data.length; i++)
                    {
                        commentHTML = CommentsToHTML(data[i].Comment, data[i].Commenter);
                        $("#commentContent").append(commentHTML);
                    }

                }
                else
                    $('#commentContent').append("<h6> No comments yet, Be the first</h6>"); 
            }
        });
    }

    function CommentsToHTML(Comment, Commenter)
    {
        HTML = '';

        HTML += "<div class='commentIndividual'>";
        HTML += "<h6>" + Comment + "</h6>";
        HTML += "<p> By: " + Commenter + "</p>";
        HTML += "</div>"; 

        return HTML;
    }

    function showPost(likes)
    {
        // Retrieve the post data from localStorage
        var title = localStorage.getItem('postTitle');
        var content = localStorage.getItem('postContent');
        var poster = localStorage.getItem('postPoster');

        console.log("Title: " + title);
        console.log("content: " + content);
        console.log("Poster: " + poster);

        var HTML = `
                        <h2>${title}</h2>
                        <h6>${content}</h6>
                        <br>
                        <p> Posted By: ${poster}</p>
        `;

        $('#postContent').html(HTML);

        var btnHTML = '';
            btnHTML += "<button type='button' class='btn btn-outline-primary' id='likePost'>Like Post - " + localStorage.getItem('Likes') + "</button>";
            btnHTML += "<button type='button' class='btn btn-outline-danger' id='dislikePost'>DislikePost - " + localStorage.getItem('DisLikes') + "</button>";


          
        $('#postContent').append(btnHTML);
    }

    function LogOut()
    {
        document.getElementById('formLogOut').submit();
    }

</script>