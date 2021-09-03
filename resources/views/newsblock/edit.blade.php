<div class="container">

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="post" action="{{ route('newsblock.update') }}">
        @csrf

        <div class="form-group">
            <label for="name">Название</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $block->name }}">
        </div>
        <div class="form-group">
            <label for="shortlink">Короткая ссылка</label>
            <input type="text" class="form-control" id="shortlink" name="shortlink" value="{{ $block->shortlink }}">
        </div>
        <div class="form-group">
            <label for="description">Описание</label>
            <textarea class="form-control" id="description" name="description">{{ $block->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="author">Автор</label>
            <input type="text" class="form-control" id="author" name="author" value="{{ $block->author }}">
        </div>
        <div class="form-group">
            <label for="publish_date">Дата публикации:</label>
             <input type="text" class="form-control" id="datetimepicker1" name="publish_date" class="form-control" value="{{ $block->publish_date }}"/>

        </div>

        <div class="form-group">
            <label for="images">Картинки</label>
            <textarea class="form-control" id="images" name="images">@foreach (json_decode($block->images) as $img) {{$img}} @endforeach  </textarea>
        </div>
        <input type="hidden" id="blockid" name="id" value="{{ $block->id }}">
        <p></p>

    </form>

</div>

