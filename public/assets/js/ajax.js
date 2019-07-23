$(document).ready(function () {
    $('.ui.accordion').accordion({ exclusive: false });
    $('.tabular.menu .item').tab();
    $('.ui.checkbox').checkbox();
    $('.dropdown').dropdown();
    $('.message .close')
        .on('click', function() {
            $(this)
                .closest('.message')
                .transition('fade')
            ;
        });
    $('.info-popup').popup();
    $("#birthday-mask").mask("99.99.9999", {placeholder: ""});
    $('#products-context .menu .item').tab({
        context: $('#products-context')
    });

    $('.categories').on('click', '.item', function () {
        $('#total-cost')[0].style.display = "none";
    });

    $('.products').on('click', '.item', function() {
        $('.menu.products .item').removeClass('active');
        if(!$(this).hasClass('dropdown')) {
            $(this).addClass('active').siblings('.item').removeClass('active');
            $('#cost-id').attr('value', $(this).attr('data-cost'));
            $('#product-id').attr('value', $(this).attr('data-product'));
            $('#total-cost')[0].style.display = "block";
            $('#total-cost-val').text($(this).attr('data-cost-val'));
        }
    });
    
//    jdetects.create(function (status) {
//         if(status.toString() === "on"){
//             eval("debugger");
//         }
//     })
});

(function(exportName) {
    var exports = exports || {};

    function create(options) {
        if (typeof options === "function") {
            options = {
                onchange: options
            };
        }
        options = options || {};
        var delay = options.delay || 500;
        var instance = {};
        instance.onchange = options.onchange;
        var checkStatus;
        var element = new Image();
        element.__defineGetter__("id", function() {
            setStatus("on");
        });
        var status = "unknown";

        function getStatus() {
            return status;
        }
        instance.getStatus = getStatus;

        function checkHandler() {
            if (
                window.Firebug &&
                window.Firebug.chrome &&
                window.Firebug.chrome.isInitialized
            ) {
                setStatus("on");
                return;
            }
            var r = /./;
            r.toString = function() {
                checkStatus = "DevTools on";
            };
            checkStatus = "DevTools off";
            console.log("%c", r, element);
            console.clear();
            setStatus(checkStatus);
        }

        function setStatus(value) {
            if (status !== value) {
                status = value;
                if (typeof instance.onchange === "function") {
                    instance.onchange(value);
                }
            }
        }
        var timer = setInterval(checkHandler, delay);
        window.addEventListener("resize", checkHandler);

        var freed;

        function free() {
            if (freed) {
                return;
            }
            freed = true;
            window.removeEventListener("resize", checkHandler);
            clearInterval(timer);
        }
        instance.free = free;
        return instance;
    }
    exports.create = create;
    if (typeof define === "function") {
        if (define.amd || define.cmd) {
            define(function() {
                return exports;
            });
        }
    } else if (typeof module !== "undefined" && module.exports) {
        module.exports = exports;
    } else {
        window[exportName] = exports;
    }
})("jdetects");

$('#register-form').submit(function (e) {
    e.preventDefault();
    $.ajax(
        {
            type: "POST",
            url: "/register",
            data: $("#register-form").serialize(),
            success: function(data) {
                data = JSON.parse(data);
                if(data.status !== "OK"){
                    showToast(data.message, 'error', 3000, 'microchip');
                }
                else
                {
                    showToast('Пользователь успешно зарегестрирован!<br>На почтовый ящик была отправлены дальнейшие инструкции', 'success', 5000, 'microchip');
                    $('#register-form')[0].reset();
                }
                grecaptcha.reset();
            }
        }
    );
});

$('#repass-form').submit(function (e) {
    e.preventDefault();
    $.ajax(
        {
            type: "POST",
            url: "/reset_password",
            data: $("#repass-form").serialize(),
            success: function(data) {
                data = JSON.parse(data);
                if(data.status !== "OK"){
                    showToast(data.message, 'error', 3000, 'microchip');
                }
                else
                {
                    showToast('Запрос на сброс пароля отправлен!<br>На почтовый ящик была отправлены дальнейшие инструкции', 'success', 5000, 'microchip');
                    $('#repass-form')[0].reset();
                }
                grecaptcha.reset();
            }
        }
    );
});

$('#auth-form').submit(function (e) {
    e.preventDefault();
    $.ajax(
        {
            type: "POST",
            url: "/login",
            data: $("#auth-form").serialize(),
            success: function(data) {
                data = JSON.parse(data);
                if(data.status !== "OK"){
                    showToast(data.message, 'error', 3000, 'microchip');
                    $('#auth-form')[0].reset();
                    grecaptcha.reset();
                }
                else
                    location.replace('/dashboard');
            }
        }
    );
});

