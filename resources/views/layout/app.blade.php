<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <meta charset="utf-8">
    <meta name="author" content="DTT">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{asset('web/images/favicon.png')}}">
    <!-- Site Title  -->
    <title>trading</title>
    <!-- Bundle and Base CSS -->
    <link rel="stylesheet" href="{{asset('web/assets/css/vendor.bundle.css?ver=210')}}">
    <link rel="stylesheet" href="{{asset('web/assets/css/style-dark.css?ver=210')}}">
    <!-- Extra CSS -->
    <!-- <link rel="stylesheet" href="assets/css/theme.css?ver=210"> -->

    @include('blocks.nav')

    @yield('content')
    <!-- Footer -->
    @include('blocks.footer')

