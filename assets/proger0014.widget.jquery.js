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

    function createHidden(name, value) {
        return $(`<input>`)
            .attr('hidden', null)
            .attr('name', name)
            .attr('value', value);
    }

    function configureInputs(widget) {
        const inputs = widget('input');
        inputs.attr('form', '');
    }

    function prepareSwitches(widget) {
        const switches = widget(`input[${ATTRIBUTES.INPUT.TYPE}="switch"]`);

        switches.each((i, elem) => {
            const switchItem = $(elem);

            const switchInputName = switchItem.attr(ATTRIBUTES.INPUT.NAME);
            const existsHiddenValue = widget(`input[name="${switchInputName}"]`);

            const switchVal = switchItem.prop('checked')
                ? '1' : '0';

            if (existsHiddenValue) {
                existsHiddenValue.attr('value', switchVal);
            } else {
                const hidden = createHidden(switchInputName, switchVal);
                switchItem.after(hidden);
            }
        });
    }

    $.fn.registerWidget = function() {
        const widget = this;

        configureInputs(widget);

        $(document).on('submit', (event) => {
            const widgetTarget = `form#${widget.attr('widget-target')}`;

            if (widgetTarget !== event.target) {
                return;
            }

            prepareSwitches(widget);
        })
    };
}(jQuery));