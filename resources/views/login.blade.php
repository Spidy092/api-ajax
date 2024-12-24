<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
</head>
<body>
    <h3>Login</h3>
    <form id="loginForm" method="post">
        <div>
            <input type="text" name="email" id="email" placeholder="Email">
            {{-- @error('email')
                <span class="error">{{ $message }}</span>
            @enderror --}}
        </div>
        
        <div>
            <input type="password" name="password" id="password" placeholder="Password">
            {{-- @error('password')
                <span class="error">{{ $message }}</span>
            @enderror --}}
        </div>
        
        <button id="loginButton" type="submit">Submit</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#loginForm").on('submit', function (event) {
                event.preventDefault();
                
                const email = $('#email').val();
                const password = $('#password').val();
                
                $.ajax({
                    url: '/api/login',
                    type: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify({
                        email: email,
                        password: password,
                    }),
                    success: function (response) {
                        console.log("Success response:", response);
                        if (response.token) {
                            localStorage.setItem('api_token', response.token);
                            const savedToken = localStorage.getItem('api_token');
                            
                            if (savedToken) {
                                console.log("Token saved successfully:", savedToken);
                                window.location.href = 'http://localhost:8000/api/allposts';
                            } else {
                                console.error("Failed to save token");
                            }
                        } else {
                            console.error("No token in response");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error response:", xhr.responseText || error);
                        alert(xhr.responseText || "Login failed. Please try again.");
                    }
                });
            });
        });
    </script>
</body>
</html>