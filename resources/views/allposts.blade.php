<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>All Posts</title>
</head>
<body>
    <div>
        <h1>All Posts</h1>
        <button id="logout">LOG OUT</button>
        <button id="addPost">Add POst</button>
    </div>
    
    <div id="postContainer">
        <!-- Posts will be loaded here -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check for token on page load
            const token = localStorage.getItem('api_token');
            if (!token) {
                console.error("No token found");
                window.location.href = 'http://localhost:8000/api/login';
                return;
            }
            
            // Load posts
            loadData();
            
            // Logout handler
            document.getElementById("logout").addEventListener('click', function() {
                const token = localStorage.getItem('api_token');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Logout successful:", data);
                    localStorage.removeItem('api_token');
                    window.location.href = 'http://localhost:8000/api/login';
                })
                .catch(error => {
                    console.error('Logout Error:', error);
                    alert("Logout failed. Please try again.");
                });
            });
        });

        function addpost(){
            const token = localStorage.getItem("api_token");

            const post = document.querySelector("#post");

            post.addEventListener("click", function(event) {
                event.preventDefault();

                fetch(/)


            })

        }

        function loadData() {
            const token = localStorage.getItem('api_token');
            const postContainer = document.querySelector("#postContainer");
            
            // Add your code here to fetch and display posts
            // Example:
            fetch('/api/posts', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Handle displaying posts
                console.log("Posts loaded:", data);
            })
            .catch(error => console.error('Error loading posts:', error));
        }
    </script>
</body>
</html>