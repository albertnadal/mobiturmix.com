
function MobiturmixGeoLoader(id, map, id_map) {
  bindMethods(this);
  this.id = id;
  this.div = this.gebi(id);
  this.map = map;
  this.id_map = id_map;
  this.timer = null;
  this.state_x = 0;
  this.state_y = 0;
  this.city_x = 0;
  this.city_y = 0;
  this.country_x = 0;
  this.country_y = 0;
}

MobiturmixGeoLoader.prototype.gebi = function(id) { return document.getElementById(id); }

MobiturmixGeoLoader.prototype.generateCountryStatesSelector = function(id_country) {
	this.invoqueJSONdataRequest('generate_states_selector', id_country);
}

MobiturmixGeoLoader.prototype.generateStateCitiesSelector = function(id_state) {
        this.invoqueJSONdataRequest('generate_cities_selector', id_state);
}

MobiturmixGeoLoader.prototype.generatePositionateCity = function(id_city) {
        this.invoqueJSONdataRequest('positionate_city', id_city);
}

MobiturmixGeoLoader.prototype.invoqueJSONdataRequest = function(op, param) {
  if(op=='generate_states_selector')
  {
    var d  = loadJSONDoc('/geo.php?op=get_states&id_country='+param);
    d.addCallback(this.renderStatesSelector);
  }
  else if(op=='generate_cities_selector')
  {
    var d  = loadJSONDoc('/geo.php?op=get_cities&id_state='+param);
    d.addCallback(this.renderCitiesSelector);
  }
  else if(op=='positionate_city')
  {
    var d  = loadJSONDoc('/geo.php?op=get_city&id_city='+param);
    d.addCallback(this.positionateCity);
  }
}

MobiturmixGeoLoader.prototype.positionateCity = function(data) {

  if (GBrowserIsCompatible())
  {
    this.map.setCenter(new GLatLng(data.x,data.y), 12);
    m = this.gebi(this.id_map);
    m.style.display='block';
    this.city_x = data.x;
    this.city_y = data.y;
  }
}

MobiturmixGeoLoader.prototype.renderStatesSelector = function(data) {

  var acumula = DIV();

  var opcio = OPTION({'value': 0}, '');
  var aux = DIV();
  aux.appendChild(opcio);
  acumula.appendChild(aux);

  for (var i=0; i<data.id_state.length; i++)
  {
	var opcio = OPTION({'value': data.id_state[i]}, data.name_state[i]);
	var aux = DIV();
	aux.appendChild(opcio);
	acumula.appendChild(aux);
  }

var aux = DIV();
aux.appendChild(acumula);

this.div.innerHTML = "<select style='width:194px'  onfocus='set_color(\"y\",this);' onBlur='set_color(\"w\",this);' id='my_state' name='my_state' onchange='mostrar_selector_ciutats(this.options[this.selectedIndex].value)'>"+aux.innerHTML+"</select>";

  if (GBrowserIsCompatible())
  {
    this.map.setCenter(new GLatLng(data.x,data.y), 4);
    m = this.gebi(this.id_map);
    m.style.display='block';
    this.country_x = data.x;
    this.country_y = data.y;
  }
}

MobiturmixGeoLoader.prototype.renderCitiesSelector = function(data) {

  var selector = SELECT({'style': 'width: 194px;'});
  var acumula = DIV();

  var opcio = OPTION({'value': 0}, '');
  var aux = DIV();
  aux.appendChild(opcio);
  acumula.appendChild(aux);

  for (var i=0; i<data.id_city.length; i++)
  {
        var opcio = OPTION({'value': data.id_city[i]}, data.name_city[i]);
        var aux = DIV();
        aux.appendChild(opcio);
        acumula.appendChild(aux);
  }

  if(data.id_city.length)
  {
    var aux = DIV();
    aux.appendChild(acumula);

    this.div.innerHTML = "<select style='width:194px'  onfocus='set_color(\"y\",this);' onBlur='set_color(\"w\",this);' id='my_city' name='my_city' onchange='posicionar_ciutat(this.options[this.selectedIndex].value)'>"+aux.innerHTML+"</select>";

    if (GBrowserIsCompatible())
    {
      this.map.setCenter(new GLatLng(data.x,data.y), 6);
      m = this.gebi(this.id_map);
      m.style.display='block';
      this.state_x = data.x;
      this.state_y = data.y;
    }
  }
  else
  {
      e = this.gebi('capa_ciutats');
      e.style.display = 'none';
  }
}

MobiturmixGeoLoader.prototype.cancelTimer = function () {
	if (this.timer) this.timer.cancel();
	this.timer = null;
}

MobiturmixGeoLoader.prototype.restartTimer = function () {
	this.cancelTimer();
      this.getTimeRefresh();
	if(this.time_refresh != null) this.timer = callLater(this.time_refresh, this.invoqueJSONdataRequest);
}

