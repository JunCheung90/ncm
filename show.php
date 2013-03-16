<?php
  require_once("model/Story.class.php");
  $story = new Story();
  $us2 = $story->userStory2();
  $stat = $us2->stat;
  $totalDuration = $stat->totalDuration;
  $totalCount = $stat->totalCount;
  $top10 = $stat->top10;
  $specificFreqContact = $stat->specificFreqContact;
  
  //平均一次通话时长
  $meanDuration = $totalDuration/$totalCount;
  //平均每月
  $monthDuration = $totalDuration/12;
  //平均一天
  $dayDuration = $monthDuration/30;
  function durationFormat($seconds) {
    $tmp = new stdClass;
    $h = $seconds/3600;
    $m = $seconds%3600/60;
    $s = $seconds%3600%60;
    $tmp->h = sprintf("%02d", $h);
    $tmp->m = sprintf("%02d", $m);
    $tmp->s = sprintf("%02d", $s);

    return $tmp;
  }
  $meanDuration = durationFormat($meanDuration);
  $monthDuration = durationFormat($monthDuration);
  $dayDuration = durationFormat($dayDuration);

  $top10NameArr = array();
  $top10CallOutpPercentArr = array();
  $top10CallInPercentArr = array();
  for ($i = 0; $i < count($top10); $i++) {
    $top10NameArr[] = $top10[$i]->name;
    $top10CallOutpPercentArr[] = round($top10[$i]->callOutTotal / $totalDuration * 100, 2);
    $top10CallInPercentArr[] = round($top10[$i]->callInTotal / $totalDuration * 100, 2);
  }


  $SFCCallLog = $specificFreqContact->callLog;
  usort($SFCCallLog, "cmp");
  function cmp($a, $b)
  {
    if ($a[0] == $b[0])
      return 0;
    else if ($a[0] > $b[0])
      return 1;
    else if ($a[0] < $b[0])
      return -1;
  }
  for ($i = 0; $i < count($SFCCallLog); $i++) {
    $SFCCallLog[$i][0] *= 1000; 
  }
  $SFCCallLog[] = array($SFCCallLog[$i-1][0], 0);
  $SFCCallLog[] = array(time()*1000, 0);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>YoYo</title>
  <meta name="description" content="the web show user story">
  <meta name="author" content="YoYo">

  <!-- Le styles -->
  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/bootstrap-responsive.css" rel="stylesheet">
  <link href="css/common.css" rel="stylesheet">
  <link href="css/home.css" rel="stylesheet">
  
  <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <!-- Le fav and touch icons (补上小图标)-->
  <link rel="shortcut icon" href="img/index/favicon.png">
  
</head>
<body class="home">
  <div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container-fluid">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <a class="brand" href="index.html"><img src="img/index/logo.png" alt="logo" title="logo" /></a>
        <div class="nav-collapse">
          <ul class="nav">
            <li><a href="contact.php">联系人管理</a></li>
            <li class="active"><a href="#">趣味分析</a></li>
          </ul>
          <ul class="nav pull-right" >
            <li class="">
              <a href="#" class="userName">
                 <img src="img/index/jixiang.png" alt="avatar" title="avatar" class="littleAvatar" />
                 万伟祥
              </a>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
  </div><!--/.navbar -->

  <div class="container">
    <div class="row-fluid">
   
        <div id="content">
          <section class="userInfo">
            <img src="img/index/jixiang.png" class="avatar" alt="avatar" title="avatar" />
            <div class="userInfoDetail">
              <p>
                <span class="name">万伟祥</span>
              </p>
              <p>
                <span class="gender">男</span>
                <span>.</span>
                <span class="gender">24岁</span>
              </p>
              <p>
                <span class="country">中国</span>
                <span>.</span>
                <span class="province">广东</span>
                <span>.</span>
                <span class="city">广州</span>
              </p>
            </div>
          </section><!--/.userInfo -->

          <section class="graph">
            <div class="tabbable">
              <ul class="nav nav-pills">
                <li class="active">
                  <a id="task" href="#tab1" data-toggle="tab">
                    <span class="text">话痨</span>
                  </a>
                  <span class="cusp1"></span>
                </li>
                <li class="">
                  <a id="member" href="#tab2" data-toggle="tab">
                    <span class="text">谁用了最多话费</span>
                  </a>
                  <span class="cusp1"></span>
                </li>
                <li class="">
                  <a id="discuss" href="#tab3" data-toggle="tab">
                    <span class="text">好久没联系</span>
                  </a>
                  <span class="cusp1"></span>
                </li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="tab1">
                  <div id="speakMuch_container"></div>
                </div>
                <div class="tab-pane " id="tab2">
                  <div id="top10_container"></div>
                </div>
                <div class="tab-pane " id="tab3">
                  <div id="spc_Container"></div>
                </div>
              </div>
            </div>
          </section><!--/.graph -->
        </div><!--/#content-->
      
    </div><!--/row-->

    <hr>
    <footer>
        <!-- <p>&copy; YoYo 2012</p> -->
    </footer>

  </div><!--/.container-->

<!-- Le javascript -->
  <script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
  <script src="js/bootstrap.js" type="text/javascript"></script>
  <script src="js/highcharts-cn.js" type="text/javascript"></script>
  <script>
    var GraphData = {};
    //话痨
    
    //top10
    GraphData.nameArr = <?php echo json_encode($top10NameArr); ?>;
    GraphData.callOutArr = <?php echo json_encode($top10CallOutpPercentArr); ?>;
    GraphData.callInArr = <?php echo json_encode($top10CallInPercentArr); ?>;

    //好久没联系
    GraphData.SFCName = '<?php echo $specificFreqContact->name; ?>';
    GraphData.SFCCallLog = <?php echo json_encode($SFCCallLog); ?>;
  </script>
  <script src="js/main.js" type="text/javascript"></script>
  <script src="js/highcharts-theme-gray.js" type="text/javascript"></script>

</body>
</html>