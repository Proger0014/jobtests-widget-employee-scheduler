(function ($) {

    const ATTRIBUTES = {
        _base_name: 'widget',
        INPUT: {
            _base_name_input: `${this._base_name}-input`,
            TYPE: `${this._base_name_input}-type`,
            BINDS: `${this._base_name_input}-binds`,
            NAME: `${this._base_name_input}-name`,
        }
    }

    const TYPE_HANDLERS = {
        switch: {
            valueFormatter: (valueRaw) => {
                return valueRaw
                    ? '1' : '0';
            },
            domManipulator: (widget, item) => {
                item.attr('form', null);
                const switchInputName = item.attr(ATTRIBUTES.INPUT.NAME);
                const existsHiddenValue = widget(`input[name="${switchInputName}"]`);

                const switchVal = this.valueFormatter(item.prop('checked'));

                if (existsHiddenValue) {
                    existsHiddenValue.attr('value', switchVal);
                } else {
                    const hidden = createHidden(switchInputName, switchVal);
                    item.after(hidden);
                }
            }
        },
    };

    function createHidden(name, value) {
        return $(`<input>`)
            .attr('hidden', null)
            .attr('name', name)
            .attr('value', value);
    }

    function prepareInputs(widget) {
        const inputs = widget(`input[${ATTRIBUTES.INPUT.TYPE}]`);

        inputs.each((i, elem) => {
            const jqueryItem = $(elem);
            const type = jqueryItem.attr(ATTRIBUTES.INPUT.TYPE);

            TYPE_HANDLERS[type].domManipulator(widget, jqueryItem);
        })
    }

    $.fn.registerWidget = function() {
        const widget = this;

        $(document).on('submit', (event) => {
            const widgetTarget = `form#${widget.attr('widget-target')}`;

            if (widgetTarget !== event.target) {
                return;
            }

            prepareInputs(widget);
        })
    };
}(jQuery));