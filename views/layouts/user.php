<?php
/**
 * @var \Application $app
 * @var string $contents
 * @var \services\ViewServiceProvider $this
 * @var string $assets
 */
use models\Map\BookTableMap;
use models\Map\PageWidgetTableMap;
use services\URLS;
use widgets\BooksList;
use widgets\Reading;
use widgets\RegistrationInvitation;
use widgets\RegularVisitor;

?>
<!DOCTYPE html><html lang="ru" ng-app="bible"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name="keywords" content="Библия Онлайн, толкование Священного Писания, переводы Библии, планы чтения Библии, Евангелие, аудиобиблия, слушать Библию, Библейские карты" />
<meta name="description" content="Библия от «А» до «Я»: тексты Священного Писания в оригинале, и в различных переводах, толкования Отцов Церкви и современных богословов, исторические комментарии и справочники, интерактивные путешествия во времени и пространстве, аудиокниги и лекции. Ekzeget.ru - ваш проводник в мир Библии" /><meta property="og:image" content="<?=$assets?>/IMG/soc_kniga.png" />
<link rel="image_src" href="/IMG/soc_kniga.png" /><link rel="apple-touch-icon" href="<?=$assets?>/IMG/soc_kniga.png" /><link rel="apple-touch-icon" href="<?=$assets?>/IMG/soc_kniga60x60.png" size="60x60" /><link rel="apple-touch-icon" href="<?=$assets?>/IMG/soc_kniga76x76.png" size="76x76" /><link rel="apple-touch-icon" href="<?=$assets?>/IMG/soc_kniga120x120.png" size="120x120" /><link rel="apple-touch-icon" href="<?=$assets?>/IMG/soc_kniga152x152.png" size="152x152" />
<title><?=$page_title?> | Ekzeget.ru - ваш проводник в мир Библии</title>
<link rel="icon" href="<?=$assets?>/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?=$assets?>/favicon.ico" type="image/x-icon" />
<link href="https://fonts.googleapis.com/css?family=Roboto+Mono|Roboto:400,700,900" rel="stylesheet">
<?php
    \views\assets\LegacyMainAsset::register($this);
    $this->assets();
    ?>
<!--noindex-->
<!-- Скрипты кнопок соцсетей -->
<script type="text/javascript" src="//vk.com/js/api/openapi.js?105"></script>
<script  type="text/javascript">
 VK.init({apiId: 4011940, onlyWidgets: true});
</script>
<script  type="text/javascript">
VK.Widgets.Like("vk_like", {type: "mini"});
</script>
<!-- Счетчики -->


<!-- Счетчики -->
<!--/noindex-->
<meta name='yandex-verification' content='6940e802632820aa' />
</head>
<body>
<section id="head">
    <div class="container">
    <?= \widgets\Navmenu::widget()?>

<div class="jumbotron">
    <div class="row">
    <div class="col-md-4"><!-- Календарь -->
    <!--noindex-->
<?= \widgets\Calendula::widget()?>

<!--/noindex-->  
    </div>
        <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6"><a href = "/.."><img src="<?=$assets?>/IMG/logo.png" style="height:70px; margin-left: 45px; margin-top: 2px" title="Экзегет. Библия и толкования" /></a></div>
                    <div class="col-md-6">
                        <div class="rss">
                        <a href="https://vk.com/ekzeget" target="_blank" title="Мы ВКонтакте">
                        <img style="margin: 0 5px 0 0" src="<?=$assets?>/IMG/vk.png" /></a> 
                        <a href="https://www.facebook.com/groups/ekzeget/" target="_blank" title="Мы на Facebook'e">
                        <img style="margin: 0 5px 0 0" src="<?=$assets?>/IMG/f.png" /></a> 
                        <a href="https://twitter.com/EkzegetRU" target="_blank" title="Мы в твиттере">
                        <img style="margin: 0 5px 0 0" src="<?=$assets?>/IMG/tvit.png" /></a>
                        <a href="https://www.youtube.com/channel/UCymsjx24eU3kRFUjeBsh47A" target="_blank" title="Наш канал на YouTube">
                        <img style="margin: 0 5px 0 0" src="<?=$assets?>/IMG/youtube.png"></a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <!--noindex-->
    <form action="search_stih.php" method="get">
        <div class="search_pano"> 
<input id="search" type="search" name="search" maxlength="100" class="form-control" required pattern="[^<|^>]{3,}" placeholder="Поиск...">
<a onclick="advanced_search();" title="Расширенный поиск" id="togglersearch" class="search-settings"><img src="<?=$assets?>/IMG/search_settings.png" title="Расширенный поиск"></a></a>
<button class="search_button" type="submit"><img src="<?=$assets?>/IMG/search.png" title="Начать поиск" style="width: 90%;"></button>
                <div style="display: none;" class="tsear">
                <div id="res_advanced">
                <br /><div style="text-align: center;"><img src="<?=$assets?>/IMG/loading.gif" style="width:50px" /></div><br />
                </div>

                </div>
        </div>
    </form>
