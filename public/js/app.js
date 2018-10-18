
//datepicker------------------------------------------------------/

$('#order_choiceDate').datepicker({
    language: "fr",
    weekStart: 1,
    orientation: "top auto",
    todayHighlight: true
});


//css------------------------------------------------------------/

$('#orderForm li').css('color', 'red');

//half price or not-----------------------------------------------/

$("#order_half").click(function () {
    $("form[name=order]").valid();

    if ($("#order_half").is(":checked")){
        $("#priceFull").text("Plein tarif 8€");
        $("#priceChild").text("Enfant 4€");
        $("#priceSenior").text("Senior 6€");
        $("#priceReduce").text("Reduit 5€");
        var free = countElements(prices, "gratuit");
        $("#nbrFree").text("Nbr de billets : " + free);
        var child = countElements(prices, "enfant");
        $("#nbrChild").text("Nbr de billets : " + child);
        $("#totalChild").text((child * 4) + " €");
        var full = countElements(prices, "plein");
        $("#nbrFull").text("Nbr de billets : " + full);
        $("#totalFull").text((full * 8) + " €");
        var reduce = countElements(prices, "reduit");
        $("#nbrReduce").text("Nbr de billets : " + reduce);
        $("#totalReduce").text((reduce * 5) + " €");
        var senior = countElements(prices, "senior");
        $("#nbrSenior").text("Nbr de billets : " + senior);
        $("#totalSenior").text((senior * 6) + " €");
        $("#nbrTickets").text(free + child + full + reduce + senior);
        $("#total").text(((child * 4)+(full * 8)+(reduce * 5)+(senior * 6)) + " €");
    } else {
        $("#priceFull").text("Plein tarif 16€");
        $("#priceChild").text("Enfant 8€");
        $("#priceSenior").text("Senior 12€");
        $("#priceReduce").text("Reduit 10€");
        var free = countElements(prices, "gratuit");
        $("#nbrFree").text("Nbr de billets : " + free);
        var child = countElements(prices, "enfant");
        $("#nbrChild").text("Nbr de billets : " + child);
        $("#totalChild").text((child * 8) + " €");
        var full = countElements(prices, "plein");
        $("#nbrFull").text("Nbr de billets : " + full);
        $("#totalFull").text((full * 16) + " €");
        var reduce = countElements(prices, "reduit");
        $("#nbrReduce").text("Nbr de billets : " + reduce);
        $("#totalReduce").text((reduce * 10) + " €");
        var senior = countElements(prices, "senior");
        $("#nbrSenior").text("Nbr de billets : " + senior);
        $("#totalSenior").text((senior * 12) + " €");
        $("#nbrTickets").text(free + child + full + reduce + senior);
        $("#total").text(((child * 8)+(full * 16)+(reduce * 10)+(senior * 12)) + " €");
    }
});


var ages = {};
var prices = {};

function countElements(tab, element){
    var nbr = 0;
    for (i = 0; i <= ($("#tickets >div:last-child").attr("id")).match(/[0-9]/g).join(''); i++){
        if (tab[i] === element){
            nbr += 1;
        }
    }
    return nbr;
}

