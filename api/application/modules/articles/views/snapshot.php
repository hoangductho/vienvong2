<html>
<head>
    <base href="/">
    <meta name="fragment" content="!">
    <meta charset="utf-8">
    <title><?php echo $title ?> - Viễn Vọng</title>
    <link rel="shortcut icon" href="http://vienvong.vn/images/vienvong-logo1.3094715e.png">
    <link rel="apple-touch-icon-precomposed" href="http://vienvong.vn/images/vienvong-logo2.5a633749.png">
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="keywords" content="<?php echo $tags; ?>">
    <link rel=”author” href=”https://www.facebook.com/vienvong.vn”/>
    <meta property="og:title" content="<?php echo $title; ?>"/>
    <meta property="og:description" content="<?php echo $description; ?>"/>
    <meta property="og:image" content="http://<?php echo $_SERVER['HTTP_HOST'] . '/' . $lAvatar; ?>"/>
    <meta property="og:url" content="<?php echo 'http://vienvong.vn/express/' . $_id; ?>"/>
    <meta property="og:site_name" content="Viễn Vọng"/>
    <meta property="fb:app_id" content="550251971759267"/>
    <meta property="og:type" content="article"/>
    <meta property="og:locale:alternate" content="vi_VI"/>
    <meta property="article:author" content="http://vienvong.vn"/>
    <meta name="viewport" content="width=device-width">
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="stylesheet" href="http://vienvong.vn/styles/vendor.efeb5ea4.css">
    <link rel="stylesheet" href="http://vienvong.vn/styles/main.bdf53822.css">

