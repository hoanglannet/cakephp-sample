/*------------------------ DIALOG HELPER SECTION -----------------------------*/
/**
 * Provide default prompt dialog with common needs
 * it will create dialog with default setting(model, and could be resized)
 * @param title : title of dialog
 * @param mess : message be displayed on dialog
 * @param callback : call back function for Positive button(Ok, Yes, Next, Select)
 * @param buttonType : the buttons for dialog:
 * YesNo : buttons Yes and No
 * OkCancel: buttons Ok and Cancel
 * SelectCancel: button Select and Cancel
 * NextCancel: button Next and Cancel
 * @param iconType: icon for message, the icon is displayed front the message
 * 'warn': warning icon
 * 'info': info icon
 * null: nothing
 * @param focusButton : the translate text of button be focused
 * ex: _('Yes') : the button have translate text of 'Yes' is focus
 * @return show dialog
 */

function promptConfirm(title, mess, callback, buttonType, iconType, focusButton) {	
	var b = jQuery(document.body);	
	var selector = "prompt-confirm-dialog";
	if (title == null) {
		title = '';
	}
	var msgbox = "<div id='" + selector + "' title='" + title + "'>" + mess + "</div>";
	if (iconType == 'warn') {		
		msgbox = "<div id='" + selector + "' title='" + title + "'><p><span class='ui-icon  ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>" + mess + "</p></div>";
	} else if (iconType == 'info') {
		msgbox = "<div id='" + selector + "' title='" + title + "'><p><span class='ui-icon ui-icon-info' style='float:left; margin:0 7px 20px 0;'></span>" + mess + "</p></div>";
	} else if (iconType == 'note') {
		msgbox = "<div id='" + selector + "' title='" + title + "'><p><span class='ui-icon ui-icon-info' style='float:left; margin:0 7px 20px 0;'></span>" + mess + "</p>";
		msgbox += "<br/><p>Your note</p><textarea id='confirmNoteId' rows='6' cols='30' ></textarea>";
		msgbox += "</div>";
	}
	$("#" + selector).remove();	
	b.append(msgbox);	
	var buttons = {};	
	if (buttonType == 'YesNo') {
		buttons['No'] = function() {
			$(this).dialog('close');
		};
		buttons['Yes'] = function () {
			if (callback('Yes',b) == true) {
				$(this).dialog('close');
			}
		};				
	} else if (buttonType == 'OkCancel') {
		buttons['Cancel'] = function() {
			$(this).dialog('close');
		};
		buttons['Ok'] = function () {
			if (callback('Ok',b) == true) {
				$(this).dialog('close');
			}
		};		
	} else if (buttonType == 'SelectCancel') {
		buttons['Cancel'] = function() {
			$(this).dialog('close');
		};
		buttons['Select'] = function () {
			if (callback('Select',b) == true) {
				$(this).dialog('close');
			}
		};
		
	} else if (buttonType == 'NextCancel') {
		buttons['Cancel'] = function() {
			$(this).dialog('close');
		};
		buttons['Next'] = function () {
			if (callback('Next',b) == true) {
				$(this).dialog('close');
			}
		};
		
	}
	
	$("#" + selector).dialog({
		autoOpen: true,			
		bgiframe: true,
		resizable: false,		
		modal: true,	
		dialogClass: 'custom-dialog',
		buttons: buttons		
	});	
	
	if (focusButton != null && focusButton != '') {	
		$("div[role='dialog'] div button").each(function (i) {
	        $(this).attr('class','ui-state-default ui-corner-all');
	      });
		$("div[role='dialog'] div button:contains('" + focusButton+ "')").attr('class',"ui-state-default ui-corner-all ui-state-focus")
		$("div[role='dialog'] div button:contains('" + focusButton+ "')").focus();
	}
}

