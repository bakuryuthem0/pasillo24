@extends('main')

@section('content')
{{ HTML::style('css/animation.css') }}
<div class="container containerPortada">
<div class="canvas">
    <div class="carrito">
        <div class="pepitas">
            <img src="{{ asset('images/Sin_ttulo-4-03.png') }}" class="roja">
            <img src="{{ asset('images/Sin_ttulo-4-02.png') }}" class="amarilla">
            <img src="{{ asset('images/Sin_ttulo-4-01.png') }}" class="verde">
        </div>
        <div class="marco">
            <img src="{{ asset('images/a1-01.png') }}">
        </div>
        <div class="ruedas">
            <div class="rueda1">
                <img src="{{ asset('images/a2-01.png') }}">
            </div>
            <div class="rueda2">
                <img src="{{ asset('images/a2-01.png') }}">
            </div>
        </div>
    </div>
    <div class="texto">
        <img src="{{ asset('images/a3-01.png') }}">
        <img src="{{ asset('images/a4-01.png') }}" class="texto2">
    </div>
    <img src="{{ asset('images/intro-01.png') }}" class="animBanner">
</div>
<div class="noCanvas">
    <img src="{{ asset('images/logo2.png') }}" class="noAnimationLogo">
</div>
</div>

@stop