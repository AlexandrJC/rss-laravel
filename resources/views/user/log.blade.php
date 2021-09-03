@extends('layouts.layout')

@section('title')@parent:: Управление и история запросов парсера @endsection

@section('header')
    @parent
@endsection

@section('content')
  <section class="py-2 text-center container">
    <div class="row py-lg-5">
      <div class="col-lg-6 col-md-8 mx-auto">
        <h1 class="fw-light">RSS</h1>
        <p class="lead text-muted">Пример скрапинга RSS Ленты</p>
        <p>
          <a href="#" class="btn btn-primary my-2" id="start">Поставить задачу</a>
          <a href="#" class="btn btn-secondary my-2" id="stop">Остановить</a>
      </div>
    </div>
  </section>

   <div class="album py-5 bg-light">
    <div class="table-responsive" style="margin: 2% 2% 2% 2%">
        <div class="mx-auto" style="width: 300px; height:50px;">
            <h3>История запросов</h3>
        </div>
        <p>
        <table class="table table-striped table-bordered table-sm" >
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Дата/Время</th>
                <th scope="col">Url</th>
                <th scope="col">Метод запроса</th>
                <th scope="col">Код ответа</th>
              </tr>
            </thead>
            <tbody>
                @foreach($blocks as $log)
                <tr>
                    <th scope="row" rowspan="2"> {{$log->id}}</th>
                    <td>{{$log->request_time }}</td>
                    <td>{{$log->request_url }}</td>
                    <td>{{$log->request_method }}</td>
                    <td>{{$log->responce_http_code }}</td>
                </tr>
                <tr>
                    <td colspan="4"><textarea rows="5" cols="150" >{{$log->responce_body }}</textarea></td>
                </tr>


                @endforeach

            </tbody>
          </table>


       <div class="col-sm-12">
            {{$blocks->onEachSide(2)->links()}}
       </div>


      </div>
    </div>
  </div>

    <script>

    function closeModal() {

        $("#resultModal").modal('hide');
    };

    function WorkerRequestOperation(url)
    {
        $.ajax({

        url: url,

        type: "POST",


        headers: {

        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')

        },

        success: function (data) {

            var str = data['message'];

            $('#messageBody').html(str);
            $('#resultModal').modal('show');

        },

            error: function (msg) {

                alert('Ошибка');

            }

        });

    }

    $(function() {

        $('#start').on('click', function(){
            WorkerRequestOperation('{{ route('start') }}');
        });
        $('#stop').on('click', function(){
            WorkerRequestOperation('{{ route('stop') }}');
        });


    })

    </script>


    @include('layouts.modal')

@endsection