/**
 * Provide a general dialog with flexible setting
 * @param selector : the id of html element be displayed in dialog
 * null: the dialog will generate it's own id(general-dialog) for the dialog
 * @param title: title of dialog
 * null: the title in selector element won't be override 
 * @param mess : message be displayed in dialog
 * null: the content of selector element won't be override
 * @param iconType: icon for message, the icon is displayed front the message
 * 'warn': warning icon
 * 'info': info icon
 * null: nothing
 * @param options : options for dialog
 * null: it will create dialog with default setting(the box with no button, model, and could be resized)
 * @param focusButton : the translate text of button be focused
 * ex: _('Yes') : the button have translate text of 'Yes' is focus
 * @return show dialog
 * @return
 */
function generalDialog(selector, title, mess, iconType, options, focusButton) {
	var b = jQuery(document.body);	
	var msgbox = '';
	var icon = '';
	
	if (selector == null || $.trim(selector) == '' || $('#' + selector).html() == null) {
		
		selector = "general-dialog";		
		$("#general-dialog").remove();
		msgbox = "<div id='general-dialog' title=''></div>";		
		b.append(msgbox);		
	}	
	
	if (iconType == 'warn') {		
		icon = "<span class='ui-icon  ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>";
	} else if (iconType == 'info') {
		icon = "<span class='ui-icon ui-icon-info' style='float:left; margin:0 7px 20px 0;'></span>";
	}

	if (options == null || options =='') {			
		options = {
				autoOpen: true,			
				bgiframe: true,
				resizable: false,		
				modal: true,
				buttons: {}
		}
	}
	options['dialogClass'] = 'custom-dialog';
	var len = $('body').find('#' + selector).length;	
	if ($('div[role="dialog"] div[id="' + selector + '"]').html() != null && len == 1) {
		$('#' + selector).dialog( 'destroy' );
		if (title != null) {
			$('#' + selector).attr('title',title);
		}
		if (mess != null) {
			$('#' + selector).html("<p>" + icon + mess) + "</p>";
		}
		$('#' + selector).dialog(options);		
	} else if ($('div[role="dialog"] div[id="' + selector + '"]').html() != null && len > 1) {		
		$('div[role="dialog"] div[id="' + selector + '"]').remove();
		if (title != null) {
			$('#' + selector).attr('title',title);
		}
		if (mess != null) {
			$('#' + selector).html("<p>" + icon + mess) + "</p>";
		}
		$('#' + selector).dialog(options);
	} else {		
		if (title != null) {
			$('#' + selector).attr('title',title);
		}
		if (mess != null) {
			$('#' + selector).html("<p>" + icon + mess) + "</p>";
		}
		$('#' + selector).dialog(options);
	}

	if (focusButton != null && focusButton != '') {
		
		$("div[role='dialog'] div button").each(function (i) {
	        $(this).attr('class','ui-state-default ui-corner-all');
	      });
		$("div[role='dialog'] div button:contains('" + focusButton+ "')").attr('class',"ui-state-default ui-corner-all ui-state-focus");
		$("div[role='dialog'] div button:contains('" + focusButton+ "')").focus();
	}

}
/*------------------------ END DIALOG HELPER SECTION -----------------------------*/
 
/*------------------------ FORM HELPER SECTION -----------------------------------*/
$.fn.clearForm = function() {
	return this.each(function() {
		var type = this.type, tag = this.tagName.toLowerCase();
		if (tag == 'form')
			return $(':input', this).clearForm();
		
		if (type == 'text' || type == 'password' || tag == 'textarea')
			this.value = '';
		else if (type == 'checkbox' || type == 'radio')
			this.checked = false;
		else if (tag == 'select')
			this.selectedIndex = -1;
	});
};

$.fn.currencyMask = function(options) {
	var defaults = {
			decimalPoint: ',',
			thousandSep: '.',
			fracDigits: 0
	};

	$.extend(defaults, options);
	
	return this.keyup(function(e) {
		var val = this.value.replace(new RegExp('[^0-9]+', 'g'), '');
		if (val != '') {
			var lcInt = locale(defaults.decimalPoint, defaults.thousandSep, defaults.fracDigits);
			val = toLcString(val, lcInt);
		}
		this.value = val;
	});
};

