@extends('index')
@section('main-active', 'active')
@section('title', '- Твой проводник в мир читов')

@section('additional-css')
    <style>
        body{
            min-width: 761px;
            overflow: initial !important;
        }
    </style>
@endsection

@section('main-container')
    <div class="bg-container lending-1 black" style="height: 600px">
        <div class="content">
            @include('pages.modules.default.main-menu-lending')
            <div class="ui container fluid" style="margin: 120px 0 0 0;">
                <div class="ui segment simple" style="padding: 0 0 0 100px">
                    <span id="lending-header">Почувствуй новый уровень игры</span>
                    @if(!@$logged)
                        <br><br><br>
                        <form id="fast-sign-up-form">
                            <input type="email" class="material" name="email" placeholder="Введите Ваш E-Mail" required>
                            @if(!env('BETA_DISABLERECAPTCHA'))
                                <br>
                                <div id="recaptcha-div"></div>
                                <br>
                            @endif
                            <button id="fast-sign-in-button" onclick="fastRegistration()" type="button">регистрация</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="bg-container lending-2">
        <div class="content">
            <div class="ui vertical stripe segment">
                <div class="ui middle aligned stackable grid container">
                    <div class="row">
                        <div class="eight wide column">
                            <h3 class="ui header">Платите только за то, что Вам нужно</h3>
                            <p>
                                Наш продукт имеет несколько конфигураций.
                                <span style="color: #bf1660"><b>Bunny Hop Pack</b></span>,
                                <span style="color: #bf1660"><b>Esp Pack</b></span>,
                                <span style="color: #bf1660"><b>Skin Changer Pack</b></span>,
                                <span style="color: #bf1660"><b>Trigger Bot Pack</b></span>,
                                <span style="color: #bf1660"><b>Streamer Pack</b></span>,
                                <span style="color: #bf1660"><b>Platinum Pack. </b></span>
                                Выберите комплект, который соответствует Вашим требованиям и не платите за ненужные Вам функции.
                            </p>
                            <h3 class="ui header">Гибко настраивайте конфигурацию</h3>
                            <p>Хотите сменить свою конфигурацию? В <a href="/dashboard" style="color: #bf1660">личном кабинете</a><br>
                                Вы можете быстро сменить пакет функций
                                <span style="color: #bf1660; text-transform: uppercase">без потери старых</span>
                            </p>
                        </div>
                        <div class="six wide right floated column">

                        </div>
                    </div>
                </div>
            </div>
            <div class="ui vertical stripe segment">
                <div class="ui equal width stackable internally grid" style="padding: 0 10px">
                    <div class="row">
                        <div class="column">
                            <div class="row">
                                <h3 style="text-align: center">Защита от античитов</h3>
                                <div class="ui center aligned list">
                                    <div class="item" style="text-align: center; font-size: 20px; margin-bottom: 5px">
                                        <b><i class="ui checkmark green icon"></i>VAC (Valve Anti Cheat)</b>
                                    </div>
                                    <div class="item" style="text-align: center; font-size: 20px; margin-bottom: 5px">
                                        <b><i class="ui checkmark green icon"></i>SMAC (Source Mode Anti Cheat)</b>
                                    </div>
                                    <div class="item" style="text-align: center; font-size: 20px; margin-bottom: 5px">
                                        <b><i class="ui checkmark green icon"></i>Matchmaking</b>
                                    </div>
                                    <div class="item" style="text-align: center; font-size: 20px; margin-bottom: 5px">
                                        <b><i class="ui checkmark green icon"></i>Faceit (no client)</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <h3>Поддержка 24/7</h3>
                            <p>
                                Наша служба поддержки готова предоставить Вам помощь по
                                <span style="color: #bf1660; text-transform: uppercase">всем</span>
                                вопросам, связанных с нашим продуктом.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
