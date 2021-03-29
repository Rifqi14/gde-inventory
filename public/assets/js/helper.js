$(document).ready(function () {
    // format money
    Number.prototype.formatMoney = function(c, d, t){
        var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };

    $.fn.indoMoney = (v) => {
        value = parseFloat(v);
        return value.formatMoney(0, ',', '.');
    }

    $(".decimalfield").on("keyup keypress keyup blur",function (event) {
        //this.value = this.value.replace(/[^0-9\.]/g,'');
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

})

var waitingDialog = function (d) {
    var a = d(`<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y:visible;">
        <div class="modal-dialog modal-m">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="margin:0;"></h5>
                </div>
                <div class="modal-body">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-navy" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>`);
    return {
        show: function (s, i) {
            var e = d.extend({
                dialogSize: "m",
                progressType: ""
            }, i);
            "undefined" == typeof s && (s = "Loading"), "undefined" == typeof i && (i = {}), 
            a.find(".modal-dialog").attr("class", "modal-dialog").addClass("modal-" + e.dialogSize), 
            a.find(".progress-bar").attr("class", "progress-bar progress-bar-striped progress-bar-animated bg-navy"),
            e.progressType && a.find(".progress-bar").addClass("progress-bar-" + e.progressType), 
            a.find("h5").text(s), a.modal()
        },
        hide: function () {
            a.modal("hide")
        }
    }
}(jQuery)