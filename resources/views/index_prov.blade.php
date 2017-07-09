
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>API Test Generator &mdash; For Laravel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Free HTML5 Template by FREEHTML5.CO" />
    <meta name="keywords" content="free html5, free template, free bootstrap, html5, css3, mobile first, responsive" />
    <meta name="author" content="FREEHTML5.CO" />

<!--
	//////////////////////////////////////////////////////

	FREE HTML5 TEMPLATE
	DESIGNED & DEVELOPED by FREEHTML5.CO

	Website: 		http://freehtml5.co/
	Email: 			info@freehtml5.co
	Twitter: 		http://twitter.com/fh5co
	Facebook: 		https://www.facebook.com/fh5co

	//////////////////////////////////////////////////////
	 -->

    <!-- Facebook and Twitter integration -->
    <meta property="og:title" content=""/>
    <meta property="og:image" content=""/>
    <meta property="og:url" content=""/>
    <meta property="og:site_name" content=""/>
    <meta property="og:description" content=""/>
    <meta name="twitter:title" content="" />
    <meta name="twitter:image" content="" />
    <meta name="twitter:url" content="" />
    <meta name="twitter:card" content="" />

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" href="favicon.ico">

    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,600,400italic,700' rel='stylesheet' type='text/css'>

    <!-- Animate.css -->
    <link rel="stylesheet" href="{{asset('css/animate.css')}}">
    <!-- Icomoon Icon Fonts-->
    <link rel="stylesheet" href="{{asset('css/icomoon.css')}}">
    <!-- Simple Line Icons -->
    <link rel="stylesheet" href="{{asset('css/simple-line-icons.css')}}">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">
    <!-- Bootstrap  -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">

    <!--
    Default Theme Style
    You can change the style.css (default color purple) to one of these styles

    1. pink.css
    2. blue.css
    3. turquoise.css
    4. orange.css
    5. lightblue.css
    6. brown.css
    7. green.css

    -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}">


    <!-- Modernizr JS -->
    <script src="{{asset('js/modernizr-2.6.2.min.js')}}"></script>
    <!-- FOR IE9 below -->
    <!--[if lt IE 9]>
    <script src="{{asset('js/respond.min.js')}}"></script>
    <![endif]-->

</head>
<body>
<header role="banner" id="fh5co-header">
    <div class="container">
        <!-- <div class="row"> -->
        <nav class="navbar navbar-default">
            <div class="navbar-header">
                <!-- Mobile Toggle Menu Button -->
                <a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"><i></i></a>
                <a class="navbar-brand" href="#" data-nav-section="home">API Test Generator</a><span>Beta</span>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="active"><a href="#" data-nav-section="home"><span>Home</span></a></li>
                    <li><a href="#" data-nav-section="features"><span>Features</span></a></li>
                </ul>
            </div>
        </nav>
        <!-- </div> -->
    </div>
</header>

