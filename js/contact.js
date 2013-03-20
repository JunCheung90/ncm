$(document).ready(function() {
  //去重
  $('#uniq').click(function() {
    var uniqColumn = $('.sortTable th.ascend')[0] || $('.sortTable th.descend')[0];
    if (uniqColumn) {
      var columnIndex = $(uniqColumn).index();
      var table = $('.sortTable')[0];

      uniqTable(table, columnIndex);
    }
  });

  //高亮合并表格
  var rows = $('.mergeTable>tbody>tr');
  for (var i=1; i<6; i++) {
    HighLight.highlightSameRow(rows, i, 1, true);
  }
  
  var totalRows = $('.mergeTable>tbody>tr');
  //默认显示主动合并
  controlTable(2, totalRows, 0);
  $(".button-group .btn").click(function (e) {
    var i = $(this).index();
    $('.stat-info p').hide();
    $('.stat-info .info'+i).show();
    controlTable(2-i, totalRows, i);
    $(this).siblings().removeClass('btn-success btn-green');
    $(this).addClass('btn-success btn-green');
  });

});

function controlTable(weight, totalRows, index) {
  if (index == 2) {
    $('#allContactBlock').show();
    $('#mergeBlock').hide();
  }
  else {
    $('#allContactBlock').hide();
    filterMergeTable(weight, totalRows);

    $('#mergeBlock').show();
  }
}

function filterMergeTable(weight, totalRows) {
  var table = $('.mergeTable')[0];
  removeAllRows(table);
  var rows = totalRows;
  rows = getRowsByWeight(rows, weight);

  appendRows(table, rows);
}

function getRowsByWeight(rows, weight) {
  var rowsResult = []; 
  for (var i=0; i<rows.length; ) {
    var td = $(rows[i]).find('td:eq(6)');
    var getWeight = parseInt(td.find('.j_weight').text());
    if (getWeight == weight) {
      rowsResult.push(rows[i]);
      rowsResult.push(rows[i+1]);  
    }
    i += 2;
  }

  return rowsResult;
}

function uniqTable(table, column) {
    var rows = removeAllRows(table);
    rows = uniqRows(rows, column);
    appendRows(table, rows);

    //重置首列
    var jq_trs = $('.sortTable>tbody>tr');
    for (var i=0; i<jq_trs.length; i++) {
        $(jq_trs[i]).find('td:eq(0)').text(i+1);
    }
  }

  function removeAllRows(table){
      var tbody = getFirstChildNamed(table, 'tbody');
      var rows = getAllChildNamed(tbody, 'tr');
      for (var i = 0; i < rows.length; i++) {
          tbody.removeChild(rows[i]);
      }
      return rows;
  }

  function uniqRows(rows, column) {
    var rowsResult = []; 
    rowsResult.push(rows[0]);
    for (var i=1, j=0; i<rows.length; i++) {
      if (getCellValue(rows[i], column) != getCellValue(rowsResult[j], column)) {
        rowsResult.push(rows[i]);
        j++;
      }
    }

    return rowsResult;
  }

  function getCellValue(row, column){
    var tds = getAllChildNamed(row, 'td');
    return tds[column].textContent;
  }

  function appendRows(table, rows) {
    var tbody = getFirstChildNamed(table, 'tbody');
    for (var i = 0; i < rows.length; i++) {
        tbody.appendChild(rows[i]);
    }
  }
