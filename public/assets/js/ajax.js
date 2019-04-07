$('.ui.accordion').accordion({ exclusive: false });
$('.tabular.menu .item').tab();
$('.ui.checkbox').checkbox();
$('.dropdown').dropdown({ maxSelections: 3 });
$('.info-popup').popup();
$('.ui.slider').slider();
$('.message .close')
    .on('click', function() {
        $(this)
            .closest('.message')
            .transition('fade')
        ;
    })
;

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
                $('#account-status').addClass('active').attr('data-tooltip', 'Аккаунт подтвержден');

            }
            else{
                showToast('Ошибка в подтверждении аккаунта', 'error', 5000, 'steam');
            }
        },
        error: function (data) {
            showToast('Ошибка запроса', 'error', 5000, 'steam');
        }
    });
});

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

function showAuthForm() {
    $('#auth-modal').modal('show');
}

function showRegisterForm() {
    $('#register-modal').modal('show');
}

function showRepassForm() {
    $('#repass-modal').modal('show');
}

$('#register-form').submit(function (e) {
    e.preventDefault();
    $.ajax(
        {
            type: "POST",
            url: "/action/register",
            data: $("#register-form").serialize(),
            success: function(data) {
                data = JSON.parse(data);
                if(data.status !== "OK"){
                    showToast(data.message, 'error', 3000, 'microchip');
                }
                else
                    showToast('Пользователь успешно зарегестрирован!<br>На почтовый ящик была отправлены дальнейшие инструкции', 'success', 5000, 'microchip');
            }
        }
    );
});