/**
 * Created with JetBrains WebStorm.
 * User: liurihui
 * Date: 12-8-8
 * Time: 下午4:59
 * To change this template use File | Settings | File Templates.
 */
$().ready(function() {
    var tables = getAllTables();
    makeAllTablesSortable(tables);
});


function getAllTables(){
    return document.getElementsByClassName('sortTable');
}

function makeAllTablesSortable(tables){
    for (var i = 0; i < tables.length; i++) {
        markEachColumnInHead(tables[i])
        makeSortable(tables[i]);
    }
}

function markEachColumnInHead(table){
    var ths = getAllThs(table);
    for (var i = 0; i < ths.length; i++) {
        ths[i].setAttribute('column', i);
    }
}

function makeSortable(table){
    var ths = getAllThs(table);
    for (var i = 0; i < ths.length; i++) {
        ths[i].onclick = sortTable;
    }
}

function getAllThs(table){
    var thead = getThead(table);
    var tr = getTr(thead);
    var ths = getThs(tr);
    return ths;
}

function getThead(table){
    return getFirstChildNamed(table, 'thead');
}

function getFirstChildNamed(node, tagName){
    var childNodes = node.childNodes;
    for (var i = 0; i < childNodes.length; i++) {
        if (childNodes[i].nodeName.toLowerCase() == tagName)
            return childNodes[i];
    }
}

function getTr(thead){
    return getFirstChildNamed(thead, 'tr');
}

function getThs(tr){
    return getAllChildNamed(tr, 'th');
}

function getAllChildNamed(node, tagName){
    var result = [];
    var childNodes = node.childNodes;
    for (var i = 0; i < childNodes.length; i++) {
        if (childNodes[i].nodeName.toLowerCase() == tagName) {
            result.push(childNodes[i]);
        };
    }
    return result;
}

function sortTable(event){
    var th = event.target;
    var table = th.parentNode.parentNode.parentNode;
    changeAscendDescend(th);
    removeOtherColumnsAscendDescend(th);
    var column = th.getAttribute('column');
    sortTableInOrder(table, column, isAscend(th));
}

function changeAscendDescend(th){
    if (isAscend(th)) {
        th.className = th.className.replace('ascend', 'descend');
    }
    else
    if (isDescend(th)) {
        th.className = th.className.replace('descend', 'ascend');
    }
    else {
        th.className += ' ascend';
    }
}

function isAscend(th){
    return th.className.indexOf('ascend') >= 0;
}

function isDescend(th){
    return th.className.indexOf('descend') >= 0;
}

function removeOtherColumnsAscendDescend(th){
    var tr = th.parentNode;
    var ths = getAllChildNamed(tr, 'th');
    for (var i = 0; i < ths.length; i++) {
        if (ths[i] != th) {
            ths[i].className = ths[i].className.replace('ascend', '');
            ths[i].className = ths[i].className.replace('descend', '');
        }
    }
}

function sortTableInOrder(table, column, isAscend){
    var rows = removeAllRows(table);
    sortRows(rows, column, isAscend);
    //applyAlternativeClass(rows);
    HighLight.removeHighlight(rows);
    if (column > 1) {
        HighLight.highlightSameRow(rows, column, 2);
    }
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

function sortRows(rows, column, isAscend){
    rows.sort(function(rowA, rowB){
        var a = getCellValue(rowA, column);
        var b = getCellValue(rowB, column);
        if (a == b)
            return 0;
        if (a < b && isAscend)
            return -1;
        if (a > b && isAscend)
            return 1;
        if (a < b && !isAscend)
            return 1;
        if (a > b && !isAscend)
            return -1;
    });
}

function getCellValue(row, column){
    var tds = getAllChildNamed(row, 'td');
    return tds[column].textContent;
}

function applyAlternativeClass(rows){
    for (var i = 0; i < rows.length; i++) {
        if (i % 2 == 0) {
            removeAlternative(rows[i]);
        } else {
            addAlternative(rows[i]);
        }
    }
}

function removeAlternative(row){
    row.className = row.className.replace('alternate', '');
}

function addAlternative(row){
    if(row.className.indexOf('alternate') < 0) row.className += ' alternate';
}

function appendRows(table, rows){
    var tbody = getFirstChildNamed(table, 'tbody');
    for (var i = 0; i < rows.length; i++) {
        tbody.appendChild(rows[i]);
    }
}
