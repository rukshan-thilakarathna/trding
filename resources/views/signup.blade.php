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
</head>
<body>

<main class="nk-pages" style="height: 100vh;">
    <!-- Banner @s -->
    <div style="height: 100vh;" class="header-banner bg-theme-dark">
        <div class="nk-banner">
            <div class="banner banner-mask-fix banner-fs banner-single tc-light">
                <div class="banner-wrap">
                    <div class="container">
                        <div class="row align-items-center justify-content-center gutter-vr-30px">
                            <div class="col-lg-6">
                                <div class="banner-caption wide-auto text-center text-lg-start">
                                    <form  action="{{ route('store-user') }}" method="POST">
                                        @csrf
                                        <div class="cpn-head mt-0">
                                            <h2 class="title title-xl-2 title-semibold animated" data-animate="fadeInUp" data-delay="1.35">Signup</h2>
                                        </div>
                                        <div class="cpn-text cpn-text-s1">
                                            <div>
                                                <label style="display: flex; flex-direction: column; margin-bottom: 5px;" class="animated" data-animate="fadeInUp" data-delay="1.35">
                                                    Name
                                                    <input type="text" name="name" placeholder="Enter your name" style="background:#9d9d9d; border: none; padding: 0 10px; color: white; border-radius: 3px; height: 40px; margin-top: 5px;">
                                                    @error('name')
                                                    <div style="color: red; margin-top: 5px;">{{ $message }}</div>
                                                    @enderror
                                                </label>

                                                <label style="display: flex; flex-direction: column; margin-bottom: 5px;" class="animated" data-animate="fadeInUp" data-delay="1.35">
                                                    Email
                                                    <input type="email" name="email" placeholder="Enter your email" style="background:#9d9d9d; border: none; padding: 0 10px; color: white; border-radius: 3px; height: 40px; margin-top: 5px;">
                                                    @error('email')
                                                    <div style="color: red; margin-top: 5px;">{{ $message }}</div>
                                                    @enderror
                                                </label>

                                                <label style="display: flex; flex-direction: column; margin-bottom: 5px;" class="animated" data-animate="fadeInUp" data-delay="1.35">
                                                    Password
                                                    <input type="password" name="password" placeholder="Enter your password" style="background:#9d9d9d; border: none; padding: 0 10px; color: white; border-radius: 3px; height: 40px; margin-top: 5px;">
                                                    @error('password')
                                                    <div style="color: red; margin-top: 5px;">{{ $message }}</div>
                                                    @enderror
                                                </label>

{{--                                                <label style="display: flex; flex-direction: column; margin-bottom: 5px;" class="animated" data-animate="fadeInUp" data-delay="1.35">--}}
{{--                                                    Re-enter password--}}
{{--                                                    <input type="password" name="repassword" placeholder="Re-enter your password" style="background:#9d9d9d; border: none; padding: 0 10px; color: white; border-radius: 3px; height: 40px; margin-top: 5px;">--}}
{{--                                                    @error('repassword')--}}
{{--                                                    <div style="color: red; margin-top: 5px;">{{ $message }}</div>--}}
{{--                                                    @enderror--}}
{{--                                                </label>--}}

                                            </div>
                                        </div>

                                        <div class="cpn-btns">
                                            <ul style="width: 95%" class="btn-grp animated " data-animate="fadeInUp" data-delay="1.55">
                                                <!--<li><a href="#" class="btn btn-md btn-grad btn-round">Sign up to join</a></li>-->
                                                <li  style="width: 100%"><button  style="width: 100%;border-radius: 5px;" class="btn btn-md btn-grad btn-grad-alternet btn-round">Signup</button></li>
                                            </ul>
                                        </div>
                                        <div class="cpn-social">
                                            <ul class="social">
                                                <li class="animated" data-animate="fadeInUp" data-delay="1.7"><a href="#"><em class="social-icon fab fa-twitter"></em></a></li>
                                                <li class="animated" data-animate="fadeInUp" data-delay="1.85"><a href="#"><em class="social-icon fab fa-discord"></em></a></li>
                                                <li class="animated" data-animate="fadeInUp" data-delay="1.75"><a href="#"><em class="social-icon fab fa-youtube"></em></a></li>
                                                <li class="animated" data-animate="fadeInUp" data-delay="1.75"><a href="#"><em class="social-icon fab fa-instagram"></em></a></li>
                                                <li class="animated" data-animate="fadeInUp" data-delay="1.65"><a href="#"><em class="social-icon fab fa-facebook-f"></em></a></li>
                                                <li class="animated" data-animate="fadeInUp" data-delay="1.9"><a href="#"><em class="social-icon fab fa-medium-m"></em></a></li>
                                            </ul>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div><!-- .row -->
                    </div>
                </div>
            </div>
        </div><!-- .nk-banner -->
        <div class="nk-ovm mask-c-dark shape-v mask-contain-bottom"></div>
        <!-- Place Particle Js -->
        <div id="particles-bg" class="particles-container particles-bg" data-pt-base="#00c0fa" data-pt-base-op=".3" data-pt-line="#2b56f5" data-pt-line-op=".5" data-pt-shape="#00c0fa" data-pt-shape-op=".2"></div>
    </div>
    <!-- .header-banner @e -->
</main>

<!-- JavaScript -->
<script src="{{asset('web/assets/js/jquery.bundle.js?ver=210')}}"></script>
<script src="{{asset('web/assets/js/scripts.js?ver=210')}}"></script>
<script src="{{asset('web/assets/js/charts.js?ver=210')}}"></script>
<script src="{{asset('web/assets/js/charts.js?ver=210')}}"></script>




</body>
</html>

