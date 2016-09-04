/**
 * Created by root on 26.08.16.
 */

$('#print').click(function() {
    if(document.getElementById("main-table") != null) {
        printData("main-table")
    } else {
        alert('Отсутствует таблица!')
    }
});

$('#showInfoCount').click(function() {
    var text = 'Количество за период рассчитывается по формуле: <br>' +
        '<i>"Текущее кол-во" - "Количество приходов" + "Количество расходов"</i>, <br>' +
        'такой расчет используется на основании того, что в текущее количество входят <b>приходы</b>, ' +
        'которые необходимо <b>вычесть</b>, и <b>расходы</b>, которые нужно <b>прибавить</b>, ' +
        'чтобы получить необходимое количество за период.';
    infoPopUp('Информационное окно', text);
});

function printData(div)
{
    var divToPrint = document.getElementById(div);
    newWin= window.open("");
    newWin.document.write(divToPrint.outerHTML);
    newWin.print();
    newWin.close();
}
$('#inviteButton').click(function() {
    var modal = $('#ModalMain');
    var modalHeader = modal.find('.modal-title');
    var modalBody = modal.find('.modal-body');
    $.ajax({
        url: '/invite',
        type: 'get',
        success: function(data) {
            modalHeader.html('');
            modalBody.html('');
            modalHeader.html($(data).filter('.title-page').val());
            modalBody.html(data);
            modal.modal({
                show: true
            });
        }
    });
});

/**
 * @param title
 * @param text
 */

function infoPopUp(title, text) {
    var modal = $('#ModalMain');
    var modalHeader = modal.find('.modal-title');
    var modalBody = modal.find('.modal-body');
    modalHeader.html('<span class="text-info">'+title+'</span>');
    modalBody.html(text);
    modal.modal({
        show: true
    });
}

function customPopUpText(title, text, reason) {
    var modal = $('#ModalMain');
    var modalHeader = modal.find('.modal-title');
    var modalBody = modal.find('.modal-body');
    if(reason == 'success') {
        modalHeader.html('<h4 class="text-success"><span class="glyphicon glyphicon-ok-circle"></span> '+title+'</h4>');
        modalBody.html(text);
        modal.modal({
            show: true
        });
    } else if(reason == 'err') {
        modalHeader.html('<h4 class="text-danger"><span class="glyphicon-alert glyphicon"></span> '+title+'</h4>');
        modalBody.html(text);
        modal.modal({
            show: true
        });
    } else {
        modalHeader.html(title);
        modalBody.html(text);
        modal.modal({
            show: true
        });
    }
    setTimeout(function() {
        modal.modal('hide')
    }, 2500)
}

function refreshModal(url, modal) {
    var modalHeader = $(modal).find('.modal-title');
    var modalBody = $(modal).find('.modal-body');
    $.ajax({
        url: url,
        type: 'get',
        success: function(data) {
            modalHeader.html('');
            modalBody.html('');
            modalHeader.html($(data).filter('.title-page').val());
            modalBody.html(data);
        }
    });
}

function refreshTable(url, table) {
    $.ajax({
        url: url,
        type: 'get',
        success: function(data) {
            $(table).html($(data).find('#main-table').html());
        }
    });
}