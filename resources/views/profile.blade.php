<x-header-profile :sharedData="$sharedData">
  <div class="list-group">
    @foreach ($posts as $post)
    <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
      <img class="avatar-tiny" src="{{$post->authorPost->avatar}}" />
      <strong>{{$post->title}}</strong> {{$post->created_at->format('j/n/y')}}
    </a>
    @endforeach
  </div>

</x-header-profile>