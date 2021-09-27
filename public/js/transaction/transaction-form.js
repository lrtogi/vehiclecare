$(document).ready(function() {
    myFunctionduit();
    $("#select-vehicle_type").change(function(e) {
        $.getJSON(packageUrl + "/" + $("#select-vehicle_type").val(), function(
            data
        ) {
            Vue.set(app, "package_type_select", data);
            if (data.result) {
                Vue.set(app, "package_type", "");
                Vue.nextTick(function() {
                    $("#package_type_selector").select2();
                });
            }
        });
    });

    $("#package_type_selector").change(function(e) {
        Vue.set(app, "package_type", $(this).val());
        $.getJSON(packagePriceUrl + "/" + $(this).val(), function(data) {
            if (data.result) {
                Vue.set(app, "total_price", data.data.discounted_price);
                $("#total_price").val(data.data.discounted_price);
            }
        });
    });

    $(".numajaDesimal").keypress(function(e) {
        if (
            (e.charCode >= 48 && e.charCode <= 57) ||
            e.charCode == 0 ||
            e.charCode == 46
        )
            return true;
        else return false;
    });
    $("#price").keyup(function() {
        var price = removePeriod($("#price").val(), ",");
        var discount_percentage = $("#discount_percentage").val();
        var discounted_price = price - (price * discount_percentage) / 100;
        $("#discounted_price").val(addPeriod(discounted_price, "."));
    });
    $("#discount_percentage").keyup(function() {
        var price = removePeriod($("#price").val(), ",");
        var discount_percentage = $("#discount_percentage").val();
        var discounted_price = price - (price * discount_percentage) / 100;
        $("#discounted_price").val(addPeriod(discounted_price, "."));
    });

    $("#price").change(function() {
        var price = removePeriod($("#price").val(), ",");
        var discount_percentage = $("#discount_percentage").val();
        var discounted_price = price - (price * discount_percentage) / 100;
        $("#discounted_price").val(addPeriod(discounted_price, "."));
    });
    $("#discount_percentage").change(function() {
        var price = removePeriod($("#price").val(), ",");
        var discount_percentage = $("#discount_percentage").val();
        var discounted_price = price - (price * discount_percentage) / 100;
        $("#discounted_price").val(addPeriod(discounted_price, "."));
    });

    $(document).on("blur", ".percent", function(e) {
        var id = $(this).attr("id");
        if ($(this).val() != "") {
            var num = parseFloat($(this).val());
            if (num > 100) num = 100;
            $(this).val(num.toFixed(2));
            var price = removePeriod($("#price").val(), ",");
            var discount_percentage = $("#discount_percentage").val();
            var discounted_price = price - (price * discount_percentage) / 100;
            $("#discounted_price").val(addPeriod(discounted_price, "."));
        }
        // console.log($(this).val());
    });
});

function setCaretPosition(elemId, caretPos) {
    var elem = document.getElementById(elemId);

    if (elem != null) {
        if (elem.createTextRange) {
            var range = elem.createTextRange();
            range.move("character", caretPos);
            range.select();
        } else {
            if (elem.selectionStart) {
                elem.focus();
                elem.setSelectionRange(caretPos, caretPos);
            } else elem.focus();
        }
    }
}

function getSelectionStart(o) {
    if (o.createTextRange) {
        var r = document.selection.createRange().duplicate();
        r.moveEnd("character", o.value.length);
        if (r.text == "") {
            return o.value.length;
        }
        return o.value.lastIndexOf(r.text);
    } else return o.selectionStart;
}

function myFunctionduit() {
    var add = ",";
    $("#productPrice,#productPriceUpdate,.price").keyup(function(e) {
        if (e.keyCode < 37 || e.keyCode > 40) {
            var id = $(this).attr("id");
            var locationMouse = getSelectionStart(document.getElementById(id));
            var input = document.getElementById(id).value;
            var output = addPeriod(input, add);
            var posAwal = input.length;
            var posAkhir = output.length;
            if (posAwal - posAkhir == 1) {
                locationMouse--;
            } else if (posAkhir - posAwal == 1) {
                locationMouse++;
            }
            document.getElementById(id).value = output;
            setCaretPosition(id, locationMouse);
        }
    });
    $("#productPrice,#productPriceUpdate,.price").change(function(e) {
        var id = $(this).attr("id");
        var locationMouse = getSelectionStart(document.getElementById(id));
        var input = document.getElementById(id).value;
        var output = addPeriod(input, add);
        var posAwal = input.length;
        var posAkhir = output.length;
        if (posAwal - posAkhir == 1) {
            locationMouse--;
        } else if (posAkhir - posAwal == 1) {
            locationMouse++;
        }
        document.getElementById(id).value = output;
        setCaretPosition(id, locationMouse);
    });
}

function removePeriod(nStr, remove) {
    if (nStr != "") {
        tamp = nStr.split(remove);
        nStr = "";
        for (var kembali = 0; kembali < tamp.length; kembali++) {
            nStr += tamp[kembali];
        }
    }
    return nStr;
}

function addPeriod(nStr, add) {
    nStr += "";
    nStr = removePeriod(nStr, add);
    nStr += "";
    var desimalnya = nStr.split(".");
    if (desimalnya.length > 1) {
        var desimalText = desimalnya[1];
        nStr = desimalnya[0];
    } else {
        var desimalText = "00";
    }
    nStr += "";
    x = nStr.split(add);
    x1 = x[0];
    x2 = x.length > 1 ? add + x[1] : "";
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, "$1" + add + "$2");
    }
    return x1 + x2 + "." + desimalText;
}

function formatUang(text, depan, simbol, desimal) {
    var desimalnya = text.split(".");
    if (desimalnya.length > 1) {
        var desimalText = desimalnya[1];
    } else {
        var desimalText = "00000";
    }
    var text = desimalnya[0];

    var tamp = text;
    var len = tamp.length;
    var count = 1;
    var temp = "";

    if (desimal == 1) {
        for (var awal = len - 1; awal >= 0; awal--) {
            if ((count - 1) % 3 == 0 && count - 1 > 0) {
                temp += ",";
            }
            temp += tamp[awal];
            count += 1;
        }
        len = temp.length;
        tamp = "";
        for (var awal = len - 1; awal >= 0; awal--) {
            tamp += temp[awal];
        }
        tamp += "." + desimalText;
    } else {
        for (var awal = len - 1; awal >= 0; awal--) {
            if ((count - 1) % 3 == 0 && count - 1 > 0) {
                temp += ".";
            }
            temp += tamp[awal];
            count += 1;
        }
        len = temp.length;
        tamp = "";
        for (var awal = len - 1; awal >= 0; awal--) {
            tamp += temp[awal];
        }
    }
    if (depan == 1) {
        return simbol + " " + tamp;
    } else {
        return tamp + " " + simbol;
    }
}
