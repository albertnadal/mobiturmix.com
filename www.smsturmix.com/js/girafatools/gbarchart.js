var IMG_PATH = 'stats/';

function SimpleGroupBarChart(node) { 
  bindMethods(this);
  this.div = node;
  this.timer = null;
  //this.getTimeRefresh();
  //this.getUrlAction();
}

SimpleGroupBarChart.prototype.getTimeRefresh = function() {
  this.time_refresh = getNodeAttribute(this.div, 'value');
}

SimpleGroupBarChart.prototype.getUrlAction = function() {
  this.url_action = getNodeAttribute(this.div, 'href');
}

SimpleGroupBarChart.prototype.invoqueJSONdataRequest = function() {
  this.getUrlAction();
  var d  = loadJSONDoc(this.url_action);
  d.addCallback(this.renderChart);
}

SimpleGroupBarChart.prototype.renderChart = function(data) {
//alert('renderitzant chart');
  var row_bars = TR({'class': 's1', 'valign': 'bottom'});
  var row_text = TR({'class': 's3'});
  var row_legend = TR();
  var num_grups = data[0].text.length;
  var num_tipus = data[0].legend.length;

//alert('iniciant renderitzacio');
  for (var i=0; i<data[0].values.length; i++)
  {
	//alert('ok0');
	for (var e=0; e<data[0].values[i].length; e++)
	{
		//alert('gnerant barra');
	  var single_bar = TD({'class': 's2'}, data[0].values[i][e], BR(), IMG({'src': IMG_PATH+'pipe'+e+'v.png', 'height': data[0].height[i][e], 'width': '17'}));
	  row_bars.appendChild(single_bar);
	}
  }

  for (var e=0; e<num_grups; e++)
  {
      var single_tex = TD({'class': 'bottomrow'+e%2+' tablemaintext v2','colspan': num_tipus}, data[0].text[e]);
      row_text.appendChild(single_tex);
  }


	var taulaa = document.createElement('div');
//appendChildNodes
  taulaa.appendChild(TABLE({'class': 'compactstats2'},
					TD({'class': 'msg', 'colspan': i*num_grups, 'align': 'center'},  data[0].title),
					row_bars,
					row_text,
					TD({'class': 'msg', 'colspan': i*num_grups, 'align': 'center'},  data[0].caption, BR(), BR())));


  for (var j=0; j<num_tipus; j++)
  {
      row_legend.appendChild(TD({'class': 'msg'}, IMG({'src': IMG_PATH+'pipe'+j+'.png', 'align': 'top', 'height': '15', 'width': '30'}), ' '+data[0].legend[j]));
      row_legend.appendChild(TD({'class': 'msg', 'width': '20'}));
  }
  var peuu = document.createElement('div');;
  peuu.appendChild(row_legend);

  this.div.innerHTML = taulaa.innerHTML + peuu.innerHTML;

  this.restartTimer();
}

SimpleGroupBarChart.prototype.cancelTimer = function () {
	if (this.timer) this.timer.cancel();
	this.timer = null;
}

SimpleGroupBarChart.prototype.restartTimer = function () {
	this.cancelTimer();
      this.getTimeRefresh();
	if(this.time_refresh != null) this.timer = callLater(this.time_refresh, this.invoqueJSONdataRequest);
}

function initSimpleGroupBarCharts() {
	//alert('Capturant gbarcharts');
  var barcharts = getElementsByTagAndClassName(null, 'gbarchart');
  for (var i=0; i<barcharts.length; i++)
  {
	//alert('capturat jbarchart');
     barChart = new SimpleGroupBarChart(barcharts[i]);
	//alert('invocant json');
     barChart.invoqueJSONdataRequest();
	//alert('json invcat');
  }
}

addLoadEvent(initSimpleGroupBarCharts);
