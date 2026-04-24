window.renderPaginationTable = function ({
    pagerSelector,
    tableBodySelector,
    rows,
    emptyRowHtml,
    pageSize = 10
}) {
    const tableBody = document.querySelector(tableBodySelector);
    const pager = window.jQuery ? window.jQuery(pagerSelector) : null;

    if (!tableBody || !pager || typeof pager.pagination !== 'function') {
        return;
    }

    if (pager.data('pagination')) {
        pager.pagination('destroy');
    }

    if (!Array.isArray(rows) || rows.length === 0) {
        tableBody.innerHTML = emptyRowHtml;
        return;
    }

    pager.pagination({
        dataSource: rows,
        pageSize,
        showSizeChanger: false,
        callback: function (data) {
            tableBody.innerHTML = data.join('');
        }
    });
};

(function () {
    if (typeof window.jQuery === 'undefined' || !window.jQuery.fn.pagination || !window.jQuery.fn.pagination.defaults) {
        return;
    }
    window.jQuery.extend(true, window.jQuery.fn.pagination.defaults, {
        showNavigator: true,
        formatNavigator: 'Showing <%= rangeStart %> to <%= rangeEnd %> of <%= totalNumber %> results',
        className: 'clinic-os-pagination',
        prevText: '&lt;',
        nextText: '&gt;',
        hideOnlyOnePage: false,
        showSizeChanger: false
    });
})();
