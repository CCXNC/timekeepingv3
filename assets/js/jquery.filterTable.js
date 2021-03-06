/**
 * jquery.filterTable
 *
 * This plugin will add a search filter to tables. When typing in the filter,
 * any rows that do not contain the filter will be hidden.
 *
 * Utilizes bindWithDelay() if available. https://github.com/bgrins/bindWithDelay
 *
 * @version v1.5.3
 * @author Sunny Walker, swalker@hawaii.edu 
 */ 
!function(e){
	var t=e.fn.jquery.split("."),i=parseFloat(t[0]),a=parseFloat(t[1]);
	e.expr[":"].filterTableFind=2>i&&8>a?function(t,i,a)
	{
		return e(t).text().toUpperCase().indexOf(a[3].toUpperCase())>=0
	}:jQuery.expr.createPseudo(function(t)
	{
		return function(i)
		{
			return e(i).text().toUpperCase().indexOf(t.toUpperCase())>=0
		}
	}),e.fn.filterTable=function(t)
	{
		var i={autofocus:!1,callback:null,containerClass:"filter-table",
		containerTag:"div",hideTFootOnFilter:!1,highlightClass:"alt",
		inputSelector:null,inputName:"",inputType:"search",label:"Filter:",
		minRows:8,placeholder:"search this table",quickList:[],
		quickListClass:"quick",quickListGroupTag:"",quickListTag:"a",visibleClass:"visible"
		},a=function(e)
		{
			return e.replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/</g,"&lt;").replace(/>/g,"&gt;")
		},l=e.extend({},i,t),n=function(e,t)
		{
			var i=e.find("tbody");""===t?(i.find("tr.data").show().addClass(l.visibleClass),
			i.find("td").removeClass(l.highlightClass),
			l.hideTFootOnFilter&&e.find("tfoot").show()):(i.find("tr.data").hide().removeClass(l.visibleClass),
			l.hideTFootOnFilter&&e.find("tfoot").hide(),i.find("td").removeClass(l.highlightClass).
			filter(':filterTableFind("'+t.replace(/(['"])/g,"\\$1")+'")').
			addClass(l.highlightClass).closest("tr.data").show().addClass(l.visibleClass)),
			l.callback&&l.callback(t,e)};return this.each(function()
			{
				var t=e(this),i=t.find("tbody"),s=null,r=null,o=null,c=!0;
				"TABLE"===t[0].nodeName&&i.length>0&&(0===l.minRows||l.minRows>0&&i.find("tr.data").length>l.minRows)
				&&!t.prev().hasClass(l.containerClass)&&(l.inputSelector&&1===e(l.inputSelector).length ?
				(o=e(l.inputSelector),s=o.parent(),c=!1) : (s=e("<"+l.containerTag+" />"),
				""!==l.containerClass&&s.addClass(l.containerClass),s.prepend(l.label+" "),
				o=e('<input style="width:70%"type="'+l.inputType+'" placeholder="'+l.placeholder+'" name="'+l.inputName+'" />')),
				l.autofocus&&o.attr("autofocus",!0),e.fn.bindWithDelay?o.bindWithDelay("keyup",
				function(){n(t,e(this).val())},200):o.bind("keyup",function()
				{n(t,e(this).val())}),o.bind("click search",function(){n(t,e(this).val())}),
				c&&s.append(o),l.quickList.length>0&&(r=l.quickListGroupTag?e("<"+l.quickListGroupTag+" />"):s,
				e.each(l.quickList,function(t,i){var n=e("<"+l.quickListTag+' class="'+l.quickListClass+'" />');
				n.text(a(i)),"A"===n[0].nodeName&&n.attr("href","#"),n.bind("click",
				function(e){e.preventDefault(),o.val(i).focus().trigger("click")}),
				r.append(n)}),r!==s&&s.append(r)),c&&t.before(s))})
		}
	}(jQuery);