    @if (count($posts) > 0) 
    @foreach ($posts as $post)
    <div class="card" style="width: 40rem">
        <div class="card-header">
            <h3 class="card-title">
                <a href="/soen341/public/posts/{{$post->id}}">
                    {{$post->title}}
                </a>
            </h3>
        </div>
        <div class="card-body">
            <a href="/soen341/public/posts/{{$post->id}}">
                <img class="card-img-top" src="/soen341/storage/app/public/images/{{$post->image}}">
            </a>
            <p class="card-text">{{$post->body}}</p>
            

        <form method="POST" action="{{ route('likeToggle') }}">
            <div class="form-group">
                @csrf            
                <input name="id" type="hidden" class="form-control" value="{{$post->id}}"/>
            </div>
            <div class="form-group">
                @csrf            
                <input name="url" type="hidden" class="form-control" value="{{Request::url()}}"/>
            </div>    
            <div class="row">
            <div class="col-md-1">
                <button type="submit" class="fa fa-thumbs-up btn btn-primary btn-sm">
                    <span class=" ml-1 badge badge-light">{{ $post->likers()->get()->count() }}     </span>
                </button>
            </div>   
            <div class="col-md-1">
                <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Liked By
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @foreach ($post->likers()->get() as $liker)
                    <a class="dropdown-item" href="/soen341/public/user/{{$liker->id}}">{{$liker->name}}</a>
                    @endforeach
                </div>
            </div>  
            </div>  


        </form>
        </div>

        
      
        </div>
        <footer class="blockquote-footer">Written by {{$post->user->name}} on {{$post->created_at}}</footer>

    </div>

    @endforeach
    @else 
        <p>No posts found!</p>
    @endif