$.fn.numericMask = function(options) {
	var defaults = {
			length:  255
	};

	$.extend(defaults, options);
	
	return this.keyup(function(e) {
		var val = new String(this.value.replace(new RegExp('[^0-9]+', 'g'), ''));
		
		var length	=	defaults.length;
		if (!isNaN(parseInt(options.length)) > 0) {
			length	=	parseInt(options.length);
		}
		if (length > 0) {
			val	=	val.substr(0,length);
		}
		this.value = val;
	});
};

/*------------------------ END FORM HELPER SECTION -----------------------------------*/

/*------------------------ AJAX HELPER SECTION -----------------------------------*/
function showAjaxLoading(msg) {
	$('#indicatorMsg').text(msg);
	$('#ajaxLoading').show();
}

function hideAjaxLoading() {
	$('#indicatorMsg').text('');
	$('#ajaxLoading').hide();
}
/*------------------------ END AJAX HELPER SECTION -----------------------------------*/

/*------------------------ DATE HELPER SECTION -----------------------------------*/
/**
* Return number of days in February
* @param year
* @return
* 29: leap year
* 28: not a leap year
*/
function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
	// EXCEPT for centurial years which are not also divisible by 400.
	return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}

function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
	} 
	return this
}

function fixedDateCheck(dtStr, target) {
	var daysInMonth = DaysArray(12);
	var minYear = 1900;
	var maxYear = 2100;
	var full = /^\d{1,2}\/\d{1,2}\/\d{4}$/;	

	var strDay = '1';
	var strMonth = '1';
	var strYear = '';
	if (full.test(dtStr)) {
		var dArr = dtStr.split("/");
		strDay = dArr[0];
		strMonth = dArr[1];
		strYear = dArr[2]; 
	} else {
		$('#' + target).text("Định dạng ngày không hợp lệ");
		return false;
	}

	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1);
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1);
	for (var i = 1; i <= 3; i++) {
		if (strYear.charAt(0)=="0" && strYear.length>1) strYear=strYear.substring(1);
	}

	var day		= parseInt(strDay, 10);	
	var month	= parseInt(strMonth, 10);	
	var year	= parseInt(strYear, 10);

	if (strDay != '' && (day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month])){
		$('#' + target).text("Ngày không hợp lệ");
		return false;	
	}

	if (strMonth != '' && (month<1 || month>12)){
		$('#' + target).text("Tháng không hợp lệ");
		return false;	
	}

	if (strYear != '' && (year==0 || year<minYear || year>maxYear)){
		$('#' + target).text("Năm không hợp lệ. Năm phải nằm trong khoảng từ " + minYear + " đến " + maxYear);
		return false;
	}
	return true;
}

function isLessThanCurMonth(month, year, target) {
	var dtStr = '01/' + month + '/' + year;
	var d = new Date();
	var curMonth = d.getMonth() + 1;
	var curYear = d.getFullYear();
	if (fixedDateCheck(dtStr, target)) {
		if ( (year < curYear) || ( (year == curYear) && (month <= curMonth) ) ) {
			$('#' + target).text("");
			return true;
		} else {
			$('#' + target).text("Tháng phải nhỏ hơn tháng hiện tại");
			return false;
		}
	}
	return false;
}

