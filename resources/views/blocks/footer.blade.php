<footer class="nk-footer bg-theme-alt section-connect">
    <div class="section section-m pb-0 tc-light ov-h">
        <div class="container py-4">
            <!-- Block @s -->
            <div class="nk-block pb-lg-5">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-6 col-md-9">
                        <div class="wide-auto-sm section-head section-head-sm pdb-r">
                            <h4 class="title title-md animated" data-animate="fadeInUp" data-delay=".1">Don't miss out, Stay updated</h4>
                        </div>
                        <form action="form/subscribe.php" class="nk-form-submit" method="post">
                            <div class="field-inline field-inline-round field-inline-s2-sm bg-theme-dark-alt shadow-soft animated" data-animate="fadeInUp" data-delay=".2">
                                <div class="field-wrap">
                                    <input class="input-solid input-solid-md required email" type="text" name="contact-email" placeholder="Enter your email">
                                    <input type="text" class="d-none" name="form-anti-honeypot" value="">
                                </div>
                                <div class="submit-wrap">
                                    <button class="btn btn-md btn-round btn-grad h-100">Subscribe</button>
                                </div>
                            </div>
                            <div class="form-results"></div>
                        </form>
                    </div>
                </div>
            </div><!-- .block @e -->
        </div>
        <div class="nk-ovm shape-contain shape-center-top shape-p"></div>
    </div>
    <div class="section section-footer section-s tc-light bg-transparent">
        <div class="container">
            <!-- Block @s -->
            <div class="nk-block block-footer">
                <div class="row">
                    <div class="col">
                        <div class="wgs wgs-text text-center mb-3">
                            <ul class="social pdb-m justify-content-center">
                                <li><a href="#"><em class="social-icon fab fa-twitter"></em></a></li>
                                <li><a href="#"><em class="social-icon fab fa-discord"></em></a></li>
                                <li><a href="#"><em class="social-icon fab fa-youtube"></em></a></li>
                                <li><a href="#"><em class="social-icon fab fa-instagram"></em></a></li>
                                <li><a href="#"><em class="social-icon fab fa-facebook-f"></em></a></li>
                                <li><a href="#"><em class="social-icon fab fa-medium-m"></em></a></li>
                            </ul>
                            <div class="copyright-text copyright-text-s3 pdt-m">
                                <p><span class="d-sm-block">Copyright &copy; 2022, DTT Token</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div><!-- .block @e -->
        </div>
    </div>
    <div style="width: 100%;height: 50px;position: fixed;z-index: 73;bottom: 0;background: #28384c;">
        <!-- TradingView Widget BEGIN -->
        <div class="tradingview-widget-container">
            <div class="tradingview-widget-container__widget"></div>
            <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
                {
                    "symbols": [
                    {
                        "proName": "FOREXCOM:SPXUSD",
                        "title": "S&P 500 Index"
                    },
                    {
                        "proName": "FOREXCOM:NSXUSD",
                        "title": "US 100 Cash CFD"
                    },
                    {
                        "proName": "FX_IDC:EURUSD",
                        "title": "EUR to USD"
                    },
                    {
                        "proName": "BITSTAMP:BTCUSD",
                        "title": "Bitcoin"
                    },
                    {
                        "proName": "BITSTAMP:ETHUSD",
                        "title": "Ethereum"
                    }
                ],
                    "showSymbolLogo": true,
                    "isTransparent": true,
                    "displayMode": "regular",
                    "colorTheme": "dark",
                    "locale": "en"
                }
            </script>
        </div>
        <!-- TradingView Widget END -->
    </div>
</footer>
</div>
<!-- Preloader -->
<div class="preloader"><span class="spinner spinner-round"></span></div>
<!-- JavaScript -->
<script src="{{asset('web/assets/js/jquery.bundle.js?ver=210')}}"></script>
<script src="{{asset('web/assets/js/scripts.js?ver=210')}}"></script>
<script src="{{asset('web/assets/js/charts.js?ver=210')}}"></script>
<script src="{{asset('web/assets/js/charts.js?ver=210')}}"></script>
</body>

</html>
