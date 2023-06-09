@if (Auth::check())
    {{-- ユーザ詳細ページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('tasks.index', Auth::user()->id) }}">{{ Auth::user()->name }}&#39;s tasks</a></li>
    <li class="divider lg:hidden"></li>

    {{-- ログアウトへのリンク --}}
    <li><a class="link link-hover" href="{{ route('tasks.index') }}" onclick="event.preventDefault();this.closest('form').submit();">Logout</a></li>
@else
    {{-- ユーザ登録ページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('register') }}">Signup</a></li>
    <li class="divider lg:hidden"></li>
    {{-- ログインページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('login') }}">Login</a></li>
@endif