<!--/noindex-->
<?= \widgets\GeneralMenu::widget()?>
                    </div>
                </div>
        </div>
    </div>
     
</div>
    </div>
</section>
<section id="ekzeget">
    <div class="container"><h1><?=$page_title?></h1></div>
</section>

<section id="page">
<div class="container">
    <div class="row">
        <!--левый блок -->
        <div class="col-sm-3">
        <!-- НАЧАЛО МЕНЮ -->
            <ul id="myTab" class="nav nav-tabs">
                <li class="active"><a class="menu_bible" href="#panel1">Новый Завет</a></li>
                <li><a class="menu_bible" href="#panel2">Ветхий Завет</a></li>
            </ul>
                <div class="tabsmenu">
                    <div class="tab-content">
                        <div id="panel1" class="tab-pane fade in active">
                            <div id="menu_nz">
                                <ul id="verticalmenu" class="glossymenu"  style="border-top: 1px solid #C7C7C7; ">
                                <?= BooksList::widget(['testament' => BookTableMap::COL_TESTAMENT_NT])?>
                                </ul>
                            </div>
                    </div>
                <div id="panel2" class="tab-pane fade">
                <div id="menu_vz">
                <ul id="verticalmenu" class="glossymenu"  style="border-top: 1px solid #C7C7C7; ">
                <?= BooksList::widget(['testament' =>  BookTableMap::COL_TESTAMENT_OT])?>
                </ul><br /><span style="padding-left: 10px; font-size:80%">* - неканонические книги</span><br />

                </div>
                </div>
            </div>
    </div>
<!-- КОНЕЦ МЕНЮ -->
<br/>
        <!-- BANNERs -->
        <!--noindex-->
        <div class="pano-banners calendar_inside text-center">
                <br />
                <a href="https://fond.predanie.ru/blago/340442/" class="btn btn-lg btn-danger btn-shadow">Помочь нашему проекту</a><br />
                <br />
                <a href="/banners" class="btn btn-lg btn-warning btn-shadow">Наши баннеры</a>
                <div class="banners" align="center">
                    <!-- Put this div tag to the place, where the Like block will be -->
                    <div id="vk_like" style="width: 100px"></div>
                    <br />
                    <div id="fb-root" style="width: 100px"></div>
                    <div class="fb-like" data-layout="box_count" data-action="like" data-show-faces="true" data-share="false" style="width: 100px"></div>
                    <br /><br />

                    <div id="ok_shareWidget"></div>
                </div>
        </div>
        <!--/noindex-->
        <!-- END BANNERs -->
        </div>
        <!--центральный блок -->
        <div class="col-sm-6 page-control">
            <div class="page-content"><hr />
            <?=$contents?>
                     
            </div>
        </div>
        <!--правый блок -->
        <div class="col-md-3">
          <div class="calendar_inside box3 text-center">
            <h4>Наши друзья:</h4>
<a href="http://fond.predanie.ru/?banner=1" target="_blank"><img src="https://ekzeget.ru/IMG/partners/banner_predanie-200x100-01.gif"></a><br /><br />
<a href="http://www.bogoslov.ru" target="_blank"><IMG src="https://ekzeget.ru/IMG/partners/banner_bogoslov_293x79.gif" WIDTH=200 BORDER=0 ALT="Богослов.ру"></a><br /><br />
<!-- banner Elitcy was add 26.07.17 -->
<a href="https://dialog.elitsy.ru/"><img src="https://s3-eu-west-1.amazonaws.com/elitsy/static/b/questions_elitsy_200x90.png" border="0" alt="православная социальная сеть «Елицы»" width="200" height="90" /></a><br />
</div><br />
<div align="center">

<a href="http://orphus.ru" id="orphus" target="_blank"><img alt="Система Orphus" src="<?=$base_url?>/assets/IMG/orphus.gif" border="0" width="125" height="115" /></a>
</div>
        </div>
   
</div>
</section>

