

function convertDateTimeIntoTimezone(date,timeZone) {
	var dates = typeof date === "string" ? new Date(date) : date

	let options = {
		timeZone: timeZone,
		year: 'numeric', month: '2-digit', day: '2-digit' ,
		hour : '2-digit', hour12 : false,  minute : '2-digit', second : '2-digit',
		timeZoneName : 'short',
	},
	formatter = new Intl.DateTimeFormat("en-US", options);
	datetime = formatter.format( dates );

	/*
	datetime = formatter.formatToParts( new Date() );
	var fdate = [];
	$.each(datetime, function(index, val) {
		if(val.type == 'month'){
			fdate['month'] = val.value
		}
		if(val.type == 'day'){
			fdate['day'] = val.value
		}
		if(val.type == 'year'){
			fdate['year'] = val.value
		}
		if(val.type == 'hour'){
			fdate['hour'] = val.value
		}
		if(val.type == 'minute'){
			fdate['minute'] = val.value
		}
		if(val.type == 'second'){
			fdate['second'] = val.value
		}
	});
	console.log(datetime, fdate)*/

	return (datetime);
}

function convertDateTimeServerFormate(date) {
	return date.getFullYear() + "-" + appendLeadingZeroes(date.getMonth() + 1) + "-" + appendLeadingZeroes(date.getDate()) + " " + appendLeadingZeroes(date.getHours()) + ":" + appendLeadingZeroes(date.getMinutes()) + ":" + appendLeadingZeroes(date.getSeconds())
}

function appendLeadingZeroes(n){
  if(n <= 9){
    return "0" + n;
  }
  return n
}


function timeForamteWithUserTimezone(date) {
	date = new Date(date);
	var hours = date.getHours();
	var minutes = date.getMinutes();
	var ampm = hours >= 12 ? 'pm' : 'am';

	hours = hours % 12;
	hours = hours ? hours : 12; // the hour '0' should be '12'
	hours = hours < 10 ? '0'+hours : hours;
	minutes = minutes < 10 ? '0'+minutes : minutes;

	var strTime = hours + ':' + minutes + ' ' + ampm;
	return strTime;
}

