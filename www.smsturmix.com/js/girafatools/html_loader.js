function HtmlLoader(node, html_file, jscode) { 
  bindMethods(this);
  this.id = node;
  this.url = html_file;
  this.req = null;
  this.jscode = jscode;
}

HtmlLoader.prototype.invoqueHtmlDataRequest= function() {
  this.req = getXMLHttpRequest();
  if (this.req.overrideMimeType) { this.req.overrideMimeType("text/xml"); }
  this.req.open("GET", this.url, true);
  d = sendXMLHttpRequest(this.req).addCallback(this.renderHtml);
}

HtmlLoader.prototype.renderHtml = function(r) {
  e = document.getElementById(this.id);
  e.innerHTML=r.responseText;
  eval(this.jscode);
}

function setHtmlCode(id_area, htmlcode, jscode)
{
  var area = getElement(id_area);
  area.innerHTML=htmlcode;
  eval(jscode);
}

function initHtmlLoader(html_file, id_area, jscode) {
  html = new HtmlLoader(id_area, html_file, jscode);
  html.invoqueHtmlDataRequest();
}
