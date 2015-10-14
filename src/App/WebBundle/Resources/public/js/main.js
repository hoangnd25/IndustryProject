$().ready(function () {
    $('.modal.printable').on('shown.bs.modal', function () {
        $('.modal-dialog', this).addClass('focused');
        $('body').addClass('modalprinter');
    }).on('hidden.bs.modal', function () {
        $('.modal-dialog', this).removeClass('focused');
        $('body').removeClass('modalprinter');
    });
});