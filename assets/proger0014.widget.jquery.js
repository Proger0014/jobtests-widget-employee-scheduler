(function ($) {

    const ATTRIBUTES = {
        _base_name: 'widget',
        TARGET: `${this._base_name}-target`,
        INPUT: {
            _base_name_input: `${this._base_name}-input`,
            TYPE: `${this._base_name_input}-type`,
            BINDS: `${this._base_name_input}-binds`,
            NAME: `${this._base_name_input}-name`,
        },
        BLOCK: {
            _base_name_block: `${this._base_name}-block`,
            ATTR: this._base_name_block,
            BUTTON: `${this._base_name_block}-button`
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
        time: {
            valueFormatter: (valueRaw) => {
                return `${valueRaw}:00`
            },
            domManipulator: (widget, item) => {
                item.attr('form', null);
            }
        }
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

    function prepareBinds(widget) {
        const bindsItems = widget(`input[${ATTRIBUTES.INPUT.BINDS}]`);

        bindsItems.each((i, elem) => {
            const bindItem = $(elem);
            const targetItemName = bindItem.attr(ATTRIBUTES.INPUT.NAME);

            const bindsAttrArr = bindItem.attr(ATTRIBUTES.INPUT.BINDS).split(' ');
            bindsAttrArr.forEach((bind) => {
                const bindItemTarget = widget(`input[${ATTRIBUTES.INPUT.NAME}="${bind}"]`);
                const bindItemTargetValue = bindItemTarget.prop('value');
                const bindItemTargetType = bindItemTarget.attr(ATTRIBUTES.INPUT.TYPE);
                const formattedValue = TYPE_HANDLERS[bindItemTargetType]
                    .valueFormatter(bindItemTargetValue);

                const existsHiddenValue = widget(`input[name="${targetItemName}"]`);

                if (existsHiddenValue) {
                    existsHiddenValue.prop('value', formattedValue);
                } else {
                    const hidden = createHidden(targetItemName, formattedValue);
                    bindItem.after(hidden);
                }
            });
        })
    }

    $.fn.registerWidget = () => {
        const widget = this;

        $(document).on('submit', (event) => {
            const widgetTarget = `form#${widget.attr(ATTRIBUTES.TARGET)}`;

            if (widgetTarget !== event.target) {
                return;
            }

            prepareInputs(widget);
            prepareBinds(widget);
        })
    };
}(jQuery));