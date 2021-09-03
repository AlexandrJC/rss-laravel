@extends('layouts.layout')

@section('content')

  <div class="album py-5 bg-light">
    <div class="container">
     <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

        @foreach($blocks as $newsblok)

        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <a href="{{$newsblok->shortlink}}" class="btn btn-sm btn-outline-secondary">Редактировать</a>
                            <a href="{{$newsblok->shortlink}}" class="btn btn-sm btn-outline-secondary">Удалить</a>
                        </div>
                    </div>
                </div>
              <a href="{{route('images')}}">
              @if ($newsblok->GetFirstImage()!='')
                <img src="{{$newsblok->GetFirstImage()}}"  width="100%" height="225">
              @else
                <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Замечание</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Картинки нет</text></svg>
              @endif
              </a>
              <div class="card-body">
                <h3>{{$newsblok->name}}</h3>
                <p class="card-text">
                    {{$newsblok->description}}
                </p>
                <p>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="btn-group">
                    <a href="{{$newsblok->shortlink}}" class="btn btn-sm btn-outline-secondary">Источник</a>
                  </div>
                  <small class="text-muted">Опубликовано {{$newsblok->GetPublishDate()}}</small>
                </div>

              </div>
            </div>
          </div>

       @endforeach

       <div class="col-sm-12">
            {{$blocks->onEachSide(2)->links()}}
       </div>


      </div>
    </div>
  </div>

@endsection

