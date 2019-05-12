$(document).ready(function () {
    const today = new Date();
    $('#games-create-context .menu .item').tab({
        context: $('#games-create-context')
    });

    $('#games-modify-context .menu .item').tab({
        context: $('#games-modify-context')
    });

    $('#countries-context .menu .item').tab({
        context: $('#countries-context')
    });

    $('#search-game-dropdown').dropdown({
        onChange: function (a) {
            $.ajax({
                method: "POST",
                url: "/action/admin/get_game_data",
                data: {"game_id": a},
                success: function (data) {
                    data = JSON.parse(data);
                    if(data.status !== "OK"){
                        showToast(data.message, 'error', 3000, 'microchip');
                    }
                    else{
                        $('#game-mod-name').val(data.game_name);
                        $('#game-mod-last-update').val(data.last_update);
                        $('#game-mod-game-id').val(data.game_id);
                        $('#game-mod-modules').dropdown({
                           values:  data.game_modules
                        });
                    }
                }
            });
        }
    });

    $('#game-last-update').calendar({
        maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate() + 1)
    });
});

$('#create-module-form').submit(function (e) {
    e.preventDefault();
    $.ajax({
        method: "POST",
        url: "/action/admin/create_module",
        data: $('#create-module-form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            if(data.status !== "OK"){
                showToast(data.message, 'error', 3000, 'microchip');
            }
            else {
                window.location.reload();
            }
        }
    });
});

$('#modify-game-form').submit(function (e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
    $.ajax({
       method: 'POST',
       url: '/action/admin/update_game',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
           data = JSON.parse(data);
            if(data.status !== "OK"){
                showToast(data.message, 'error', 3000, 'microchip');
            }
            else {
                showToast('Изменения сохранены', 'success', 3000, 'steam');
                $('#game-mod-last-update').val(data.message);
            }
        }
    });
});

$('#create-game-form').submit(function (e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
    $.ajax({
        method: "POST",
        url: "/action/admin/create_game",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            data = JSON.parse(data);
            if(data.status !== "OK"){
                showToast(data.message, 'error', 3000, 'microchip');
            }
            else {
                window.location.reload();
            }
        }
    });
});


$('#admin-ticket-append-form').submit(function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "/support/admin/ticket/append",
        data: $('#admin-ticket-append-form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            if(data.status === "OK"){
                window.location.reload();
            }
            else{
                showToast(data.message, 'error', 5000, 'microchip');
            }
            $('#admin-ticket-append-form')[0].reset();
        }
    });
});


function acceptTicket(ticket_id) {
    if(!confirm('После принятие обращения оно будет закреплено за Вами. Продолжить?'))
        return;

    $.ajax({
        method: "POST",
        url: "/support/admin/accept",
        data: {"id": ticket_id},
        success: function (data) {
            data = JSON.parse(data);
            if(data.status !== "OK"){
                showToast(data.message, 'error', 3000, 'microchip');
            }
            else {
                window.location.replace('/support/admin/ticket/' + ticket_id);
            }
        }
    });
}

$('#create-county-form').submit(function (e) {
    e.preventDefault();
    $.ajax({
        method: "POST",
        url: "/action/admin/create_country",
        data: $('#create-county-form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            if(data.status !== "OK"){
                showToast(data.message, 'error', 3000, 'microchip');
            }
            else {
                window.location.reload();
            }
        }
    })
});

function openTicketStaff(ticket_id) {
    window.location.replace('/support/admin/ticket/' + ticket_id);
}

function closeTicketStaff(ticket_id) {
    const reason = prompt('Введите причину закрытия обращения');
    if(!reason)
        return;

    $.ajax({
        method: "POST",
        url: "/support/admin/ticket/close",
        data: {"id": ticket_id, "reason" : reason},
        success: function (data) {
            data = JSON.parse(data);
            if(data.status !== "OK"){
                showToast(data.message, 'error', 3000, 'microchip');
            }
            else {
                window.location.reload();
            }
        }
    });
}

$('#create-increment-form').submit(function (e) {
    e.preventDefault();
    $.ajax({
        method: "POST",
        url: "/action/admin/create_increment",
        data: $('#create-increment-form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            if(data.status !== "OK"){
                showToast(data.message, 'error', 3000, 'microchip');
            }
            else {
                window.location.reload();
            }
        }
    })
});

$('#create-cost-form').submit(function (e) {
    e.preventDefault();
    $.ajax({
        method: "POST",
        url: "/action/admin/create_cost",
        data: $('#create-cost-form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            if(data.status !== "OK"){
                showToast(data.message, 'error', 3000, 'microchip');
            }
            else {
                window.location.reload();
            }
        }
    })
});

$('#create-product-feature-form').submit(function (e) {
    e.preventDefault();
    $.ajax({
        method: "POST",
        url: "/action/admin/create_product_feature",
        data: $('#create-product-feature-form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            if(data.status !== "OK"){
                showToast(data.message, 'error', 3000, 'microchip');
            }
            else {
                window.location.reload();
            }
        }
    })
});

$('#create-product-form').submit(function (e) {
    e.preventDefault();
    $.ajax({
        method: "POST",
        url: "/action/admin/create_product",
        data: $('#create-product-form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            if(data.status !== "OK"){
                showToast(data.message, 'error', 3000, 'microchip');
            }
            else {
                window.location.reload();
            }
        }
    })
});