<div id="slider" data-section="home">
    <div class="owl-carousel owl-carousel-fullwidth">
        <!-- You may change the background color here. -->
        <div class="item" style="background: #352f44;">
            <div class="container" style="position: relative;">
                <div class="row">
                    <div class="col-md-7 col-sm-7">
                        <div class="fh5co-owl-text-wrap">
                            <div class="fh5co-owl-text">
                                <h1 class="fh5co-lead to-animate">Laravel API Testing</h1>
                                <h2 class="fh5co-sub-lead to-animate">
                                    Automatic test generation for APIs developed in Laravel</h2>
                                    <p class="to-animate-2">API Test Generator creates a set of tests that will guarantee the quality and efficiency of your code. In just 3 steps you can make use of this powerful tool that will reduce the cost of the testing stage with impressive results</p>
                                    <p class="to-animate-2"><a href="{{URL::asset('/register')}}" class="btn btn-primary btn-lg">Get started</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-md-push-1 col-sm-4 col-sm-push-1 iphone-image">
                        <div class="iphone to-animate-2"><img src="{{asset('images/code.png')}}" alt="Free HTML5 Template by FREEHTML5.co"></div>

                    </div>

                </div>
            </div>
        </div>
        <!-- You may change the background color here.  -->
        <div class="item" style="background: #38569f;">
            <div class="container" style="position: relative;">
                <div class="row">
                    <div class="col-md-7 col-md-push-1 col-md-push-5 col-sm-7 col-sm-push-1 col-sm-push-5">
                        <div class="fh5co-owl-text-wrap">
                            <div class="fh5co-owl-text">
                                <h1 class="fh5co-lead to-animate">Free for a limited time</h1>
                                <h2 class="fh5co-sub-lead to-animate">You still have time to get a free API Test Generator license</h2>
                                    <p class="to-animate-2">We want to thank all the users who have tried the beta version with a license of one year of this tool with each and every one of its characteristics.</p>
                                    <p class="to-animate-2"><a href="{{URL::asset('/register')}}" class="btn btn-primary btn-lg">Get started</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-md-pull-7 col-sm-4 col-sm-pull-7 iphone-image">
                        <div class="iphone to-animate-2"><img src="{{asset('images/free.png')}}" alt="Free HTML5 Template by FREEHTML5.co"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<div id="fh5co-our-services" data-section="features">
    <div class="container">
        <div class="row row-bottom-padded-sm">
            <div class="col-md-12 section-heading text-center">
                <h2 class="to-animate">Features</h2>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 to-animate">
                        <h3>API Test Generator is a unique tool that allows you to test your web service in the shortest possible time.</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="box to-animate">
                    <div class="icon colored-1"><span><i class="icon-rocket"></i></span></div>
                    <h3>Speed</h3>
                    <p>You can build tests for your web service in just a few minutes. We reduce test time by more than 90%</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box to-animate">
                    <div class="icon colored-2"><span><i class="icon-magic"></i></span></div>
                    <h3>100% coverage</h3>
                    <p>A positive test and a negative test are generated for each possible invalid value of each parameter, thus covering a high percentage of cases.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box to-animate">
                    <div class="icon colored-3"><span><i class="icon-cloud"></i></span></div>
                    <h3>Without downloads</h3>
                    <p>Just indicate the url of your repository in GitHub and you will get the test files instantly</p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box to-animate">
                    <div class="icon colored-4"><span><i class="icon-lock"></i></span></div>
                    <h3>Confidential and safe</h3>
                    <p>
                        The most important! Do not worry about your project. There will be no trace of him on our server. We delete the code from our system immediately after generating the test files.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer id="footer" role="contentinfo">
    <div class="container">
        <div class="row row-bottom-padded-sm">
            <div class="col-md-12">
                <p class="copyright text-center">&copy; 2015 Free <a href="index.html">Crew</a>. All Rights Reserved. <br> Free HTML5 Template by <a href="http://freehtml5.co/" target="_blank">FREEHTML5.co</a> | Images by <a href="http://pexels.com/" target="_blank">Pexels</a> &amp;  <a href="http://unsplash.com/" target="_blank">Unsplash</a></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <ul class="social social-circle">
                    <li><a href="#"><i class="icon-twitter"></i></a></li>
                    <li><a href="#"><i class="icon-facebook"></i></a></li>
                    <li><a href="#"><i class="icon-youtube"></i></a></li>
                    <li><a href="#"><i class="icon-pinterest"></i></a></li>
                    <li><a href="#"><i class="icon-linkedin"></i></a></li>
                    <li><a href="#"><i class="icon-instagram"></i></a></li>
                    <li><a href="#"><i class="icon-dribbble"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>


<!-- jQuery -->
<script src="{{asset('js/jquery.min.js')}}"></script>
<!-- jQuery Easing -->
<script src="{{asset('js/jquery.easing.1.3.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<!-- Waypoints -->
<script src="{{asset('js/jquery.waypoints.min.js')}}"></script>
<!-- Owl Carousel -->
<script src="{{asset('js/owl.carousel.min.js')}}"></script>

<!-- For demo purposes only styleswitcher ( You may delete this anytime ) -->
{{--<script src="{{asset('js/jquery.style.switcher.js')}}"></script>--}}
{{--<script>--}}
    {{--$(function(){--}}
        {{--$('#colour-variations ul').styleSwitcher({--}}
            {{--defaultThemeId: 'theme-switch',--}}
            {{--hasPreview: false,--}}
            {{--cookie: {--}}
                {{--expires: 30,--}}
                {{--isManagingLoad: true--}}
            {{--}--}}
        {{--});--}}
        {{--$('.option-toggle').click(function() {--}}
            {{--$('#colour-variations').toggleClass('sleep');--}}
        {{--});--}}
    {{--});--}}
{{--</script>--}}
<!-- End demo purposes only -->

<!-- Main JS (Do not remove) -->
<script src="{{asset('js/main.js')}}"></script>

</body>
</html>
