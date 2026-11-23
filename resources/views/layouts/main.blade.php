<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Музыкальный магазин')</title>
            
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ asset('/css/common.css') }}">
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        @yield('styles')
    
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="{{ url('/') }}" class="navbar-brand">Магазин музыкальных инструментов</a>

                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbar-collapse">
                <div class="row">
                    <div class="col-xs-12 col-sm-1">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="{{ url('/') }}">ГЛАВНАЯ</a></li>
                        </ul>
                    </div>
    
                    <div class="col-xs-12 col-sm-5">
                        <form action="{{ route('search') }}" method="GET" class="navbar-form">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="ПОИСК" value="{{ request('search') }}">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="submit">
                                        <i class="glyphicon glyphicon-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
    
                    <div class="col-xs-12 col-sm-3">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">КАТЕГОРИЯ 
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach ($categories as $category)
                                        <li>
                                            <a href="{{ route('search', ['category' => $category->Id]) }}">
                                                {{ $category->Name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li><a href="{{ route('cart') }}"><span class="glyphicon glyphicon-shopping-cart"></span> КОРЗИНА</a></li>

                            <!-- Client Menu -->
                            @auth('client')
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <span class="glyphicon glyphicon-user"></span> {{ mb_strtoupper(Auth::guard('client')->user()->Name) }}
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Мой профиль</a></li>
                                        <li><a href="#">Мои заказы</a></li>
                                        <li>
                                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-client').submit();">Выйти</a>
                                            <form id="logout-form-client" method="POST" action="{{ route('logout') }}" style="display: none;">
                                            @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @endauth

                            <!-- Employee Menu -->
                            @auth('employee')
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <span class="glyphicon glyphicon-briefcase"></span> {{ mb_strtoupper(Auth::guard('employee')->user()->Name) }}
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ route('admin.dashboard') }}">Админ панель</a></li>
                                        <li>
                                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-employee').submit();">Выйти</a>
                                            <form id="logout-form-employee" method="POST" action="{{ route('logout') }}" style="display: none;">
                                            @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @endauth

                            <!-- No User Logged In -->
                            @if(!Auth::guard('client')->check() && !Auth::guard('employee')->check())
                                <li><a href="{{ route('login') }}"><span class="glyphicon glyphicon-user"></span> ВХОД</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer-container">
        <p>Курсовой проект "Проектирование базы данных для магазина музыкальных инструментов". <br><a href="{{ route('privacy') }}">Пользовательское соглашение</a></p>
    </footer>
    
    @stack('scripts')
    </body>
</html>
