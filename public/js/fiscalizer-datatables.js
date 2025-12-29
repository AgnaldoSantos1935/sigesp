window.FiscalizerDataTable = function (selector, options = {}) {

    const defaults = {
        processing: false,
        serverSide: true,
        responsive: true,
        "language": {
            "url": "{{ asset('js/datatables/pt-BR.json') }}"
        },
        order: [[1, 'asc']]
    };

    const config = Object.assign({}, defaults, options);

    return $(selector).DataTable(config);
};
