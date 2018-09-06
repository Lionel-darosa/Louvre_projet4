
//datepicker------------------------------------------------------/

$('#order_choiceDate').datepicker({
    language: "fr",
    weekStart: 1,
    orientation: "top auto",
    todayHighlight: true
});


//css------------------------------------------------------------/

$('#orderForm li').css('color', 'red');

//add and remove ticket form button-------------------------------/

var tickets;


$(".btn-add").on("click", function() {
    $("form[name=order]").valid();
    var $collectionHolder = $($(this).data("rel"));
    var index = $collectionHolder.data("index");
    var prototype = $collectionHolder.data("prototype");
    $collectionHolder.append(prototype.replace(/__name__/g, index));
    $collectionHolder.data("index", index+1);

    tickets = $('#tickets > *').length;

    console.log(tickets);

    $('.birth').datepicker({
        language: "fr",
        weekStart: 1,
        orientation: "top auto",
        todayHighlight: true
    });
    
    //pricing-------------------/
    //
    // $("#order_tickets_"+index+"_birth").blur(function (e) {
    //     var age = moment().diff(moment(e.target.value), 'years');
    //     console.log(age);
    //     if (age < 4){
    //
    //     } else if (age >= 4 && age < 12){
    //
    //     } else if (age >= 12 && age < 60){
    //
    //     } else if (age >= 60){
    //
    //     }
    // })

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

    $("order_tickets_"+index+"_birth").rules("add", {
        required: true,
        messages : {
            required: "veuillez entrer la date de naissance"
        }
    });

});

$("body").on("click", ".btn-remove", function() {
    $($(this).data("rel")).remove();
    tickets = $('#tickets > *').length;
    console.log(tickets);
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




//ajax thousand tickets checking------------/

var dates = {};

$('#order_choiceDate').change(function (e) {
    var date = moment(e.target.value, "DD/MM/YYYY").format("YYYY-MM-DD");
    console.log(date);

    $.ajax({
        url: '/thousand/'+moment(e.target.value, "DD/MM/YYYY").format("YYYY-MM-DD"),
        async: false,
        dataType: 'json',
        success: function (data) {
            dates[e.target.value]=data;

        }

    });
});

$.validator.addMethod("moreThousand",
    function (value, element) {
        return this.optional(element) || dates[value] < 1000;
    },
    "il n'y a plus de billets à vendre pour cette date"
);

$.validator.addMethod("almostThousand",
    function (value, element) {
    console.log(dates[$("#order_choiceDate").val()] + $('#tickets > *').length);
        return this.optional(element) || (dates[$("#order_choiceDate").val()] < 10) && ((dates[$("#order_choiceDate").val()] + $('#tickets > *').length) > 10)
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

$("#order_half").click(function () {
    $("form[name=order]").valid();
});

// //email check
//
// $('#order_email').blur(function (e) {
//     var regexMail = /.+@.+\..+/;
//     $('#mailErrors ul li').remove();
//     if (e.target.value === ''){
//         $('#mailErrors ul').append('<li>Veuillez entrer votre adresse mail</li>').css('color', 'red');
//     } else if (!regexMail.test(e.target.value)){
//         $('#mailErrors ul').append('<li>Votre adresse mail n\'est pas correcte</li>').css('color', 'red');
//     }
// });
//
//
// //order date check
//
// $('#order_choiceDate').blur(function (e) {
//     $('#dateErrors li').remove();
//
//     var regexDate =/([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/([0-2][0-9][0-9][0-9])/;
//
//     var selectDateMonth = e.target.value.substring(3,5);
//     var selectDate = new Date(e.target.value.substring(6,10), selectDateMonth-=1, e.target.value.substring(0,2));
//
//
//     var today = (new Date());
//     console.log(today);
//
//     if (e.target.value === '') {
//         $('#dateErrors').append('<li>Veuillez entrer date</li>').css('color', 'red');
//     }else if (!regexDate.test(e.target.value)){
//         $('#dateErrors').append('<li>Veuillez respecter le format JJ/MM/AAAA</li>').css('color', 'red');
//     }
//     if (selectDate.getDay() === 0) {
//         $('#dateErrors').append('<li>Vous ne pouvez pas prendre de billets pour le dimanche</li>').css('color', 'red');
//     }
//     if (selectDate.getDay() === 2) {
//         $('#dateErrors').append('<li>Le musée est fermé le Mardi</li>').css('color', 'red');
//     }
//     if (selectDate.toLocaleDateString("fr-FR") < today.toLocaleDateString("fr-FR")) {
//         $('#dateErrors').append('<li>Vous ne pouvez pas réserver pour un jour passé</li>').css('color', 'red');
//     }
//     if (selectDate.toLocaleDateString("fr-FR") === today.toLocaleDateString("fr-FR") && today.getHours() > 13) {
//         $('#dateErrors').append('<li>Vous ne pouvez pas prendre de billet journée après 14 heures</li>').css('color', 'red');
//     }
//
// });







