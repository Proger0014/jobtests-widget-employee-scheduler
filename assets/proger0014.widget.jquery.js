(function ($) {

    function _attribues() {
        const baseName = 'widget';

        const target = `${baseName}-target`;

        function input(base) {
            const baseName = `${base}-input`;

            return {
                TYPE: `${baseName}-type`,
                BINDS: `${baseName}-binds`,
                NAME: `${baseName}-name`,
                CONTAINER: `${baseName}-container`
            };
        }

        function block(base) {
            const baseName = `${base}-block`;

            return {
                ATTR: `${baseName}`,
                BUTTON: `${baseName}-button`
            };
        }

        return {
            TARGET: target,
            INPUT: input(baseName),
            BLOCK: block(baseName)
        }
    }

    const ATTRIBUTES = _attribues();

    const TYPE_HANDLERS = {
        switch: {
            valueFormatter: (valueRaw) => {
                return valueRaw
                    ? '1' : '0';
            },
            domManipulator: (widget, item) => {
                item.attr('form', null);
                const switchInputName = item.attr(ATTRIBUTES.INPUT.NAME);
                const existsHiddenValue = widget.find(`input[name="${switchInputName}"]`);

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

    const BLOCK_HANDLERS = {
        default: (widget, block) => {
            const inputContainers = block.find(`[${ATTRIBUTES.INPUT.CONTAINER}]`);

            const buttons = block.find(`button[${ATTRIBUTES.BLOCK.BUTTON}]`);
            buttons.each((i, elem) => {
                const button = $(elem);

                const buttonType = button.attr(ATTRIBUTES.BLOCK.BUTTON);

                switch (buttonType) {
                    case 'edit':
                        configureEditButton(button, inputContainers);
                        break;
                    case 'delete':
                        configureRemoveButton(button, inputContainers);
                        break;
                }
            })
        }
    };

    function configureRemoveButton(button, target) {
        button.on('click', () => {
            target.forEach((item) => {
                // TODO
            });
        })
    }

    function configureEditButton(button, target) {
        button.on('click', () => {
            target.each((i, item) => {
                disableInputToggle($(item));
            });
        })
    }

    function disableInputToggle(inputContainer) {
        inputContainer.toggleClass('disabled');
        inputContainer.find('input').get(0).toggleAttribute('disabled');
    }

    function createHidden(name, value) {
        return $(`<input>`)
            .attr('hidden', null)
            .attr('name', name)
            .attr('value', value);
    }

    function prepareInputs(widget) {
        const inputs = widget.find(`input[${ATTRIBUTES.INPUT.TYPE}]`);

        inputs.each((i, elem) => {
            const jqueryItem = $(elem);
            const type = jqueryItem.attr(ATTRIBUTES.INPUT.TYPE);

            TYPE_HANDLERS[type].domManipulator(widget, jqueryItem);
        })
    }

    function prepareBinds(widget) {
        const bindsItems = widget.find(`input[${ATTRIBUTES.INPUT.BINDS}]`);

        bindsItems.each((i, elem) => {
            const bindItem = $(elem);
            const targetItemName = bindItem.attr(ATTRIBUTES.INPUT.NAME);

            const bindsAttrArr = bindItem.attr(ATTRIBUTES.INPUT.BINDS).split(' ');
            bindsAttrArr.forEach((bind) => {
                const bindItemTarget = widget.find(`input[${ATTRIBUTES.INPUT.NAME}="${bind}"]`);
                const bindItemTargetValue = bindItemTarget.prop('value');
                const bindItemTargetType = bindItemTarget.attr(ATTRIBUTES.INPUT.TYPE);
                const formattedValue = TYPE_HANDLERS[bindItemTargetType]
                    .valueFormatter(bindItemTargetValue);

                const existsHiddenValue = widget.find(`input[name="${targetItemName}"]`);

                if (existsHiddenValue) {
                    existsHiddenValue.prop('value', formattedValue);
                } else {
                    const hidden = createHidden(targetItemName, formattedValue);
                    bindItem.after(hidden);
                }
            });
        })
    }

    function configureDefaultBlock(widget) {
        const defaultBlock = widget.find(`[${ATTRIBUTES.BLOCK.ATTR}="default"]`);
        BLOCK_HANDLERS.default(widget, defaultBlock);
    }

    $.fn.registerWidget = function () {
        const widget = $(this);

        configureDefaultBlock(widget);

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