function refreshSummary(){
    var lastTicket = ($("#tickets >div:last-child").attr("id")).match(/[0-9]/g).join('');
    for (i = 0; i <= lastTicket; i++){
        ages[i]= moment().diff(moment($("#order_tickets_"+i+"_birth").val(), "DD/MM/YYYY"), 'years');
        prices[i]=0;
        if (ages[i] < 4){
            prices[i] = 'gratuit';
            if ($("#order_tickets_"+i+"_reduced").is(":checked")){
                $("#reducedInfo_"+i).text("Gratuit, pas de réduction pour ce tarif");
            } else {
                $("#reducedInfo_"+i).text("tarif Gratuit");
            }
        } else if (ages[i] >= 4 && ages[i] < 12){
            prices[i] = 'enfant';
            if ($("#order_tickets_"+i+"_reduced").is(":checked")){
                $("#reducedInfo_"+i).text("tarif enfant, pas de réduction pour ce tarif");
            } else {
                $("#reducedInfo_"+i).text("tarif enfant");
            }
        } else if (ages[i] >= 12 && ages[i] < 60){
            if ($("#order_tickets_"+i+"_reduced").is(":checked")){
                prices[i] = 'reduit';
                console.log($("#reducedInfo_"+i).text());
                $("#reducedInfo_"+i).text("tarif réduit, un justificatif vous sera demandé à l'entrée");
            } else {
                prices[i] = 'plein';
                $("#reducedInfo_"+i).text("tarif plein");
            }
        } else if (ages[i] >= 60){
            prices[i] = 'senior';
            if ($("#order_tickets_"+i+"_reduced").is(":checked")){
                $("#reducedInfo_"+i).text("tarif senior, pas de réduction pour ce tarif");
            } else {
                $("#reducedInfo_"+i).text("tarif senior");
            }
        }
    }
    if ($("#order_half").is(":checked")){
        var free = countElements(prices, "gratuit");
        $("#nbrFree").text("Nbr de billets : " + free);
        var child = countElements(prices, "enfant");
        $("#nbrChild").text("Nbr de billets : " + child);
        $("#totalChild").text((child * 4) + " €");
        var full = countElements(prices, "plein");
        $("#nbrFull").text("Nbr de billets : " + full);
        $("#totalFull").text((full * 8) + " €");
        var reduce = countElements(prices, "reduit");
        $("#nbrReduce").text("Nbr de billets : " + reduce);
        $("#totalReduce").text((reduce * 5) + " €");
        var senior = countElements(prices, "senior");
        $("#nbrSenior").text("Nbr de billets : " + senior);
        $("#totalSenior").text((senior * 6) + " €");
        $("#nbrTickets").text(free + child + full + reduce + senior);
        $("#total").text(((child * 4)+(full * 8)+(reduce * 5)+(senior * 6)) + " €");
    } else {
        var free = countElements(prices, "gratuit");
        $("#nbrFree").text("Nbr de billets : " + free);
        var child = countElements(prices, "enfant");
        $("#nbrChild").text("Nbr de billets : " + child);
        $("#totalChild").text((child * 8) + " €");
        var full = countElements(prices, "plein");
        $("#nbrFull").text("Nbr de billets : " + full);
        $("#totalFull").text((full * 16) + " €");
        var reduce = countElements(prices, "reduit");
        $("#nbrReduce").text("Nbr de billets : " + reduce);
        $("#totalReduce").text((reduce * 10) + " €");
        var senior = countElements(prices, "senior");
        $("#nbrSenior").text("Nbr de billets : " + senior);
        $("#totalSenior").text((senior * 12) + " €");
        $("#nbrTickets").text(free + child + full + reduce + senior);
        $("#total").text(((child * 8)+(full * 16)+(reduce * 10)+(senior * 12)) + " €");
    }
}


//add and remove ticket form button-------------------------------/

$(".btn-add").on("click", function() {
    var $collectionHolder = $($(this).data("rel"));
    var index = $collectionHolder.data("index");
    var prototype = $collectionHolder.data("prototype");
    $collectionHolder.append(prototype.replace(/__name__/g, index));
    $collectionHolder.data("index", index+1);

    if ((dates[$("#order_choiceDate").val()] + ($("#tickets >div").length)) > 1000){
        $(".btn-add").prop("disabled", true);
        $("#validButton").prop("disabled", true);
        $("#messageAddButton").text("Vous ne pouvez plus commander de billets pour cette date, veuillez retirer des billets");
    } else {
        $(".btn-add").prop("disabled", false);
        $("#validButton").prop("disabled", false);
        $("#messageAddButton").text("");
    }

    $("#validButton").prop("disabled", false);
    $("#messageValid").text("");

    //pricing-------------------/

    $("#order_tickets_"+index+"_birth").blur(function () {
        refreshSummary();
    });

    $("#order_tickets_"+index+"_reduced").click(function () {
        refreshSummary();
    });


    $('.birth').datepicker({
        language: "fr",
        weekStart: 1,
        orientation: "top auto",
        todayHighlight: true
    });

    //validator rules-----------/

    $("#order_tickets_"+index+"_firstName").rules("add", {
        required: true,
        messages : {
            required: "veuillez entrer le prénom"
        }
    });

    $("#order_tickets_"+index+"_lastName").rules("add", {
        required: true,
        messages : {
            required: "veuillez entrer le nom"
        }
    });

    $("#order_tickets_"+index+"_birth").rules("add", {
        required: true,
        messages : {
            required: "veuillez entrer la date de naissance"
        }
    });


});