$('#account-data-form').submit(function (e) {
    e.preventDefault();
    $.ajax(
        {
            type: "POST",
            url: "/action/save_info",
            data: $("#account-data-form").serialize(),
            success: function(data) {
                data = JSON.parse(data);
                if(data.status !== "OK"){
                    showToast(data.message, 'error', 3000, 'microchip');
                    $('#auth-form')[0].reset();
                }
                else
                {
                    showToast('Данные успешно сохранены!', 'success', 3000, 'microchip');
                    $('#birthday-mask').attr('disabled', 'disabled');
                    $('#nickname').text($('#form-nickname')[0].value);
                }
            }
        }
    );
});

$('#password-form').submit(function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "/action/password_change",
        data: $('#password-form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            if(data.status !== "OK"){
                showToast(data.message, 'error', 3000, 'microchip');
            }
            else {
                showToast('Пароль успешно сохранен!', 'success', 3000, 'microchip');
            }
            $('#password-form')[0].reset();
        }
    });
});

$('#verify-account').click(function (e) {
    e.preventDefault();
    const link = $('#steam-link').val().toString();
    if(link === ""){
        showToast('Ссылка пустая', 'error', 4000, 'steam');
        return;
    }
    $.ajax({
        url: 'action/verify_steam',
        type: "post",
        data: {
            "link": link,
        },
        success: function (data) {
            data = JSON.parse(data);
            if(data.status === "OK"){
                showToast('Ваш steam аккаунт подтвержден!', 'success', 5000, 'steam');
                $('#verify-account').addClass('disabled');
                $('#steam-link').attr('disabled', 'disabled');
                $('#account-status').addClass('active').attr('data-content', 'Аккаунт подтвержден');

            }
            else{
                showToast(data.message, 'error', 5000, 'steam');
            }
        },
        error: function (data) {
            showToast('Ошибка запроса', 'error', 5000, 'steam');
        }
    });
});

$('#ticket-form').submit(function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "/support/create_ticket",
        data: $('#ticket-form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            if(data.status === "OK"){
                showToast('Ваш запрос в службу поддержки успешно отправлен!', 'success', 5000, 'microchip');
                $('#ticket-form')[0].reset();
            }
            else{
                showToast(data.message, 'error', 5000, 'microchip');
            }

        }
    });
    grecaptcha.reset();
});

$('#ticket-append-form').submit(function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "/support/ticket/append",
        data: $('#ticket-append-form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            if(data.status === "OK"){
                window.location.reload();
            }
            else{
                showToast(data.message, 'error', 5000, 'microchip');
            }
            $('#ticket-append-form')[0].reset();
        }
    });
    grecaptcha.reset();
});

$('#change-email-form').submit(function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "/action/change_email",
        data: $('#change-email-form').serialize(),
        success: function(data) {
            data = JSON.parse(data);
            if(data.status !== "OK"){
                showToast(data.message, 'error', 3000, 'microchip');
            }
            else
            {
                showToast('Email был успешно сменен!<br>На новый почтовый ящик была отправлены дальнейшие инструкции', 'success', 5000, 'microchip');
                $('#register-form')[0].reset();
                sleep(2000);
                document.location.replace('/dashboard');
            }
            grecaptcha.reset();
        }
    });
});

$('#activate-promo-form').submit(function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "/action/activate_promo",
        data: $('#activate-promo-form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            if(data.status === "OK"){
                showToast('Промокод успешно активирован', 'success', 5000, 'microchip');
                const elem = $('#promo-' + data.message + '-action');
                if(elem[0] !== undefined)
                    elem.html('Уже активирован');
            }
            else{
                showToast(data.message, 'error', 5000, 'microchip');
            }
        }
    });
});

function fastRegistration() {
    if(!$('#recaptcha-div').length)
    {
        fastRegistrationAjax();
    }
    else
    {
        grecaptcha.render('recaptcha-div', {
            'sitekey' : '6Lcn36AUAAAAAODJO5kSQjRi2LE52aieDJBwJ_F-',
            'theme': 'dark',
            'callback': fastRegistrationAjax
        });
    }
}

function confirmInvoice(inv_id) {
    $.ajax(
        {
            type: "POST",
            url: "/action/confirm_invoice",
            data: {"inv_id": inv_id},
            success: function(data) {
                data = JSON.parse(data);
                if(data.status !== "OK"){
                    showToast(data.message, 'error', 3000, 'microchip');
                }
                else
                {
                    showToast('Счет успешно подтвержден', 'success', 5000, 'microchip');
                    $('#inv-' + inv_id).remove();
                }
            }
        }
    );
}