/**
* Compare 2 date with format the same format
* dd/mm/yyyy
* mm/yyyy
* yyyy
* @param dtStr1
* @param dtStr2
* @return
* 'gt' : dtStr1 > dtStr2
* 'lt' : dtStr1 < dtStr2
* 'eq' : dtStr1 = dtStr2
* dtStr2='today'
* 'gtToday' : dtStr1 > current day
* 'ltToday' : dtStr1 < current day
* 'eqToday' : dtStr1 = current day
*/
function compareDate(dtStr1, dtStr2){
	var daysInMonth = DaysArray(12)

	var full = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
	var medium = /^\d{1,2}\/\d{4}$/;
	var short = /^\d{4}$/;

	var strDay1 = '1';
	var strMonth1 = '1';
	var strYear1 = '';

	if (full.test(dtStr1)) {
		var dArr1 = dtStr1.split("/");
		strDay1 = dArr1[0];
		strMonth1 = dArr1[1];
		strYear1 = dArr1[2]; 
	} else if (medium.test(dtStr1)) {
		var dArr1 = dtStr1.split("/");    	
		strMonth1 = dArr1[0];
		strYear1 = dArr1[1];		
	} else if (short.test(dtStr1)) {
		strYear1 = dtStr1;
	}

	if (strDay1.charAt(0)=="0" && strDay1.length>1) strDay1=strDay1.substring(1);
	if (strMonth1.charAt(0)=="0" && strMonth1.length>1) strMonth1=strMonth1.substring(1);
	for (var i = 1; i <= 3; i++) {
		if (strYear1.charAt(0)=="0" && strYear1.length>1) strYear1=strYear1.substring(1);
	}

	var day1   = parseInt(strDay1, 10)
	var month1 = parseInt(strMonth1, 10)
	var year1  = parseInt(strYear1, 10)
	var myDate1 = new Date();
	myDate1.setFullYear(year1, month1 - 1, day1);
	if (dtStr2 == 'today') {
		var today = new Date();
		if (myDate1>today) {
			return "gtToday";
		} else if (myDate1<today) {
			return "ltToday";
		} else {
			return "eqToday"
		}
	}

	var strDay2 = '1';
	var strMonth2 = '1';
	var strYear2 = '';

	if (full.test(dtStr2)) {
		var dArr2 = dtStr2.split("/");
		strDay2 = dArr2[0];
		strMonth2 = dArr2[1];
		strYear2 = dArr2[2]; 
	} else if (medium.test(dtStr2)) {
		var dArr2 = dtStr2.split("/");    	
		strMonth2 = dArr2[0];
		strYear2 = dArr2[1];		
	} else if (short.test(dtStr2)) {
		strYear2 = dtStr2;
	}

	if (strDay2.charAt(0)=="0" && strDay2.length>1) strDay2=strDay2.substring(1);
	if (strMonth2.charAt(0)=="0" && strMonth2.length>1) strMonth2=strMonth2.substring(1);
	for (var i = 1; i <= 3; i++) {
		if (strYear2.charAt(0)=="0" && strYear2.length>1) strYear2=strYear2.substring(1);
	}

	var day2   = parseInt(strDay2, 10)	
	var month2 = parseInt(strMonth2, 10)	
	var year2  = parseInt(strYear2, 10)
	var myDate2 =new Date();
	myDate2.setFullYear(year2, month2 - 1, day2);

	if (myDate1>myDate2) {
		return "gt";
	} else if (myDate1<myDate2) {
		return "lt";
	} else {
		return "eq";
	}
}
/*------------------------ END DATE HELPER SECTION -----------------------------------*/

/*------------------------ NUMBER FORMAT HELPER SECTION -----------------------------------*/
/**
 * Constructor for a custom locale object
 * @param decimalPoint: String containing the symbol used as the decimal delimiter. 
 * @param thousandSep: String containing the symbol used as a separator for groups of digits to the left of the decimal delimiter. 
 * @param fracDigits: Number representing the number of fractional digits to be written to the right of the decimal delimiter
 * @return: object with properties: decimalPoint, thousandSep, fracDigits
 */
function locale(decimalPoint, thousandSep, fracDigits) {
	  var lc = {decimalPoint:'', thousandSep: '', fracDigits: ''};
	  lc.decimalPoint = new String(decimalPoint);
	  lc.thousandSep = new String(thousandSep);
	  lc.fracDigits = fracDigits;
	  return lc;
}

