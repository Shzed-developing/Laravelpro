@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills card-header-pills" style="padding: 0">
                        <li class="nav-item">
                            <a href="{{ route('Profile') }}" class="nav-link {{ request()->is('Profile') ? 'active' : '' }}">پروفایل</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('Profile.2fa.manage') }}" class="nav-link {{ request()->is('Profile/twofactor') ? 'active' : '' }}">احراز هویت دو مرحله ای</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('Profile.orders') }}" class="nav-link {{ request()->is('Profile/orders') ? 'active' : '' }}">سفارشات</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    @yield('main')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