</head>
<body> <!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]--> <!-- Add your site or application content here -->
<div id="top" ng-app="vienvong" ng-controller="MainCtrl" class="ng-scope"> <!-- Header of Page loading-->
    <header>
        <div id="header-box">
            <div id="logo" class="header-package col-sm-0-min col-xs-3">
                <div id="logo-button" class="blur" ng-init="secondMenu = false" ng-click="secondMenu =! secondMenu"><img
                        src="images/vienvong-logo2.5a633749.png" height="100%" alt="vienvong.vn mobile icon"> <span
                        class="caret"></span></div>
                <nav id="second-menu" class="hide" ng-class="{'show': (secondMenu)}" off-click="secondMenu = false"
                     off-click-if="secondMenu"><a ui-sref="main.articles.home" href="http://vienvong.vn//">
                        <div class="second-menu-options blur active"
                             ng-class="{'active': (state.includes('main.articles.home'))}">All Posts
                        </div>
                    </a> <a ui-sref="main.articles.group({group: 'news'})" href="http://vienvong.vn//news">
                        <div class="second-menu-options blur" ng-class="{'active': (state.params.group == 'news')}">
                            News
                        </div>
                    </a> <a ui-sref="main.articles.group({group: 'blog'})" href="http://vienvong.vn//blog">
                        <div class="second-menu-options blur" ng-class="{'active': (state.params.group == 'blog')}">
                            Blog
                        </div>
                    </a> <a ui-sref="main.articles.group({group: 'tutorials'})" href="http://vienvong.vn//tutorials">
                        <div class="second-menu-options blur"
                             ng-class="{'active': (state.params.group == 'tutorials')}">Tutorials
                        </div>
                    </a> <a ui-sref="main.articles.search({text: 'NoSQL'})" href="http://vienvong.vn//search/NoSQL">
                        <div class="second-menu-options blur" ng-class="{'active': (state.params.text == 'NoSQL')}">
                            NoSQL
                        </div>
                    </a></nav>
            </div>
            <div id="site-name" class="header-package col-xs-0-max col-sm-3 col-md-2">
                <a ui-sref="main.articles.home()" href="http://vienvong.vn//">
                    <div id="site-name-letter" class="blur col-sm-12">
                        <!--<img src="images/vienvong-fulltext3.48e28378.png" height="80%" style="padding-top: 5%">-->
                        <div style="font-family: 'Times New Roman'; font-size: 32px"><b>Viễn Vọng</b></div>
                    </div>
                </a></div>
            <div id="main-bar" class="header-package col-xs-0-max col-sm-5 col-md-5">
                <div id="main-bar-above" class="header-dual"><p>Tri thức là sự chia sẻ</p></div>
                <div id="main-bar-below" class="header-dual">
                    <nav id="main-menu"><a ui-sref="main.articles.home" href="http://vienvong.vn//">
                            <div class="main-menu-options blur active"
                                 ng-class="{'active': (state.includes('main.articles.home'))}">All Posts
                            </div>
                        </a> <a ui-sref="main.articles.group({group: 'news'})" href="http://vienvong.vn//news">
                            <div class="main-menu-options blur" ng-class="{'active': (state.params.group == 'news')}">
                                News
                            </div>
                        </a> <a ui-sref="main.articles.group({group: 'blog'})" href="http://vienvong.vn//blog">
                            <div class="main-menu-options blur" ng-class="{'active': (state.params.group == 'blog')}">
                                Blog
                            </div>
                        </a> <a ui-sref="main.articles.group({group: 'tutorials'})" href="http://vienvong.vn//tutorials">
                            <div class="main-menu-options blur"
                                 ng-class="{'active': (state.params.group == 'tutorials')}">Tutorials
                            </div>
                        </a> <a ui-sref="main.articles.search({text: 'NoSQL'})" href="http://vienvong.vn//search/NoSQL">
                            <div class="main-menu-options blur" ng-class="{'active': (state.params.text == 'NoSQL')}">
                                NoSQL
                            </div>
                        </a></nav>
                </div>
            </div>
            <div id="actions" class="header-package col-xs-8 col-sm-4 col-md-5 pull-right">
                <div id="auth-box" class="header-package block pull-right"> <!-- ngIf: !online -->
                    <div ng-if="!online" class="ng-scope">
                        <div id="user-name" class="header-dual"><p>You are Anonymous!</p></div>
                        <div id="user-actions" class="header-dual">
                            <button ng-click="loginShow()">Sign in</button>
                            <button rel="nofollow" ui-sref="main.auth.registry" href="http://vienvong.vn//auth/registry">Sign up</button>
                        </div>
                    </div>
                    <!-- end ngIf: !online --> <!-- ngIf: online --> </div>
                <div id="facebook-box" class="header-package block pull-right">
                    <div class="header-button facebook-bar-button" ng-click="fbShow()"><i
                            class="fa fa-2x fa-facebook header-color"></i></div>
                </div>
                <div id="search-button" class="header-package col-md-0-min block pull-right">
                    <div class="header-button facebook-bar-button" ng-click="searchShow()"><i
                            class="fa fa-search primary-color fa-2x"></i></div>
                </div>
                <div id="search-bar" class="header-package block col-md-6 col-lg-7 col-sm-0-max pull-right">
                    <!-- Search Box-->
                    <div id="search-bar-box" ng-controller="searchCtrl"
                         class="bar pull-right col-md-12 full-radius ng-scope">
                        <form ng-submit="searchArticle()" class="row ng-pristine ng-valid">
                            <div class="pull-left col-md-11 col-sm-10 row none-padding-right"><input type="text"
                                                                                                     name="search"
                                                                                                     ng-model="searchString"
                                                                                                     class="form-control none-padding-right ng-pristine ng-untouched ng-valid">
                            </div>
                            <div class="pull-right">
                                <button type="submit" class="form-control none-effect"><i
                                        class="fa fa-search primary-color"></i></button>
                            </div>
                        </form>
                    </div>
                    <!-- End search box--> </div>
            </div>
        </div>
    </header>
    <!-- end header--> <!-- Main Content loading-->
    <main class="container">
        <noscript>&lt;div class="row articles-block"&gt; &lt;h3&gt;Trình duyệt của bạn đã chặn Javascript&lt;/h3&gt;
            &lt;p&gt;vienvong.vn được xây dựng bằng angularJS. Website của chúng tôi không thể hoạt động khi Javascript
            bị chặn.&lt;/p&gt; &lt;p&gt;Để vienvong.vn có thể hoạt động trên trình duyệt của bạn, xin vui lòng hãy kích
            hoạt Javascript.&lt;/p&gt; &lt;p&gt;Hi vọng bạn sẽ có những trải nghiệm thú vị.&lt;/p&gt; &lt;p&gt;Xin cảm
            ơn&lt;/p&gt; &lt;/div&gt;</noscript>
        <!-- uiView:  -->
        <div class="row ng-scope" ui-view=""><!-- uiView: undefined -->
            <div id="left-edge" class="col-lg-2 col-md-0-max ng-scope"></div>
            <div id="express" class="col-lg-8 col-md-9 col-sm-12 ng-scope"> <!--<main>-->
                <article scroll="">
                    <div id="brand" class="articles-block">
                        <div class="detail-content detail-box"><h1 class="ng-binding"><?php echo $title ?></h1></div>
                        <div class="suggest-articles detail-box">
                            <p class="suggest-article ng-scope" ng-repeat="art in suggest">
                                <?php
                                if(isset($suggest)) {
                                    foreach ($suggest as $link) {
                                        $href = 'http://vienvong.vn/express/' . $link['_id'];
                                        echo '<a href="' . $href . '"><i class="fa fa-caret-right g-plus"> </i>' . $link['title'] . '</a><br>';
                                    }
                                }

                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="detail-content detail-box">
                        <div markdown-safe-viewer="">
                            <div ng-bind-html="sanitizedContent" class="wmd-preview markdown-body ng-binding ng-scope" style="">
                                <?php
                                require_once(APPPATH . '/libraries/markdown/parsedown.php');
                                $Parsedown = new Parsedown();
                                echo $Parsedown->text($content);
                                ?>
                            </div>
                        </div>
                    </div>
                </article>

            <div id="right-edge" class="col-lg-2 col-md-3 col-sm-0-max ng-scope">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- ad.no.1 -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-5355896671501389"
                     data-ad-slot="8491924156"
                     data-ad-format="auto"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        </div>
    </main>
    <!-- End main content--> <!-- Footer of page loading-->
    <footer class="col-md-12">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-0-max"></div>
                <div class="col-lg-8 col-md-9">
                    <div id="introduce"><h4>Lời Tựa</h4>

                        <p>Với những nỗ lực của mình, chúng tôi hi vọng đã đem đến cho bạn những thông tin thực sự hữu
                            ích.</p>

                        <p>Mọi thiếu sót của chúng tôi trong quá trình thực hiện, phát hành và quản lí nội dung cũng như
                            website mong được các bạn thông cảm vả góp ý.</p>

                        <p>Sự quan tâm của các bạn là niềm vui đối với chúng tôi</p>

                        <p>Xin chân thành cảm ơn!</p>
                        <hr>
                        <h4>Bản quyền nội dung:</h4>

                        <p></p>
                        <blockquote>Để thể hiện sự tôn trọng của bạn với chúng tôi và mọi người xin hãy ghi rõ link
                            nguồn bài viết để xác nhận bản quyền khi các bạn xuất bản lại nội dung của chúng tôi.
                        </blockquote>
                        <p></p>

                        <p></p>
                        <blockquote>Trong trường hợp các bạn vi phạm vấn đề bản quyền khi phát hành lại nội dung chúng
                            tôi đã đăng tải, chúng tôi sẽ buộc lòng phải nhờ Google can thiệp để xóa nội dung của các
                            bạn trên Google theo các điều khoản của chương trình bảo vệ bản quyền Google DMCA.
                        </blockquote>
                        <p></p></div>
                    <hr>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <div id="contact"><h4>Liên hệ</h4>

                        <p>Mọi ý kiến đóng góp của các bạn xin gửi về địa chỉ e-mail:</p>

                        <p></p>
                        <blockquote>vienvong.vn@gmail.com</blockquote>
                        <p></p>

                        <p>Hoặc liên hệ với Web Master qua E-mail: </p>

                        <p></p>
                        <blockquote>hoangductho.3690@gmail.com</blockquote>
                        <p></p>

                        <p>Hoặc Skype:</p>

                        <p></p>
                        <blockquote>hoangductho.3690</blockquote>
                        <p></p></div>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-0-max"></div>
                <div class="col-lg-8 col-md-9">
                    <div class="info-menu">
                        <nav>
                            <a href="http://vienvong.vn/info/about" class="info-menu-options blur">Giới thiệu</a>
                            <a href="http://vienvong.vn/info/privacy" class="info-menu-options blur">Privacy</a>
                            <a href="http://vienvong.vn/info/copyright" class="info-menu-options blur">Bản quyền</a>
                            <a href="http://vienvong.vn/info/sitemap" class="info-menu-options blur">Site Map</a></nav>
                    </div>
                    <hr>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-12"></div>
            </div>
            <div id="copyright"> @2015 vienvong.vn</div>
            <br></div>
    </footer>
    <!-- End footer of page--> <!-- Irregular Box-->
    <div class="irregular-box">
        <div id="search-bar" class="header-package hide" ng-class="{'show': (searchBoxShow)}" off-click="searchHide()"
             off-click-if="searchBoxShow"> <!-- Search Box-->
            <div id="search-bar-box" class="bar pull-right full-radius">
                <form ng-submit="searchPosts()" class="ng-pristine ng-valid">
                    <div class="pull-left col-md-11 col-sm-10 row none-padding-right"><input type="text" name="search"
                                                                                             class="form-control none-padding-right">
                    </div>
                    <div class="pull-right">
                        <button type="submit" class="form-control none-effect"><i
                                class="fa fa-search primary-color"></i></button>
                    </div>
                </form>
            </div>
            <!-- End search box--> </div>
        <!-- Load facebook page-->
        <div id="facebook-page-box" class="hide" ng-class="{'show': (facebookShow)}" off-click="fbHide()"
             off-click-if="facebookShow">
            <div id="fb-root" class=" fb_reset">
                <div style="position: absolute; top: -10000px; height: 0px; width: 0px;">
                    <div>
                        <iframe name="fb_xdm_frame_http" frameborder="0" allowtransparency="true" allowfullscreen="true"
                                scrolling="no" id="fb_xdm_frame_http" aria-hidden="true"
                                title="Facebook Cross Domain Communication Frame" tabindex="-1"
                                src="http://static.ak.facebook.com/connect/xd_arbiter/1ldYU13brY_.js?version=41#channel=f1a90e4a8&amp;origin=http%3A%2F%2Fvienvong.vn"
                                style="border: none;"></iframe>
                        <iframe name="fb_xdm_frame_https" frameborder="0" allowtransparency="true"
                                allowfullscreen="true" scrolling="no" id="fb_xdm_frame_https" aria-hidden="true"
                                title="Facebook Cross Domain Communication Frame" tabindex="-1"
                                src="https://s-static.ak.facebook.com/connect/xd_arbiter/1ldYU13brY_.js?version=41#channel=f1a90e4a8&amp;origin=http%3A%2F%2Fvienvong.vn"
                                style="border: none;"></iframe>
                    </div>
                </div>
                <div style="position: absolute; top: -10000px; height: 0px; width: 0px;">
                    <div></div>
                </div>
            </div>
            <!-- ngIf: fanpageInit --> </div>
        <!-- end load facebook page--> <!-- Login box-->
        <div id="login-box" class="hide" ng-class="{'show': (loginBoxShow &amp;&amp; !online)}" off-click="loginHide()"
             off-click-if="loginBoxShow">
            <div class="login-actions">
                <div class="login-title end-box"><img src="/images/vienvong1.3ce88ada.png" alt="vienvong.vn login icon">
                    <strong class="g-plus"> Login</strong></div>
                <div class="login-description end-box col-xs-0-max"><p><i class="fa fa-caret-right g-plus"></i> You can
                        <a ui-sref="main.auth.registry" href="/auth/registry">registry</a> the account by your e-mail.
                    </p>

                    <p><i class="fa fa-caret-right g-plus"></i> You can using Facebook or Google account to registry and
                        login.</p></div>
                <div class="login-button end-box">
                    <form ng-controller="loginCtrl" class="ng-pristine ng-valid ng-scope">
                        <div class="login-data"> <!-- ngIf: !result.ok && result.err --> </div>
                        <div class="login-data row">
                            <div class="container-fluid"><input type="text" name="email" ng-model="auth.email"
                                                                ng-blur="valid('email')" placeholder="E-mail"
                                                                class="form-control ng-pristine ng-untouched ng-valid">
                            </div>
                            <!-- ngIf: !validate.email.valid --> </div>
                        <p></p>

                        <div class="login-data row">
                            <div class="container-fluid"><input type="password" name="password" ng-model="auth.password"
                                                                ng-blur="valid('password')" placeholder="Password"
                                                                class="form-control ng-pristine ng-untouched ng-valid">
                            </div>
                            <!-- ngIf: !validate.password.valid --> </div>
                        <p></p>

                        <div class="login-data row">
                            <div class="container-fluid">
                                <button class="btn btn-primary disable" ng-class="{'disable': (scores < 2)}"
                                        ng-click="process()">Sing in
                                </button>
                                <button ng-click="reset()" class="btn btn-default">Clear</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="user-term">
                <div class="user-terms"> <!--<a href="">--> <!--This terms for users--> <!--</a>--> </div>
            </div>
        </div>
        <!-- End login box--> </div>
    <!-- End irregular box--> </div>
<div id="gpt_hidden_data" style="display: none;"></div>
</body>
</html>