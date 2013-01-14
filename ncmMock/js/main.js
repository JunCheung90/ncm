$(function () {
	var speakMuch_chart;
    var spc_chart;
    var top10_chart;
  
    $(document).ready(function() {
    	//话痨
    	var speakMuch_options = {
            chart: {
                renderTo: 'speakMuch_container',
                type: 'areaspline'
            },
            title: {
                text: '月通话时长对比'
            },
            xAxis: [{
                categories: ['1月', '2月', '3月', '4月', '5月', '6月',
                    '7月', '8月', '9月', '10月', '11月', '12月']
            }],
            yAxis: [{ // Primary yAxis
                labels: {
                    formatter: function() {
                        return (Math.abs(this.value) / 1000) + 'K';
                    }
                },
                title: {
                    text: '通话时长（s）'
                }
            }],
            credits: {
                enabled: false
            },
            series: [{
                name: '豆豆',
                color: '#14B2AE',
                data: [183010, 213465, 178932, 152342, 184343, 265344, 135434, 148543, 216423, 194143, 254556, 144534]
    
            }, {
                name: '平均值',
                color: '#849D93',
                data: [73430, 69343, 45343, 24534, 18279, 21534, 25201, 26835, 23393, 18723, 13439, 29436]
            }]
        };

    	//spc, 好久没联系
        var spc_options = {
            chart: {
                renderTo: 'spc_Container',
                spacingRight: 20,
                type: 'areaspline',
                zoomType: 'x'
            },
            title: {
                text: '最近一年通话频率',
                x: -20 //center
            },
            xAxis: {
                type: 'datetime',
                maxZoom: 14 * 24 * 3600000, //两周
                tickWidth: 0,
                gridLineWidth: 1,
                labels: {
                    align: 'center',
                    x: 0,
                    y: 15,
                    formatter: function() {
                      return Highcharts.dateFormat('%m-%d', this.value);
                    }
                }
            },
            yAxis: {
                title: {
                    text: '通话时长（s）'
                },
                labels: {
                  formatter: function() {
                    return Highcharts.numberFormat(this.value, 0, '.', ',');
                  }
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function() {
                  var duration = this.y;
                  var type = '';
                  if (duration == 0) {
                    type = '未接';
                  }else if(duration > 0) {
                    type = '呼出';
                  }else {
                    type = '呼进';
                  }
                  duration = duration>=0?duration:-duration;
                  return Highcharts.dateFormat('%Y-%m-%d, %H:%M', this.x) + '<br/>'+type+" "+ duration +' s';
                }
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                areaspline: {
                   fillColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, '#14B2AE'],
                        [1, 'rgba(2,0,0,0)']
                    ]
                  }
                }
            },
            series: [{
                name: 'fdfadf',
                color: '#14B2AE',
                lineWidth: 2,
                marker: {
                        enabled: false,
                        states: {
                            hover: {
                                enabled: true,
                                radius: 5
                            }
                        }
                    },
                shadow: false,
                states: {
                    hover: {
                        lineWidth: 2
                    }
                },
                data: [[1333349621000,1],[1333349622000,-2],[1333349623000,1],[1333349641000,-5],[1333349661000,1]]
            }]
        };

      //top10
      var top10_options = {
            chart: {
                renderTo: 'top10_container',
                type: 'pie'
            },
            title: {
                text: 'Top10 最常联系人'
            },
            xAxis: {
              type: 'datetime'
            },
            yAxis: {
                title: {
                    text: '总通话时长百分比'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    shadow: false
                }
            },
            tooltip: {
              valueSuffix: '%'
            },
            credits: {
                enabled: false
            },
            series: [{
                name: '总时长',
                data: null,
                size: '60%',
                dataLabels: {
                    formatter: function() {
                        return this.y > 5 ? this.point.name : null;
                    },
                    color: 'white',
                    style: {fontSize:'13px'},  
                    distance: -30
                }
            }, {
                name: '时长',
                data: null,
                innerSize: '60%',
                dataLabels: {
                    formatter: function() {
                        // display only if larger than 1
                        return this.y > 1.5 ? '<b>'+ this.point.name +':</b> '+ this.y +'%'  : null;
                    }
                }
            }]
        };

      function initialize() {
        //话痨
		// speakMuch_options.series[0].data = GraphData.;
        //top10
        var nameArr = GraphData.nameArr,
    	callOutArr = GraphData.callOutArr,
    	callInArr = GraphData.callInArr,
        colors = Highcharts.getOptions().colors,
        categories = nameArr,
        data = [];  
		for (var i = 0; i < nameArr.length; i++) {
			var singleData = {};
			singleData.name = nameArr[i];
			singleData.y = callOutArr[i] + callInArr[i];
			singleData.color = Highcharts.Color(colors[i]).brighten(-0.1).get();
			var drilldown = {};
			drilldown.categories = [nameArr[i]+' 呼出', nameArr[i]+' 呼进'];
			drilldown.data = [callOutArr[i], callInArr[i]];

			drilldown.color = colors[i];
			singleData.drilldown = drilldown;
			data.push(singleData);
		}  

		var totalData = [];
		var detailData = [];
		for (var i = 0; i < data.length; i++) {

		  // add total data
		  totalData.push({
		      name: categories[i],
		      y: data[i].y,
		      color: data[i].color
		  });

		  // add callout and callin data
		  for (var j = 0; j < data[i].drilldown.data.length; j++) {
		      var brightness = 0.2 - (j / data[i].drilldown.data.length) / 5 ;   
		      detailData.push({
		          name: data[i].drilldown.categories[j],
		          y: data[i].drilldown.data[j],
		          color: Highcharts.Color(data[i].color).brighten(brightness).get()
		      });
		  }
		}
		top10_options.series[0].data = totalData;
		top10_options.series[1].data = detailData;

        //好久没联系
        spc_options.series[0].name = GraphData.SFCName;   
        spc_options.series[0].data = GraphData.SFCCallLog;

        drawGraph();
      };
      initialize();

      function drawGraph() {
        speakMuch_chart = new Highcharts.Chart(speakMuch_options);
        spc_chart = new Highcharts.Chart(spc_options);
        top10_chart = new Highcharts.Chart(top10_options);
      }
      
      //切换tab, render again
      $('.graph a[data-toggle="tab"]').on('shown', function (e) {
        e.target; // activated tab
        drawGraph();
      })
    
    });

})