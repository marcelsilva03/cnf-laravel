@extends('layouts.email')
@section('title', $template['titulo'])
@section('subject', $template['assunto'])
@section('content')
    @include('partialsEmail.comunicadoDeObito')
@endsection
