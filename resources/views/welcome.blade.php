<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="author" content="Sumon Rahman" />
    <meta name="description" content="" />
    <meta name="keywords" content="HTML,CSS,XML,JavaScript" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Title -->
    <title>Vehicle Care</title>
    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon" type="image/" href="{{ asset('images/download.png') }}" />
    <!-- Plugin-CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/linearicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}" />
    <!-- Main-Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}" />
    <link rel="stylesheet" href="{{ asset('style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}" />
    <script src="{{ asset('js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <!--[if lt IE 9]>
      <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body data-spy="scroll" data-target=".mainmenu-area">
    <!-- Preloader-content -->
    <div class="preloader">
        <span><i class="lnr lnr-sun"></i></span>
    </div>
    <!-- MainMenu-Area -->
    <nav class="mainmenu-area" data-spy="affix" data-offset-top="200">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#primary_menu">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><img src="{{ asset('images/logo.png') }}" class="img-rounded"
                        alt="Logo" /></a>
            </div>
            <div class="collapse navbar-collapse" id="primary_menu">
                <ul class="nav navbar-nav mainmenu">
                    <li class="active"><a href="#home_page">Home</a></li>
                    <li><a href="#about_page">About</a></li>
                    <li><a href="#features_page">Advantages</a></li>
                    <li><a href="#contact_page">Contacts</a></li>
                </ul>
                <div class="right-button hidden-xs">
                    @if (Route::has('login'))
                        @auth
                            @if (Auth::user()->user_type == 2)
                                <a href="{{ route('home') }}">Dashboard Company</a>
                            @endif
                            @if (Auth::user()->user_type == 3)
                                <a href="{{ route('admin/home') }}">Dashboard Admin</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}">Login</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}">Register Company</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>
    <!-- MainMenu-Area-End -->
    <!-- Home-Area -->
    <header class="home-area overlay" id="home_page">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 hidden-sm col-md-5">
                    <figure class="mobile-image wow fadeInUp" data-wow-delay="0.2s"></figure>
                </div>
                <div class="col-xs-12 col-md-7">
                    <div class="space-80 hidden-xs"></div>
                    <h1 class="wow fadeInUp" data-wow-delay="0.4s">
                        Mulai melakukan online booking di Vehicle Care.
                    </h1>
                    <div class="space-20"></div>
                    <div class="desc wow fadeInUp" data-wow-delay="0.6s">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiing elit, sed do
                            eiusmod tempor incididunt ut labore et laborused sed do eiusmod
                            tempor incididunt ut labore et laborused.
                        </p>
                    </div>
                </div>
            </div>
    </header>
    <!-- Home-Area-End -->
    <!-- About-Area -->
    <section class="section-padding" id="about_page">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <div class="page-title text-center">
                        <img src="{{ asset('images/about-logo.png') }}" width="10%" alt="About Logo" />
                        <div class="space-20"></div>
                        <h5 class="title">About Vehicle Care</h5>
                        <div class="space-30"></div>
                        <h3 class="blue-color">
                            Tempat pencucian kendaraan yang mudah, murah, cepat, dan tidak repot.
                        </h3>
                        <div class="space-20"></div>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiing elit, sed do
                            eiusmod tempor incididunt ut labore et laborused sed do eiusmod
                            tempor incididunt ut labore et laborused.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About-Area-End -->
    <!-- How-To-Use -->
    <section class="section-padding" id="features_page">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <div class="page-title">
                        <h5 class="title wow fadeInUp" data-wow-delay="0.2s">
                            Advantages
                        </h5>
                        <div class="space-10"></div>
                        <h3 class="dark-color wow fadeInUp" data-wow-delay="0.4s">
                            Penggunaan Aplikasi Sangat Mudah
                        </h3>
                    </div>
                    <div class="space-20"></div>
                    <div class="desc wow fadeInUp" data-wow-delay="0.6s">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiing elit, sed do
                            eiusmod tempor incididunt ut labore et laborused sed do eiusmod
                            tempor incididunt ut labore et laborused.
                        </p>
                    </div>
                    <div class="space-50"></div>
                    <a href="#" class="bttn-default wow fadeInUp" data-wow-delay="0.8s">Learn More</a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-5 col-md-offset-1">
                    <div class="space-60 hidden visible-xs"></div>
                    <div class="service-box wow fadeInUp" data-wow-delay="0.2s">
                        <div class="box-icon">
                            <i class="lnr lnr-clock"></i>
                        </div>
                        <h4>Transaksi dipermudah</h4>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                            eiusmod tempor.
                        </p>
                    </div>
                    <div class="space-50"></div>
                    <div class="service-box wow fadeInUp" data-wow-delay="0.2s">
                        <div class="box-icon">
                            <i class="lnr lnr-laptop-phone"></i>
                        </div>
                        <h4>Booking Online</h4>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                            eiusmod tempor.
                        </p>
                    </div>
                    <div class="space-50"></div>
                    <div class="service-box wow fadeInUp" data-wow-delay="0.2s">
                        <div class="box-icon">
                            <i class="lnr lnr-cog"></i>
                        </div>
                        <h4>No Ribet</h4>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                            eiusmod tempor.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- How-To-Use-End -->

    <!-- Footer-Area -->
    <footer class="footer-area" id="contact_page">
        <div class="section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title text-center">
                            <h5 class="title">Contact US</h5>
                            <h3 class="dark-color">Find Us By Bellow Details</h3>
                            <div class="space-60"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-4">
                        <div class="footer-box">
                            <div class="box-icon">
                                <span class="lnr lnr-map-marker"></span>
                            </div>
                            <p>
                                Universitas Advent Indonesia
                            </p>
                        </div>
                        <div class="space-30 hidden visible-xs"></div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="footer-box">
                            <div class="box-icon">
                                <span class="lnr lnr-phone-handset"></span>
                            </div>
                            <p>
                                +62 815-8415-5129
                            </p>
                        </div>
                        <div class="space-30 hidden visible-xs"></div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="footer-box">
                            <div class="box-icon">
                                <span class="lnr lnr-envelope"></span>
                            </div>
                            <p>1881017@unai.edu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer-Bootom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-md-5">
                        <span>Copyright &copy;
                            <script>
                                document.write(new Date().getFullYear());
                            </script>
                            All rights reserved | This template is made with
                            <i class="lnr lnr-heart" aria-hidden="true"></i> by
                            <a href="https://colorlib.com" target="_blank">Colorlib</a></span>
                        <div class="space-30 hidden visible-xs"></div>
                    </div>
                    <div class="col-xs-12 col-md-7">
                        <div class="footer-menu">
                            <ul>
                                <li><a href="#">About</a></li>
                                <li><a href="#">Promo</a></li>
                                <li><a href="#">Advantages</a></li>
                                <li><a href="#">Contacts</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer-Bootom-End -->
    </footer>
    <!-- Footer-Area-End -->
    <!--Vendor-JS-->
    <script src="{{ asset('js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/vendor/bootstrap.min.js') }}"></script>
    <!--Plugin-JS-->
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/contact-form.js') }}"></script>
    <script src="{{ asset('js/ajaxchimp.js') }}"></script>
    <script src="{{ asset('js/scrollUp.min.js') }}"></script>
    <script src="{{ asset('js/magnific-popup.min.js') }}"></script>
    <script src="{{ asset('js/wow.min.js') }}"></script>
    <!--Main-active-JS-->
    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>
