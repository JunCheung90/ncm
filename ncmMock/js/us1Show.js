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
  for (var i=2; i<7; i++) {
    HighLight.highlightSameRow(rows, i, 2);
  }
  
   // horizontal slider 
  $("#slider-horizontal").slider({
      orientation: "horizontal",
      range: "min",
      min: 0,
      max: 2,
      step: 1,
      value: 0,
      slide: function (event, ui) {
        var weight = ui.value;
        controlTable(weight, totalRows);
      }
  });
  var totalRows = $('.mergeTable>tbody>tr');
  controlTable($("#slider-horizontal").slider("value"), totalRows);
});

function controlTable(weight, totalRows) {
  var typeArr = ['全部联系人', '推荐合并', '需要合并'];
  $("#amount").val(typeArr[weight]);
  weight = parseInt(weight);
  if (weight == 0) {
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
    var td = $(rows[i]).find('td:eq(7)');
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
