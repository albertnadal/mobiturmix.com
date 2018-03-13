var IMG_PATH = 'stats/';

function SimpleBarChart(node) { 
  bindMethods(this);
  this.div = node;
  this.timer = null;
  //this.getTimeRefresh();
  //this.getUrlAction();
}

SimpleBarChart.prototype.getTimeRefresh = function() {
  this.time_refresh = getNodeAttribute(this.div, 'value');
}

SimpleBarChart.prototype.getUrlAction = function() {
  this.url_action = getNodeAttribute(this.div, 'href');
}

SimpleBarChart.prototype.invoqueJSONdataRequest = function() {
  this.getUrlAction();
  var d  = loadJSONDoc(this.url_action);
  d.addCallback(this.renderChart);
}

SimpleBarChart.prototype.renderChart = function(data) {
  var row_bars = TR({'class': 's1', 'valign': 'bottom'});
  var row_text = TR({'class': 's3'});

  for (var i=0; i<data[0].values.length; i++)
  {
	var single_bar = TD({'class': 's2'}, data[0].values[i], BR(), IMG({'src': IMG_PATH+'pipeltv.png', 'height': data[0].height[i], 'width': '38'}));
	var single_tex = TD({'class': 'bottomrow0 tablemaintext v4'}, data[0].text[i], BR(), data[0].subtext[i]);
	row_bars.appendChild(single_bar);
	row_text.appendChild(single_tex);
  }

  var taulaa = document.createElement('div');
  taulaa.appendChild(TABLE({'class': 'compactstats'},
					TD({'class': 'msg', 'colspan': i, 'align': 'center'},  data[0].title),
					row_bars,
					row_text,
					TD({'class': 'msg', 'colspan': i, 'align': 'center'},  data[0].caption)));
  this.div.innerHTML = taulaa.innerHTML;
  this.restartTimer();
}

SimpleBarChart.prototype.cancelTimer = function () {
	if (this.timer) this.timer.cancel();
	this.timer = null;
}

SimpleBarChart.prototype.restartTimer = function () {
	this.cancelTimer();
      this.getTimeRefresh();
	if(this.time_refresh != null) this.timer = callLater(this.time_refresh, this.invoqueJSONdataRequest);
}

function initSimpleBarCharts() {
  var barcharts = getElementsByTagAndClassName(null, 'barchart');
  for (var i=0; i<barcharts.length; i++)
  {
     barChart = new SimpleBarChart(barcharts[i]);
     barChart.invoqueJSONdataRequest();
  }
}

addLoadEvent(initSimpleBarCharts);
