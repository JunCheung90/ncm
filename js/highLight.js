var HighLight = {};
HighLight.version = "0.5.5";

HighLight.removeHighlight = function (rows) {
  for (var i=0; i<rows.length; i++) {
    var tds = getAllChildNamed(rows[i], 'td');
    for (var j=0; j<tds.length; j++) {
      tds[j].className = '';
    }
  }
}

HighLight.highlightSameRow = function (rows, column, offset, jumpFlag) {
  for (var i=0; i<rows.length-1; i++) {
    if (HighLight.getCellValue(rows[i], column) != '' && HighLight.getCellValue(rows[i], column) == HighLight.getCellValue(rows[i+1], column)) {
      HighLight.hightlightCell(rows[i], column, offset);
      HighLight.hightlightCell(rows[i+1], column, offset);
    }
    if (jumpFlag)
      i++;
  }
}

HighLight.hightlightCell = function (row, column, offset) {
  var tds = HighLight.getAllChildNamed(row, 'td');
  var classNameArr = ["red", "green", "blue", "yellow"];
  var classNameIndex;

  switch (parseInt(column) - offset) {
    case 0:
      classNameIndex = 3;
      break;
    case 1:
      classNameIndex = 2;
      break;
    case 2:    
      classNameIndex = 2;
      break;
    case 3:    
      classNameIndex = 0;
      break;
    case 4:    
      classNameIndex = 1;
      break;
    default:
      break;        
  }
  //$(tds[column]).addClass(classNameArr[classNameIndex]);
  $(tds[column]).addClass("highlight");
}

HighLight.getCellValue = function (row, column){
  var tds = HighLight.getAllChildNamed(row, 'td');
  return tds[column].textContent;
}

HighLight.getAllChildNamed = function (node, tagName){
    var result = [];
    var childNodes = node.childNodes;
    for (var i = 0; i < childNodes.length; i++) {
        if (childNodes[i].nodeName.toLowerCase() == tagName) {
            result.push(childNodes[i]);
        };
    }
    return result;
}