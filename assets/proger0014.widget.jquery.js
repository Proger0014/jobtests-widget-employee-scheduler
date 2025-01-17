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

    function _typeHandlers() {
        function switchFn() {
            const valueFormatter = (valueRaw) => {
                return valueRaw ? '1' : '0';
            }

            return {
                valueFormatter: valueFormatter,
                domManipulator: (widget, item) => {
                    item.attr('form', null);
                    const switchInputName = item.attr(ATTRIBUTES.INPUT.NAME);
                    const existsHiddenValue = widget.find(`input[name="${switchInputName}"]`);

                    const switchVal = valueFormatter(item.prop('checked'));

                    if (existsHiddenValue.length > 0) {
                        existsHiddenValue.attr('value', switchVal);
                    } else {
                        const hidden = createHidden(switchInputName, switchVal);
                        item.after(hidden);
                    }
                },
                allowBinds: (widget, item) => true
            };
        }

        return {
            switch: switchFn(),
            time: {
                valueFormatter: (valueRaw) => {
                    return `${valueRaw}:00`
                },
                domManipulator: (widget, item) => {
                    item.attr('form', null);
                },
                reset: (widget, item) => {
                    item.val('00:00');
                },
                allowBinds: (widget, item) => true
            },
            'switch-day-of-week': {
                valueFormatter: (valueRaw) => {
                    return valueRaw;
                },
                domManipulator: (widget, item) => {
                    item.attr('form', null);
                },
                reset: (widget, item) => {
                    item.prop('checked', false);
                },
                allowBinds: (widget, item) => {
                    return item.prop('checked');
                }
            }
        };
    }

    const ATTRIBUTES = _attribues();
    const TYPE_HANDLERS = _typeHandlers();

    const BLOCK_HANDLERS = {
        default: (widget, block) => {
            const inputContainers = block.find(`[${ATTRIBUTES.INPUT.CONTAINER}]`);

            const buttons = block.find(`button[${ATTRIBUTES.BLOCK.BUTTON}]`);
            buttons.each((i, elem) => {
                const button = $(elem);

                const buttonType = button.attr(ATTRIBUTES.BLOCK.BUTTON);

                switch (buttonType) {
                    case 'edit':
                        configureEditButton(buttons, button, inputContainers);
                        break;
                    case 'delete':
                        configureRemoveButton(button, block);
                        break;
                    case 'reset':
                        configureResetButton(button, inputContainers);
                        break;
                    case 'apply':
                        configureApplyButton(buttons, button, inputContainers);
                        break;
                }
            })
        }
    };

    function configureRemoveButton(button, target) {
        button.on('click', () => {
            target.remove();
        });
    }

    function configureEditButton(buttons, button, target) {
        button.on('click', () => {
            toggleButtonTypes(buttons);
            target.each((i, item) => {
                disableInputToggle($(item));
            });
        })
    }

    function configureResetButton(button, target) {
        button.on('click', () => {
            target.each((i, item) => {
                const itemJq = $(item);
                const input = itemJq.find('input');
                TYPE_HANDLERS[input.attr(ATTRIBUTES.INPUT.TYPE)].reset(null, input);
            });
        });
    }

    function toggleButtonTypes(buttons) {
        buttons.each((i, elem) => {
            const button = $(elem);
            if (button.hasClass('none')) {
                button.removeClass('none');
            } else {
                button.addClass('none');
            }
        });
    }

    function configureApplyButton(buttons, button, target) {
        button.on('click', () => {
            target.each((i, item) => {
                disableInputToggle($(item));
            });
            toggleButtonTypes(buttons);
        });
    }

    function disableInputToggle(inputContainer) {
        inputContainer.toggleClass('disabled');
        inputContainer.find('input').get(0).toggleAttribute('disabled');
    }

    function createHidden(name, value) {
        return $(`<input>`)
            .attr('hidden', '')
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
            const type = bindItem.attr(ATTRIBUTES.INPUT.TYPE);

            if (!TYPE_HANDLERS[type].allowBinds(widget, bindItem)) return;

            const targetItemName = bindItem.attr(ATTRIBUTES.INPUT.BINDS);

            const bindsAttrArr = targetItemName.split(' ');
            bindsAttrArr.forEach((bind) => {
                const bindItemTargetName = bind.substring(0, bind.indexOf('('));
                const bindItemName = bind.substring(bind.indexOf('(') + 1, bind.indexOf(')'));
                const bindItemTarget = widget.find(`input[${ATTRIBUTES.INPUT.NAME}="${bindItemTargetName}"]`);
                const bindItemTargetValue = bindItemTarget.prop('value');
                const bindItemTargetType = bindItemTarget.attr(ATTRIBUTES.INPUT.TYPE);
                const formattedValue = TYPE_HANDLERS[bindItemTargetType]
                    .valueFormatter(bindItemTargetValue);

                const existsHiddenValue = widget.find(`input[name="${bindItemName}"]`);

                if (existsHiddenValue.length > 0) {
                    existsHiddenValue.prop('value', formattedValue);
                } else {
                    const hidden = createHidden(bindItemName, formattedValue);
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

            if (widgetTarget !== 'form#' + $(event.target).attr('id')) {
                return;
            }

            prepareInputs(widget);
            prepareBinds(widget);
        })
    };
}(jQuery));