$("body").on("click", ".btn-remove", function() {
    $($(this).data("rel")).remove();
    $("form[name=order]").valid();

    if ((dates[$("#order_choiceDate").val()] + ($("#tickets >div").length))>1000){
        $(".btn-add").prop("disabled", true);
        $("#validButton").prop("disabled", true);
        $("#messageAddButton").text("Vous ne pouvez plus commander de billets pour cette date, veuillez retirer des billets");
    } else {
        $(".btn-add").prop("disabled", false);
        $("#validButton").prop("disabled", false);
        $("#messageAddButton").text("");
    }

    if ($("#tickets >div").length === 0) {
        $("#validButton").prop("disabled", true);
        $("#messageValid").text("Vous devez ajouter au moins un billet");
    } else {
        $("#validButton").prop("disabled", false);
        $("#messageValid").text("");
    }

    refreshSummary();
});


//add custom validator method---------------------------------------------/

function makeDate (e){
    var dateMonth = e.substring(3,5);
    return new Date(e.substring(6,10), dateMonth-=1, e.substring(0,2));
}

function JoursFeries (an){
    var JourAn = new Date(an, "00", "01").toLocaleDateString("fr-FR");
    var FeteTravail = new Date(an, "04", "01").toLocaleDateString("fr-FR");
    var Victoire1945 = new Date(an, "04", "08").toLocaleDateString("fr-FR");
    var FeteNationale = new Date(an,"06", "14").toLocaleDateString("fr-FR");
    var Assomption = new Date(an, "07", "15").toLocaleDateString("fr-FR");
    var Toussaint = new Date(an, "10", "01").toLocaleDateString("fr-FR");
    var Armistice = new Date(an, "10", "11").toLocaleDateString("fr-FR");
    var Noel = new Date(an, "11", "25").toLocaleDateString("fr-FR");


    var G = an%19;
    var C = Math.floor(an/100);
    var H = (C - Math.floor(C/4) - Math.floor((8*C+13)/25) + 19*G + 15)%30;
    var I = H - Math.floor(H/28)*(1 - Math.floor(H/28)*Math.floor(29/(H + 1))*Math.floor((21 - G)/11));
    var J = (an*1 + Math.floor(an/4) + I + 2 - C + Math.floor(C/4))%7;
    var L = I - J;
    var MoisPaques = 3 + Math.floor((L + 40)/44);
    var JourPaques = L + 28 - 31*Math.floor(MoisPaques/4);
    var Paques = new Date(an, MoisPaques-1, JourPaques).toLocaleDateString("fr-FR");
    var VendrediSaint = new Date(an, MoisPaques-1, JourPaques-2).toLocaleDateString("fr-FR");
    var LundiPaques = new Date(an, MoisPaques-1, JourPaques+1).toLocaleDateString("fr-FR");
    var Ascension = new Date(an, MoisPaques-1, JourPaques+39).toLocaleDateString("fr-FR");
    var LundiPentecote = new Date(an, MoisPaques-1, JourPaques+50).toLocaleDateString("fr-FR");

    return new Array(JourAn, VendrediSaint, LundiPaques, FeteTravail, Victoire1945, Ascension, LundiPentecote, FeteNationale, Assomption, Toussaint, Armistice, Noel);
}


//ajax thousand tickets checking--------/

var dates = {};

