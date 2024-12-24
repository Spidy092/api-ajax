<div>
   
    @if (session('status'))
            <div>
                {{$message}}
            </div>
    @endif


    <h2>signup page</h2>

    <form action="/signup" method="post">
        @csrf
        <input type="text" name="name" id="name"><span>@error('name')
            {{$message}}
        @enderror</span>
        <input type="text" name="email" id="email"><span>@error('email')
            {{$message}}
        @enderror</span>
        <input type="text" name="password" id="password"><span>@error('password')
            {{$message}}
        @enderror</span>
        <button type="submit">submit</button>
    </form>
</div>