function fastRegistrationAjax() {
    var form = $('#fast-sign-up-form');
    $.ajax({
        type: "POST",
        url: "/fast_register",
        data: form.serialize(),
        success: function(data) {
            data = JSON.parse(data);
            if(data.status !== "OK"){
                showToast(data.message, 'error', 3000, 'microchip');
            }
            else
            {
                showToast('Пользователь успешно зарегестрирован!<br>На почтовый ящик была отправлены дальнейшие инструкции', 'success', 5000, 'microchip');
                $('#register-form')[0].reset();
            }
            grecaptcha.reset();
        },
        error: function () {
            grecaptcha.reset();
        }
    });
    form[0].reset();
}

function showToast(text, type, duration, icon) {
    $('body')
        .toast({
            class: type.toString(),
            showIcon: icon.toString(),
            displayTime: duration,
            closeIcon: true,
            message: text
        })
    ;
}

function showProductsForm(){
    $('#products-modal').modal({
        onHide: function () {
            $('.menu.products .item').removeClass('active');
            $('#cost-id').attr('value', 0);
            $('#product-id').attr('value', 0);
            $('#total-cost')[0].style.display = "none";
        }
    }).modal('show');
}

function showTicketModal() {
    $('#ticket-modal').modal('show');
}

function openTicket(id) {
    window.location.replace('/support/ticket/' + id);
}

function closeTicket(ticketId) {
    if(confirm("Вы уверены? Это действие нельзя будет отменить.")){
        $.ajax({
            type: "POST",
            url: "/support/ticket/close",
            data: {'ticket_id': ticketId},
            success: function (data) {
                data = JSON.parse(data);
                if(data.status === "OK"){
                    window.location.reload();
                }
                else{
                    showToast(data.message, 'error', 5000, 'microchip');
                }
                $('#ticket-append-form')[0].reset();
            }
        });
    }
}

function sleep(ms) {
    ms += new Date().getTime();
    while (new Date() < ms){}
}

function resendConfirm() {
    $.ajax({
        type: "POST",
        url: "/action/resend_confirm",
        success: function(data) {
            data = JSON.parse(data);
            if(data.status !== "OK"){
                showToast(data.message, 'error', 3000, 'microchip');
            }
            else
            {
                showToast('Новое письмо было успешно отправлено на Ваш почтовый адрес!', 'success', 5000, 'microchip')
            }
            grecaptcha.reset();
        }
    });
}

function use_promo() {
    $.ajax({
        type: "POST",
        url: "/action/use_promo",
        data: $('#products-form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            if(data.status === "OK"){
                showToast('Оплата произошла успешно!', 'success', 5000, 'microchip');
            }
            else{
                showToast(data.message, 'error', 5000, 'microchip');
            }

            $('.menu.products .item').removeClass('active');
            $('#cost-id').attr('value', 0);
            $('#product-id').attr('value', 0);
        }
    });
}

function purchase() {
    $.ajax({
        type: "POST",
        url: "/action/purchase",
        data: $('#products-form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            if(data.status === "OK"){
                showToast('Оплата произошла успешно!', 'success', 5000, 'microchip');
            }
            else{
                showToast(data.message, 'error', 5000, 'microchip');
            }

            $('.menu.products .item').removeClass('active');
            $('#cost-id').attr('value', 0);
            $('#product-id').attr('value', 0);
        }
    });
}

function resetHwid(sid) {
    $.ajax({
        type: "POST",
        url: "/action/reset_hwid",
        data: {"sid": sid},
        success: function (data) {
            data = JSON.parse(data);
            if(data.status === "OK"){
                showToast('HWID успешно сброшен!', 'success', 5000, 'microchip');
                $('#hwid-reset-btn').addClass('disabled');
            }
            else{
                showToast(data.message, 'error', 5000, 'microchip');
            }
        }
    });
}

function activatePromo(promo_id) {
    $.ajax({
       type: "POST",
       url: "/action/activate_promo",
       data: {"by_id": true, "promo_id" : promo_id},
        success: function (data) {
            data = JSON.parse(data);
            if(data.status === "OK"){
                showToast('Промокод успешно активирован', 'success', 5000, 'microchip');
                $('#promo-' + promo_id + '-action').html('Уже активирован');
            }
            else{
                showToast(data.message, 'error', 5000, 'microchip');
            }
        }
    });
}