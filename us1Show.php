<?php
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
  define('SCRIPT', 'us1Show');
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

<html>
  <head>
     <title>user story 1</title>
     <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/jquery-ui-1.8.16.custom.css" rel="stylesheet">
    <link href="css/us1Show.css" rel="stylesheet">
  </head>
  <body> 
    <herder>
     <!--  <div class="colorWeight">
        <h3>合并权重：</h3>
        <span class="red colorBlock">8</span>
        <span class="green colorBlock">4</span>
        <span class="blue colorBlock">2</span>
        <span class="yellow colorBlock">1</span>
      </div> -->
      <div class='mergeWeightSlider'>
        <div id="slider-horizontal"></div>
        <p class="text">
          <!-- <span for="amount">当前指数:</span> -->
          <input type="text" id="amount" style="border:0; color:#f6931f; font-weight:bold; width: 100px; height:24px;" />
        </p>
      </div>  
    </header>

    <div id="mergeBlock">
      <h3>有重复信息的联系人列表</h3> 
      <strong>protip</strong>: 相邻两行颜色相同表示当前列的信息相同</hr>
      <table class="table mergeTable table-bordered">
        <thead>
        <tr>
          <th>条目</th>
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
            <td><?= $i+1 ?></td>
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
            <td></td>
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
      <h3>所有联系人列表</h3> 
      <button id="uniq">去重</button>
      <strong>protip</strong>: 点击表头切换排序顺序</hr>
      <table class="table sortTable">
        <thead>
        <tr>
          <th>条目</th>
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
            <td><?= $i+1 ?></td>
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

    <script src = "js/jquery-1.8.3.min.js" type = "text/javascript"></script>
    <script src = "js/bootstrap.js" type = "text/javascript"></script>
    <script src = "js/jquery-ui-1.9.2.custom.min.js" type = "text/javascript"></script>
    <script src = "js/highLight.js" type = "text/javascript"></script>
    <script src = "js/sorter.js" type = "text/javascript"></script>
    <script src = "js/us1Show.js" type = "text/javascript"></script>
  </body>
</html>