<footer class="footer_pano">
<!--noindex-->
<div id="message">
<a id="toTop" href="#"><img src="<?=$base_url?>/assets/IMG/vverh.png" /></a></div>
<div class="container">
        <div class="footer-menu">
            <div class="row">
                <div class="col-md-3"><a href="/.."><img src="<?=$base_url?>/assets/IMG/logo.png" class="logo-bottom" title="Экзегет. Библия и толкования"></a></div>
                <div class="col-md-6">
                    <a href="<?=$base_url?>/pravila"> Правила</a> |
                    <a href="<?=$base_url?>/new_tolk">Обновления</a> |
                    <a href="<?=$base_url?>/generator"> Генератор ссылок</a> |
                    <a href="<?=$base_url?>/search"> Поиск</a> |
                    <a href="<?=$base_url?>/zap"> Гостевая</a> <br />
                    <a href="<?=$base_url?>/propovedi">Проповеди</a> |
                    <a href="<?=$base_url?>/slovari">Словари</a> |
                    <a href="<?=$base_url?>/maps"> Карты</a> |
                    <a href="<?=$base_url?>/tolks_allp"> Экзегеты</a> |
                    <a href="<?=$base_url?>/lektorij"> Лекторий</a> | <a href="https://pda.ekzeget.ru/">Мобильная версия</a>
                    </div>
                    <div class="col-md-3 text-right">
                        <a href="<?=$base_url?>/donation" class="btn btn-lg btn-donation">Пожертвовать</a>     
                </div>
            </div>
        </div>
        <hr>
        <div class="banners-bottom">
            <a href="http://www.bogoslov.ru" target="_blank"><img src="https://ekzeget.ru/IMG/partners/banner_88x31.png" height=31 width=88 BORDER=0 alt="Богослов.ру"></a> &nbsp; 
                <a href="https://fond.predanie.ru/?banner=1" target="_blank"><img src="https://ekzeget.ru/IMG/partners/banner_predanie-88x31_0.gif"></a> &nbsp; 
                <a href='http://www.barysh-eparhia.ru/' target="_blank"><img src='https://ekzeget.ru/IMG/partners/banner_mini.png' width=88 height=34 title="Барышская епархия" alt='Барышская епархия'></a> &nbsp; 
                <a href="http://www.андреевский-храм.рф/" target="_blank"><img
                src="https://ekzeget.ru/IMG/partners/Banner.png"
                title="Храм апостола Андрея Первозванного" width=88 height=31 border=0></a> &nbsp; 
                <!--begin of www.hristianstvo.ru
                <a href="https://www.hristianstvo.ru/?from=13606" target="_blank"><img
                src="https://www.hristianstvo.ru/images/ru-88x31-lightgray1.gif"
                title="Православное христианство.ru" width=88 height=31 border=0></a> &nbsp; 
                end of www.hristianstvo.ru-->

                <!-- Yandex.Metrika informer --> &nbsp; 
                <a href="https://metrika.yandex.ru/stat/?id=23728522&amp;from=informer"
                target="_blank" rel="nofollow"><img src="https://informer.yandex.ru/informer/23728522/2_1_FFFFFFFF_EFEFEFFF_0_pageviews"
                style="width:80px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры)" onclick="try{Ya.Metrika.informer({i:this,id:23728522,lang:'ru'});return false}catch(e){}" /></a> &nbsp; 
                <!-- /Yandex.Metrika informer -->
                <noscript><div><img src="https://mc.yandex.ru/watch/23728522" style="position:absolute; left:-9999px;" alt="" /></div></noscript> 
                <!-- /Yandex.Metrika counter -->

                <noscript><div style="position:absolute;left:-10000px;">
                <img src="//top-fwz1.mail.ru/counter?id=2566972;js=na" style="border:0;" height="1" width="1" alt="Рейтинг@Mail.ru" />
                </div></noscript> 
                <!-- //Rating@Mail.ru counter -->
                <!-- Rating@Mail.ru logo -->
                <a href="https://top.mail.ru/jump?from=2566972">
                <img src="//top-fwz1.mail.ru/counter?id=2566972;t=361;l=1" 
                style="border:0;" height="18" width="88" alt="Рейтинг@Mail.ru" /></a>
                <!-- //Rating@Mail.ru logo --> 
        </div>
            <div class="founder text-right"><b>&copy; С. Жидков, 2011 - 2017 гг.</b></div>
<!--/noindex-->
        </div>
</footer>
<script type="text/javascript" src="<?=$assets?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?=$assets?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=$assets?>/orphus/orphus.js"></script>
<script defer type="text/javascript" src="<?=$assets?>/js/head_scripts.js"></script><!-- все скрипты в одном -->
<!-- Rating@Mail.ru counter -->
<script defer type="text/javascript" src="<?=$assets?>/js/Rating.Mail.js"></script> 
<script type="text/javascript">
$(document).ready(function(){ 
  $("#myTab a").click(function(e){
    e.preventDefault();
    $(this).tab('show');
  });
});
</script> 
<script> 
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){ 
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o), 
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m) 
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga'); 

ga('create', 'UA-86114429-1', 'auto'); 
ga('send', 'pageview'); 

</script> 
</body></html>