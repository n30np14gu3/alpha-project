$(document).ready(function () {
    $('.ui.accordion').accordion({ exclusive: false });
    $('.tabular.menu .item').tab();
    $('.ui.checkbox').checkbox();
    $('.dropdown').dropdown();
    $('.ui.slider').slider();
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

    $('.products').on('click', '.item', function() {
        $('.menu.products .item').removeClass('active');
        if(!$(this).hasClass('dropdown')) {
            $(this).addClass('active').siblings('.item').removeClass('active');
            $('#cost-id').attr('value', $(this).attr('data-cost'));
            $('#product-id').attr('value', $(this).attr('data-product'));
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
        }
    }).modal('show');
}

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

$('#products-form').submit(function (e) {
    e.preventDefault();
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
});

function showTicketModal() {
    $('#ticket-modal').modal('show');
}

function openTicket(id) {
    window.location.replace('/support/ticket/' + id);
}

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