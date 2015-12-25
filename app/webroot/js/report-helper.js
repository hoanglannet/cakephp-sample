/**
 * View report as HTML
 * @param reportName
 */
function viewReport(reportName) {
	if (!validateReport()) return false;	
	$('#filterMsg').text('');
	var filter = getReportFilterData();
	$.ajax({
		type:	'POST',
		url:	reportName + '/view?',
		data:	filter,
		dataType: 'html',
		beforeSend: function() {showAjaxLoading('')},
		complete: function() {hideAjaxLoading()},
		success: function(data) {
			$('#reportResultDiv').html(data);		
		}
	});
}

function printReport(reportName) {
	if (!validateReport()) return false;	
	$('#filterMsg').text('');
	var filter = getReportFilterData();
	var url	= reportName + '/printReport?' + filter;
	window.open(url, '', 'scrollbars=yes, menubar=no, resizable=yes, toolbar=no, location=no, status=no');
}

function exportReport(reportName, exportType) {
	if (!validateReport()) return false;	
	$('#filterMsg').text('');
	var filter = getReportFilterData();
	var url	=	context + '/' + reportName + '/export?';
	url	+=	filter + "&exportType=" + exportType;
	window.location.replace(url);
}

function validateSingleDate() {
	var strDate	=	$('#startDay').val() + "/" + $('#startMonth').val() + "/" + $('#startYear').val();
	return fixedDateCheck(strDate, 'filterMsg'); 	 
	
}

function validateFromToDate() {
	var startDate	=	$('#startDay').val() + "/" + $('#startMonth').val() + "/" + $('#startYear').val();
	if (!fixedDateCheck(startDate, 'tmp')) {
		showMessage('filterMsg', 'Ngày bắt đầu không hợp lệ', 'error');
		return false;
	}
	
	var endDate	=	$('#endDay').val() + "/" + $('#endMonth').val() + "/" + $('#endYear').val();
	if (!fixedDateCheck(endDate, 'tmp')) {
		showMessage('filterMsg', 'Ngày kết thúc không hợp lệ', 'error');
		return false;
	}
	if (compareDate(startDate, endDate) == 'gt') {
		showMessage('filterMsg', 'Ngày bắt đầu không được sau ngày kết thúc', 'error');
		return false;
	}
	$('#filterMsg').text('');
	return true;
	
}