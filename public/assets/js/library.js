function toUcwords(str)
{
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

$( ".show-password" ).hover(
  function() {
    $( this ).siblings('input[type=password]').attr('type','text');
  }, function() {
    $( this ).siblings('input[type=text]').attr('type','password');
  });

// END DEFAULT
$(".numberformat").keydown(function (e) {
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
				 // Allow: Ctrl+A
				(e.keyCode == 65 && e.ctrlKey === true) ||
				 // Allow: Ctrl+C
				(e.keyCode == 67 && e.ctrlKey === true) ||
				 // Allow: Ctrl+X
				(e.keyCode == 88 && e.ctrlKey === true) ||
				 // Allow: home, end, left, right
				(e.keyCode >= 35 && e.keyCode <= 39)) {
					 return;
		}
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
})

$('.userformat').keypress(function (e) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
});


$('input[alphanum]').keypress(function (e) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }

    e.preventDefault();
    return false;
});


$("input[rule='alphaonly']").keypress(function(event){
    var inputValue = event.charCode;

    if(inputValue == 121){
    	return;
    }
    if(!(inputValue >= 65 && inputValue <= 120 ) && (inputValue != 32 && inputValue != 0 ) || inputValue == 32 ){
        event.preventDefault();
    }
});

function goExplode(string,delimiter,result) {
    var response 	= (string).split(delimiter);
    return response[result];
}


function tgl_indo(tgl){
	var tanggal = (tgl).substr(8,2);
	var bulan = "";
 	switch ((tgl).substr(5,2)){
				case '01': 
					bulan= "Januari";
				case '02':
					bulan= "Februari";
				case '03':
					bulan= "Maret";
				case '04':
					bulan= "April";
				case '05':
					bulan= "Mei";
				case '06':
					bulan= "Juni";
				case '07':
					bulan= "Juli";
				case '08':
					bulan= "Agustus";
				case '09':
					bulan= "September";
				case '10':
					bulan= "Oktober";
				case '11':
					bulan= "November";
				case '12':
					bulan= "Desember";
			}

		var tahun = (tgl).substr(0,4);
		return tanggal+' '+bulan+' '+tahun;		 
}

Number.prototype.formatMoney = function(c, d, t){
	  var n = this, 
	      c = isNaN(c = Math.abs(c)) ? 2 : c, 
	      d = d == undefined ? "." : d, 
	      t = t == undefined ? "," : t, 
	      s = n < 0 ? "-" : "", 
	      i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
	      j = (j = i.length) > 3 ? j % 3 : 0;
	     return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
   };

function seo(text) {       
    var characters = [' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '_', '{', '}', '[', ']', '|', '/', '<', '>', ',', '.', '?', '--']; 

    for (var i = 0; i < characters.length; i++) {
         var char = String(characters[i]);
         text = text.replace(new RegExp("\\" + char, "g"), '-');
    }
    text = text.toLowerCase();
    return text;
}

function read_more(string,limit){
	string = strip_tags(string);
	if (string.length>limit){
		return string.substr(0,limit)+' . . . ';
	}
	else {
		return string;
	}
}

function strip_tags(input, allowed) {
	  allowed = (((allowed || '') + '')
	    .toLowerCase()
	    .match(/<[a-z][a-z0-9]*>/g) || [])
	    .join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
	  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
	    commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
	  return input.replace(commentsAndPhpTags, '')
	    .replace(tags, function($0, $1) {
	      return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
	    });
	}


Date.prototype.addHours = function(h) {    
   this.setTime(this.getTime() + (h*60*60*1000)); 
   return this;   
}

function redirect(url){
	window.location.href = url;
}


function parseURLParams(url) {
    var queryStart = url.indexOf("?") + 1,
        queryEnd   = url.indexOf("#") + 1 || url.length + 1,
        query = url.slice(queryStart, queryEnd - 1),
        pairs = query.replace(/\+/g, " ").split("&"),
        parms = {}, i, n, v, nv;

    if (query === url || query === "") {
        return;
    }

    for (i = 0; i < pairs.length; i++) {
        nv = pairs[i].split("=");
        n = decodeURIComponent(nv[0]);
        v = decodeURIComponent(nv[1]);

        if (!parms.hasOwnProperty(n)) {
            parms[n] = [];
        }

        parms[n].push(nv.length === 2 ? v : null);
    }
    return parms;
}

var format_money = function(num){
var str = num.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
if(str.indexOf(",") > 0) {
	parts = str.split(",");
	str = parts[0];
}
str = str.split("").reverse();
for(var j = 0, len = str.length; j < len; j++) {
	if(str[j] != ".") {
		output.push(str[j]);
		if(i%3 == 0 && j < (len - 1)) {
			output.push(".");
		}
		i++;
	}
}
formatted = output.reverse().join("");
return(formatted + ((parts) ? "," + parts[1].substr(0, 2) : ""));
};

$("input[rule='currency']").on('keyup change' , function (e){
	$(this).val(format_money($(this).val()));
})


$(".form-validation input").change(function(e){
	var group 			= $(this).parents('.form-group');

	if($(group).hasClass('has-error')){
		$(group).removeClass('has-error');
		$(group).find('.help-block').remove();
	}
})

$(".form-validation textarea").change(function(e){
	var group 			= $(this).parents('.form-group');

	if($(group).hasClass('has-error')){
		$(group).removeClass('has-error');
		$(group).find('.help-block').remove();
	}
})

var delay = (function(){
              var timer = 0;
              return function(callback, ms){
                clearTimeout (timer);
                timer = setTimeout(callback, ms);
              };
            })();


function ShowNotif(title,msg,theme){
    $('body').find('.jGrowl').attr('class', '').attr('id', '').hide();
    $.jGrowl(msg, {
        theme: theme,
        header: title,
        sticky: true,
        position:'top-right'
    });
}

function blockMessage(element,message,color){
	$(element).block({
        	message: '<span class="text-semibold"><i class="icon-spinner4 spinner position-left"></i>&nbsp; '+message+'</span>',
            overlayCSS: {
                backgroundColor: color,
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: '10px 15px',
                color: '#fff',
                width: 'auto',
                '-webkit-border-radius': 2,
                '-moz-border-radius': 2,
                backgroundColor: '#333'
            }
        });
}

function notifError($message){
    $.jGrowl($message, {
        header: 'Oh! Snap',
         position: 'top-center',
        theme: 'alert-styled-left bg-danger'
    }); 
}

function notifSuccess($message){
    $.jGrowl($message, {
        header: 'Success!',
        position: 'top-center',
        theme: 'alert-styled-left bg-success'
    });     
}

$(".delete-url").click(function(e){
    var url     = $(this).data('url');

    if(url==""){
        return;
    }

    swal({
      title: "Are you sure?",
      text: "This data will deleted permanently",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false
    },
    function(){
      window.location.href = url;
    });
})