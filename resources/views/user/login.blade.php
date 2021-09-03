@extends('layouts.layout')

@section('title')@parent:: вход в панель управления @endsection

@section('header')
    @parent
@endsection

@section('content')

    <div class="container">
        <h1> Вход в панель управления </h1>
        <p></p>
        <form method="post" action="{{ route('login') }}">

            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <p></p>

            <button type="submit" class="btn btn-primary">Send</button>

        </form>

    </div>
@endsection