$('#order_choiceDate').change(function (e) {
    $.ajax({
        url: '/thousand/'+moment(e.target.value, "DD/MM/YYYY").format("YYYY-MM-DD"),
        async: false,
        dataType: 'json',
        success: function (data) {
            dates[e.target.value]=data;
        }
    });

    if (dates[e.target.value]>=1000){
        $(".btn-add").prop("disabled", true);
        $("#messageAddButton").text("Il n'y a plus de billets disponibles pour cette date");
    } else if ((dates[e.target.value] + $("#tickets >div").length)>1000){
        $(".btn-add").prop("disabled", true);
        $("#messageAddButton").text("Vous ne pouvez plus commander de billets pour cette date, veuillez retirer des billets");
    } else {
        $(".btn-add").prop("disabled", false);
        $("#messageAddButton").text("");
    }

    if ($("#tickets >div").length === 0) {
        $("#validButton").prop("disabled", true);
        $("#messageValid").text("Vous devez ajouter au moins un billet");
    } else {
        $("#validButton").prop("disabled", false);
        $("#messageValid").text("");
    }
});

$.validator.addMethod("moreThousand",
    function (value, element) {
        return this.optional(element) || dates[value] < 1000;
    },
    "il n'y a plus de billets à vendre pour cette date"
);

$.validator.addMethod("almostThousand",
    function (value, element) {
        return this.optional(element) || (dates[$("#order_choiceDate").val()] + ($("#tickets >div").length)+1) <= 1000;
    },
    "vous devez retirer des billets de votre commande"
);

var today = (new Date());


$.validator.addMethod("notSunday", function (value, element) {
    return this.optional(element) || makeDate(value).getDay()!==0;
}, "vous ne pouvez pas reserver pour le dimanche" );

$.validator.addMethod("notTuesday", function (value, element) {
    return this.optional(element) || makeDate(value).getDay()!==2;
}, "le musée est fermé le mardi" );

$.validator.addMethod("notHoliday", function (value, element) {
    return this.optional(element) || JoursFeries(makeDate(value).getFullYear()).indexOf(value) === -1;
}, "vous ne pouvez pas reserver pour un jour férié" );

$.validator.addMethod("pastDay", function (value, element) {
    return this.optional(element) || moment(value, "DD/MM/YYYY").diff(moment(), 'days') >= 0;
}, "vous ne pouvez pas reserver pour un jour passé" );

$.validator.addMethod("notFullAfternoon", function (value, element) {
    return this.optional(element) ||  (
        (
            $("#order_choiceDate").val() == moment().format('DD/MM/YYYY')
            && moment().format('H') <= 14
            && !$("#order_half").is(":checked")
        )
        || $("#order_half").is(":checked")
        || $("#order_choiceDate").val() != moment().format('DD/MM/YYYY')
    ) ;
}, "vous ne pouvez pas prendre de billets journée après 14h" );


//validator method--------------------------------------------/

$("form[name=order]").validate({
    validClass: 'is-valid',
    errorClass: 'is-invalid',
    errorElement: 'div',
    errorPlacement: function(error, e) {
        error.addClass("invalid-feedback");
        e.parents('.form-group').append(error);
    },
    highlight: function(e) {
        $(e).closest('.form-group').removeClass('has-success has-error').addClass('has-error');
    },
    success: function(e) {
        e.closest('.form-group').removeClass('has-success has-error');
        e.closest('.invalid-feedback').remove();
    },
    rules: {
        "order[email]": {
            required: true,
            email: true
        },
        "order[choiceDate]": {
            required: true,
            notSunday: true,
            notTuesday: true,
            notHoliday: true,
            pastDay: true,
            notFullAfternoon: true,
            moreThousand: true,
            almostThousand: true
        },
        "order[half]": {
            notFullAfternoon: true
        }

    },
    messages: {
        "order[email]": {
            required: "entrez votre adresse mail",
            email: "format email incorrect"
        },
        "order[choiceDate]": {
            required: "entrez votre date de visite",
            date: "entrez votre date au format..."
        }

    }
});


//cart scroll down-------------------------------------------------/

var $sidebar   = $("#cart"),
    $window    = $(window),
    offset     = $sidebar.offset(),
    topPadding = 15;

$window.scroll(function() {
    if ($window.scrollTop() > offset.top) {
        $sidebar.stop().animate({
            marginTop: $window.scrollTop() - offset.top + topPadding
        });
    } else {
        $sidebar.stop().animate({
            marginTop: 0
        });
    }
});








