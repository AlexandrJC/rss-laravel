@extends('layouts.layout')

@section('content')

  <div class="album py-5 bg-light">
    <div class="mx-auto" style="width: 150px; height:100px;">
        <h1>Новости</h1>
    </div>
    <div class="container">

      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

        @foreach($blocks as $newsblok)

        <div class="col" id="col{{$newsblok->id}}">
            <div class="card shadow-sm">
                @auth
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <a href="#" class="btn btn-sm btn-outline-secondary" onclick="editNewsBlock('{{$newsblok->id}}')">Редактировать</a>
                            <a href="#" class="btn btn-sm btn-outline-secondary" onclick="delNewsBlock('{{$newsblok->id}}')">Удалить</a>
                        </div>
                    </div>
                </div>
                @endauth
              <div id="block{{$newsblok->id}}">

              <a href="{{route('images')}}">
              @if ($newsblok->GetFirstImage()!='')
                <img src="{{$newsblok->GetFirstImage()}}"  width="100%" height="225">
              @else
                <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Замечание</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Картинки нет</text></svg>
              @endif
              </a>
              @if ($newsblok->GetAuthorText()!='')
              <div class="card-body">
                  <small class="text-muted">{{$newsblok->GetAuthorText()}}</small>
              </div>
              @endif
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
          </div>

       @endforeach

       <div class="col-sm-12">
            {{$blocks->onEachSide(2)->links()}}
       </div>


      </div>
    </div>
  </div>


  @auth

  <script>

    function closeModal() {

        $("#resultModal").modal('hide');
    };

    function saveData(){

        var images=[];

        $('#images').val().split("\n").forEach(function(item){
            console.log(item);
            var img_url=item.trim();
            if (typeof img_url !== 'undefined' && img_url !== null)  {
                images.push(img_url);
            }

        });


        data={
            id: $('#blockid').val(),
            name: $('#name').val(),
            shortlink: $('#shortlink').val(),
            description: $('#description').val(),
            author: $('#author').val() != '' ? $('#author').val() : 'нет',
            publish_date: $('#datetimepicker1').val(),
            images: JSON.stringify(images)
        };

        var url='{{ route('newsblock.update') }}';

        WorkerRequestOperation(url, data);

    }

    function WorkerRequestOperation(url, data = null)
    {
        var id;

        if(data !=null && "id" in data){
            id=data['id'];
        }

        $.ajax({

            url: url,

            type: "POST",

            data: data,

            headers: {

            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')

            },

            success: function (data) {

                $('#resultMessage').html(data);

                if (window.jQuery().datetimepicker) {

                    $('#datetimepicker1').datetimepicker({

                        format: 'YYYY-MM-DD HH:mm:ss',
                        icons: {
                            time: 'fa fa-clock-o',
                            date: 'fa fa-calendar',
                            up: 'fa fa-chevron-up',
                            down: 'fa fa-chevron-down',
                            previous: 'fa fa-chevron-left',
                            next: 'fa fa-chevron-right',
                            today: 'fa fa-check',
                            clear: 'fa fa-trash',
                            close: 'fa fa-times'
                        }
                    });
                    $('.input-group-append').click(function(){
                        $('.form-control').trigger('focus');
                    });

                }

                var btnline='<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload();">Закрыть</button>';

                if(url==="{{ route('newsblock.show') }}"){
                    btnline='<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal()">Закрыть</button> \
                             <button type="button" class="btn btn-primary" onclick="saveData()">Сохранить</button>';
                }

                if(url==="{{ route('newsblock.delete') }}"){
                    command="$('#col'+"+id+").remove();"
                    btnline='<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload();">Закрыть</button>';
                }

                $('#buttonline').html(btnline);

                $('#resultModal').modal('show');


            },

            error: function (msg) {

                alert('Ошибка');

            }

        });
    }

    function editNewsBlock(id){

        WorkerRequestOperation('{{ route('newsblock.show') }}', {id:id});

    }

    function delNewsBlock(id){

        var jscommand = "WorkerRequestOperation('{{ route('newsblock.delete') }}', {id:"+id+"})";

        var btnline='<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal()">Отмена</button>  \
                     <button type="button" class="btn btn-primary" onclick="'+jscommand+'">Удалить</button>';


        $('#resultModalLabel').html("Подтверждение удаления");

        $('#resultMessage').html($('#block'+id).html());

        $('#buttonline').html(btnline);

        $('#resultModal').modal('show');

    }



    </script>
<!-- Modal -->

<div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="resultModalLabel">Редактирование</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="resultMessage">

        </div>
        <div class="modal-footer" id="buttonline">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal()">Закрыть</button>
          <button type="button" class="btn btn-primary" onclick="parseEditForm()">Сохранить</button>
        </div>
      </div>
    </div>
</div>






  @endauth

@endsection

