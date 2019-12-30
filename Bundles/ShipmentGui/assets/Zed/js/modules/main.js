/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

module.exports = function(trigger, target, inputDate) {
    $(inputDate).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        minDate: 0,
        defaultData: 0
    }).keyup(function(event) {
        var backspaceButton = 8;
        var deleteButton = 46;
        
        if(event.keyCode === backspaceButton || event.keyCode === deleteButton) {
            $.datepicker._clearDate(this);
        }
    });

    function toggleForm() {
        var selectedOptionValue = $(trigger).val();

        if (!selectedOptionValue) {
            $(target).show();

            return;
        }

        $(target).hide();
    }

    function setDisableFields() {
        var selectedOptionValue = $(trigger).val();
        var $requiredFields = $(target).find('select[required], input[required]');

        $requiredFields.each(function() {
            $(this).attr('disabled', !!selectedOptionValue);
        });
    }

    function toggleHistoryBlock() {
        $('.more-history').click(function(e){
            e.preventDefault();
            var idProductItem = $(this).data('id');
            var $history = $('#history_details_' + idProductItem);
            var $button = $('#history-btn-' + idProductItem);
            var isHidden = $history.hasClass('hidden');

            $history.toggleClass('hidden', !isHidden);
            $button.toggleClass('is-hidden', !isHidden);
            $button.toggleClass('is-shown', isHidden);
        });
    }

    function init() {
        toggleForm();
        setDisableFields();
        toggleHistoryBlock();
    }

    init();

    $(trigger).on('change', init);
};