/**
 * Returns number num rounded to the fracDigits'th decimal
 * @param num
 * @param fracDigits: Number representing the number of
 * fractional digits to be written to the right of the decimal delimiter
 * @return: rounded Fload number
 * Ex: 1.527 -> if fracDigits = 2 -> result: 1.53
 */
function roundFloat(num, fracDigits) {
	  var factor = Math.pow(10, fracDigits);
	  return(Math.round(num*factor)/factor);
}     
/**
 * 
 * @param num
 * @param lc
 * @return
 * Returns a string representing 
 * num formatted with lc.decimalPoint as decimal separator
 */
function toLcString(num, lc) {
	  var str = new String(num);
	  var aParts = str.split(".");	  
	  if (aParts[0] != null) {
		  var len = aParts[0].length;
		  var text = '';
		  var count = 0;
		  for ( var int = (len-1); int >=0; int--) {
			  text += aParts[0][int];
			  count ++;
			  if (count == 3 && int >0) {
				  count =0;
				  text += lc.thousandSep;
			  }
		  }
		  var result ='';
		  for ( var int = (text.length - 1); int >=0; int--) {
			  result += text[int];			  
		  }
		  aParts[0] = result;
	  }
	  return(aParts.join(lc.decimalPoint));
 }
/**
 * 
 * @param num
 * @param lc: local object
 * @return
 * Returns a string representing 
 * num formatted with lc.decimalPoint as decimal separator
 */
function formatNum(num, lc) {
	var sNum = new String(roundFloat(num, lc.fracDigits));
	  if(lc.fracDigits>0) {
	    if(sNum.indexOf(".")<0)
	      sNum = sNum+".";
	    while(sNum.length < 1+sNum.indexOf(".")+lc.fracDigits)
	      sNum = sNum+"0";
		  }
  return(toLcString(sNum, lc));
}
/**
 * Returns the numerical value of the number contained in 
 * str and formatted according to 
 * lc.thousandSep and 
 * lc.decimalPoint properties
 * @param str: String
 * @param lc: locale Object
 * @return
 */
function parseLcNum(str, lc) {
	  var sNum = new String($.trim(str));
	  var aParts = sNum.split(lc.thousandSep);
	  sNum = aParts.join("");
	  aParts = sNum.split(lc.decimalPoint);
	  var number = new Number(parseFloat(aParts.join(".")));
	  return parseFloat(number.toFixed(lc.fracDigits));
}

/**
 * Parses a localized fraction string and return a floating number.
 * @example: Valid strings:
 *      locale=vi-VN: 0,5  1.000,5  1/2  1,5/3 
 *      locale=en-US: 0.5  1,000.5  1/2  1.5/3        
 * @param str: String
 * @param lc: locale Object
 * @return Floating number if the input string is a valid localized or else NaN  
 */
function parseLcFraction(str, lc) {
    var sNum    =   new String($.trim(str));
    // Count "/" in the number string. The number of "/" must not be >= 1 ("121/2/3" is invalid).
    var parts   =   sNum.split("/");
    if (parts.length > 2 || parts.length == 0) {
        return Number.NaN;
    }
    if (parts.length == 1) { // Input string: 1,2  1000  2.000,5 ....
        return parseLcNum(parts[0], lc);
    }
    else { // Input: 1/2  1.5/3  ...
        var lcFrac  =   locale(lc.decimalPoint, lc.thousandSep, 10);
        var divided =   parseLcNum(parts[0], lcFrac);
        var divider =   parseLcNum(parts[1], lcFrac);
        if (!isFinite(divided) || !isFinite(divider)) {
            return Number.NaN;
        }
        var result  =   new Number(divided/divider);
        return  parseFloat(result.toFixed(lc.fracDigits));
    }
}
/*------------------------ END NUMBER FORMAT HELPER SECTION -----------------------------------*/
/**
 * Add date timepicker to [idYear] text box 
 * @param idDay : id of text box day
 * @param idMonth: id of text box month
 * @param idYear: id of text box year
 * @param changeMonth: 
 * true: allow use select month from a combobox
 * false: hide the month combobox 
 * @param changeYear
 * true: allow use select year from a combobox
 * false: hide the year combobox  
 * @param initCurrentDay
 * true: will auto fill current date into day,month,year text box at start up
 * false: day, month year text box is empty at start up
 * @return
 */
