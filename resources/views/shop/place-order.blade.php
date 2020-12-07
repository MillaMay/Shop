@extends('layouts.app_shop')

@section('content')

    <div class="account">
        <div class="container">
            <div class="account-top heading">
                <h2>CONFIRMATION</h2>
            </div>
            <div class="account-main">
                <div class="col-md-6 account-left">
                    <h3>Enter your contact details</h3>
                    <form action="{{ route('basket-confirm') }}" method="POST">
                        <div class="account-bottom">
                            <input placeholder="Name" type="text" required>
                            <input placeholder="Phone" type="text" required>
                            <div class="address">
                                <input type="submit" value="CONFIRM">
                            </div>
                        </div>
                        @csrf
                    </form>
                </div>
                <div class="col-md-6 account-right account-left">
                    <div class="col-md-8 account-left">
                        <h3>Create an Account</h3>
                        <p>By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.</p>
                        <a href="{{ route('register') }}">Register</a>
                    </div>
                    <div class="col-md-4 account-right account-left">
                        <h3>Existing User</h3>
                        <p></p>
                        <a href="{{ route('login') }}">Login</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    {{-- Если этим воспользуюсь, то нужно в миграциях для orders прописать для поля user_id->nullable()
    Уроки Laravel: интернет магазин ч.8: Request, Flash и Laravel: интернет магазин ч.11: Создание Middleware, Auth--}}

@endsection