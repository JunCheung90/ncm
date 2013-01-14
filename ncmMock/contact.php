﻿<?php
  require_once("model/Story.class.php");
  $story = new Story();
  $result = $story->userStory1();
  $contacts = $result->Contacts;
  $callLogs = $result->CallLogs;
 
   //统计，20%重复（80%手机，20%Email）70%有电话号码，5%有IM，3%有SN；5%从不联系，15%频繁联系
  //合并权重
  $mergeWeightArr = $result->MergeWeight;
  function getContactById($contacts, $id) {
    return $contacts[$id-10000];
  }

  //分页模块
  define('SCRIPT', 'contact');
  define('PAGE_COUNT', 50);

  function pager ($pageType, $arr) {
    if (isset($_GET[$pageType])) {
      $_page = $_GET[$pageType];
      if (empty($_page) || $_page < 0 || !is_numeric($_page)) {
        $_page = 1;
      } else {
        $_page = intval($_page);
      }
    }
    else {
       $_page = 1;
    }

    $_pagesize = PAGE_COUNT;
    $_num = count($arr);
    if ($_num == 0) {
      $_pageabsolute = 1;
    } else {
      $_pageabsolute = ceil($_num / $_pagesize);
    }
    if ($_page > $_pageabsolute) {
      $_page = $_pageabsolute;
    }
    $_pagenum = ($_page - 1) * $_pagesize;

    return array('page'=>$_page, 'pagenum'=>$_pagenum, 'pageabsolute'=>$_pageabsolute);
  }
  $contactsNum = count($contacts);
  $contactsPager = pager('contactPage', $contacts); 
  $contactPage = $contactsPager['page'];
  $contactsPagenum = $contactsPager['pagenum'];
  $contactsPageabsolute = $contactsPager['pageabsolute'];
  $contactsArrTmp = array_slice($contacts, $contactsPagenum, PAGE_COUNT); 
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>YoYo 我的联系人</title>
  <meta name="description" content="the web show user contact">
  <meta name="author" content="YoYo">

  <!-- Le styles -->
  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/bootstrap-responsive.css" rel="stylesheet">
  <link href="css/common.css" rel="stylesheet">
  <link href="css/contact.css" rel="stylesheet">
  
  <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <!-- Le fav and touch icons (补上小图标)-->
  <link rel="shortcut icon" href="img/index/favicon.png">
  
</head>
<body class="home">
  <div class="navbar">
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
            <li><a href="show.php">我的主页</a></li>
            <li class="active"><a href="#">联系人</a></li>
            <li><a href="#">其他</a></li>
          </ul>
          <ul class="nav pull-right" >
            <li class="">
              <a href="#" class="userName">
                 <img src="img/index/doudou.png" alt="avatar" title="avatar" class="littleAvatar" />
                 豆豆
              </a>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    <span class="cusp1"></span>
  </div><!--/.navbar -->

  <div class="container">
    <div class="row-fluid">
   
        <div id="content">
          <section class="stat-info">
            <p class="info0 ">豆豆，在你的<span>101</span>个联系人里有<span>2</span>个是同一个人哦!</p>
            <p class="info1 hidden">再找找下面的<span>20</span>个人，或许是重复的呢!</p>
            <p class="info2 hidden">嘻嘻，这就是你的全部<span>101</span>个联系人</p>
          </section>

          <section class="contact-table">
            <div class="button-group">
              <button class="btn btn-success btn-green">重复联系人</button>
              <button class="btn">部分信息重复</button>
              <button class="btn">全部联系人</button>
            </div>  

            <div id="mergeBlock">
              <table class="table mergeTable">
                <thead>
                <tr>
                  <th>用户ID</th>
                  <th>用户名</th>
                  <th>手机号码</th>
                  <th>家庭电话</th>
                  <th>Email</th>
                  <th>QQ</th>
                  <th class="hide">合并指数</th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                    for ($i = 0; $i < count($mergeWeightArr); $i++) {
                      $contactPair = $mergeWeightArr[$i];
                      $contact1 = getContactById($contacts, $contactPair->contactId1);
                      $contact2 = getContactById($contacts, $contactPair->contactId2);
                      $weight = $contactPair->weight;
                  ?>
                  <tr>
                    <td><?= $story->getId($contact1) ?></td>
                    <td><?= $story->getName($contact1) ?></td>
                    <td><?= $story->getMobilePhone($contact1) ?></td>
                    <td><?= $story->getHomePhone($contact1) ?></td>
                    <td><?= $story->getEmail($contact1) ?></td>
                    <td><?= $story->getQQ($contact1) ?></td>
                    <td rowspan="2" class="hide">
                      <span class="weight<?= $weight ?> colorBlock j_weight"><?= $weight ?></span>
                    </td>
                  </tr>
                  <tr>
                    <td><?= $story->getId($contact2) ?></td>
                    <td><?= $story->getName($contact2) ?></td>
                    <td><?= $story->getMobilePhone($contact2) ?></td>
                    <td><?= $story->getHomePhone($contact2) ?></td>
                    <td><?= $story->getEmail($contact2) ?></td>
                    <td><?= $story->getQQ($contact2) ?></td>
                  </tr>
                  <?php
                    }
                  ?>  
                </tbody>
              </table>
            </div>

            <div id="allContactBlock">  
              <table class="table sortTable">
                <thead>
                <tr>
                  <th>用户ID</th>
                  <th>用户名</th>
                  <th>手机号码</th>
                  <th>家庭电话</th>
                  <th>Email</th>
                  <th>QQ</th>
                </tr>
                </thead>
                <tbody>
                  <?php for ($i = 0; $i < count($contactsArrTmp); $i++) {?>
                  <tr>
                    <td><?= $contactsArrTmp[$i]->id ?></td>
                    <td><?= $contactsArrTmp[$i]->StructuredName->DISPLAY_NAME ?></td>
                    <td><?= $contactsArrTmp[$i]->Phone[0]->NUMBER ?></td>
                    <td><?= $contactsArrTmp[$i]->Phone[1]->NUMBER ?></td>
                    <td><?= $contactsArrTmp[$i]->Email[0]->ADDRESS ?></td>
                    <td><?= $contactsArrTmp[$i]->Im[0]->DATA ?></td>
                  </tr>
                  <?php }?>
                </tbody>
              </table>  

              <div class="pagination pagination-centered">
                <ul>
                  <?php 
                    echo '<li><a href="'.SCRIPT.'.php?contactPage='.($contactPage-1).'">上一页</a></li>';
                    for ($i = max($contactPage-5, 1); $i < min($contactPage+5, $contactsPageabsolute); $i++) {
                      if ($contactPage == ($i+1)) {
                        echo '<li class="active"><a href="'.SCRIPT.'.php?contactPage='.($i+1).'">'.($i+1).'</a></li>';
                      } else {
                        echo '<li><a href="'.SCRIPT.'.php?contactPage='.($i+1).'">'.($i+1).'</a></li>';
                      }
                    }
                    echo '<li><a href="'.SCRIPT.'.php?contactPage='.($contactPage+1).'">下一页</a></li>';
                  ?>
                </ul>
              </div>
            </div>

          </section><!--/.table -->
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

  </script>
  <script src="js/highcharts-theme-gray.js" type="text/javascript"></script>
  <script src = "js/highLight.js" type = "text/javascript"></script>
  <script src = "js/sorter.js" type = "text/javascript"></script>
  <script src = "js/contact.js" type = "text/javascript"></script>

</body>
</html>