function addDatePicker(idDay, idMonth, idYear, changeMonth, changeYear, initCurrentDay) {
	$(idDay).numericMask({length: 2});
	$(idMonth).numericMask({length: 2});
	$(idYear).numericMask({length: 4});
	$(idYear).datepicker({
		dateFormat: 'mm/dd/yy',
		gotoCurrent: true,
		buttonImage: context + '/img/calendar.png',
		buttonImageOnly: true,
		showOn: 'button',
		changeMonth: changeMonth,
		changeYear: changeYear,
		onSelect: function(dateText, inst) {                        
	
	        $(idDay).val(inst.selectedDay);
	
	        $(idMonth).val(inst.selectedMonth + 1);
	
	        $(this).val(inst.selectedYear);                        
	
	    }
	});
	if ((initCurrentDay == true) && ($(idYear).val() == '')) {
		var dt=new Date();
		var mo = dt.getMonth() + 1;  
		var day = dt.getDate(); 
		$(idDay).val(day < 10? "0" + day: day);
		$(idMonth).val(mo < 10? "0" + mo: mo);
		$(idYear).val(dt.getFullYear());
	}
}

var _normalBorber	=	"#AAA";
function toNormalBorder(input) {
	if (typeof(input) == 'object') {
		input.css("border-color", _normalBorber);
	}
	else {
		$('#' + input).css("border-color", _normalBorber);
	}
}

var _errorColor	=	'red';
var _successColor	=	'green';
function showMessage(target, msg, status) {
	if (status == 'error') {
		$('#' + target).text(msg).css('color', _errorColor).show();
	}
	else if (status == 'success') {
		$('#' + target).text(msg).css('color', _successColor).show();
	}
	else {
		$('#' + target).text(msg).show();
	}
}

function isNotEmpty(obj, message, target) {
	if ($.trim(obj.val()).length == 0) {
		obj.css("border-color", "red");
		$('span#' + target).css('color', 'red').text(message).show();
		return false;
	} else {
		toNormalBorder(obj);
		$('span#' + target).text('');;
		return true;
	}
}

function keySort(needSort) {
	var finishArray = [];
	var keys = [];
	for (key in needSort) {
		keys.push(key);
	}
	keys.sort();
	
	for (i = 0; i < keys.length; i++) {
		finishArray[keys[i]] = needSort[keys[i]];
	}
	
	return finishArray;
}

function moveNextBlur(event, currId, nextId) {
	if (event.keyCode == 13){
		$('#' + currId).trigger('blur');
		$('#' + nextId).focus();
		$('#' + nextId).select();
	}
}

function changeCity() {
	var buttons = {};
	buttons['Close'] = function() {
		$(this).dialog('close');		
	};
	
	generalDialog("changeCityDiv", null, null, null, {
		autoOpen: true,			
		bgiframe: true,
		resizable: false,				
		width: 650,
		height: 520,
		modal: true,
		buttons: buttons
	}, 'Close');
}

function activeMenu(mainId, activeId) {
	jQuery('#'+mainId+' ul li a').each(function () {
		jQuery(this).removeClass('active');
	});
	jQuery('ul li a#'+activeId).addClass('active');	
}

function formatNumFloat(num, dec) {
	num = Math.ceil(num * 100) / 100;
	return num.toFixed(dec);
}

function gotoMap() {
	var id = $('#detailResult ul#content .row.selected').attr('id');
	var spaceId = $('#SpaceId').val();
	if (id != undefined) {
		id = id.split('space_');
		id = id[1];
	} else if (spaceId != undefined) {
		id = spaceId;
	} else {
		id = '';
	}
	window.location.href = context+'/maps/map_amenities/'+id;
}