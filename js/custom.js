Number.prototype.formatMoney = function(c, d, t){
var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
 
var is_xs = false;
var is_sm = false;
var is_md = false;
var is_lg = false;
freshSize();

$(window).resize(function(e) {
	freshSize();
});

function freshSize()
{
	is_xs = false;
	is_sm = false;
	is_md = false;
	is_lg = false;
	if($(window).width()>=1200){
		is_lg = true;
	}
	else if($(window).width()<1200 && $(window).width()>=992){
		is_md = true;
	}
	else if($(window).width()<992 && $(window).width()>=768){
		is_sm = true;
	}
	else{
		is_xs = true;
	}
}