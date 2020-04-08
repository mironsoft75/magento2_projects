define(['jquery', 'jquery/ui'], function ($) {
    var widget = {
        options: {
            config: {
                serialize: {
                    strategy: 0 // 0 - Key wise. 1 - Row wise
                },
                tableClass: 'data-table',
                addButtonLabel: 'Add',
                addButtonClass: 'action-default scalable',
                deleteButtonLabel: 'Delete',
                deleteButtonClass: 'action-default scalable action-delete'
            },
            definition: {},
            values: [],
            onChange: null
        },
        currentRowNumber: 0,
        container: null,
        definition: null,
        formTable: null,
        previousValues: null,
        _create: function () {
            this.init(this.element, this.options.definition, this.options.values);
        },
        init: function (container, definition, values) {
            this.container = container;
            this.definition = definition;
            this.previousValues = values || [];
            this.makeForm();
        },
        makeForm: function () {
            var self = this;
            self.formTable = $('<table class="' + this.options.config.tableClass +'"></table>');
            var header = $('<tr></tr>');
            for (var i in this.definition) {
                header.append($('<th style="padding: 10px">' + self.definition[i].label + '</th>'));
            }
            header.append($('<th style="padding: 10px">Actions</th>'));
            var addRowbutton = $('<button type="button" class="' + this.options.config.addButtonClass + '"><span>' + this.options.config.addButtonLabel + '</span></button>');
            addRowbutton.on('click', function () {
                this.addRow();
                this.formTable.trigger('change');
            }.bind(this));
            self.formTable.append($('<thead></thead>').append(header)).append('<tbody></tbody>');
            self.container.append(addRowbutton);
            self.container.append(this.formTable);
            self.formTable.find('tbody').sortable({
                stop: function (e, ui) {
                    self.formTable.trigger('change');
                }
            });
            this.populatePreviousValues();
            if (typeof this.options.onChange === "function") {
                this.formTable.on('change', function () {
                    this.options.onChange(this.serialize());
                }.bind(this));
                this.formTable.trigger('change');
            }
        },
        serialize: function () {
            var serializedForm = this.formTable.find('select, textarea, input').serializeArray();
            var strategy = this.options.config.serialize.strategy;
            var data = {};
            Object.keys(this.options.definition).forEach(function (key) {
                if (!data[key]) {
                    data[key] = [];
                }
                serializedForm = serializedForm.filter(function (field) {
                    if (field.name === key + '[]') {
                        data[key].push(field.value);
                        return false;
                    }
                    return true;
                });
            });
            if (strategy === 1) {
                var rows = [];
                var keys = Object.keys(data);
                for (var i = 0; i < data[keys[0]].length; i++) {
                    var row = {};
                    keys.forEach(function (key) {
                        row[key] = data[key][i];
                    });
                    rows.push(row);
                }
                data = rows;
            }
            return data;
        },
        populatePreviousValues: function () {
            this.previousValues.forEach(function (row) {
                this.addRow(row);
            }.bind(this));
        },
        addRow: function (data) {
            data = data || {};
            this.formTable.find('tbody').append(this.prepareRowFromDefinition(data));
        },
        prepareRowFromDefinition: function (data) {
            var row = $('<tr></tr>');
            for (var i in this.definition) {
                var cell = $('<td></td>');
                switch (this.definition[i].type) {
                    case 'select':
                        var field = $('<select></select>');
                        this.definition[i].options.forEach(function (option) {
                            field.append($('<option value="' + option.value + '">' + option.label + '</option>'));
                        });
                        break;
                    default:
                        var field = $('<input type="text" />');
                }
                field.attr('id', i + '_' + new Date().getTime());
                field.attr('name', i + '[]');
                if (data[i]) {
                    field.val(data[i]);
                }
                cell.append(field);
                row.append(cell);
            }
            var actionsCell = $('<td></td>');
            var deleteButton = $('<button type="button" class="' + this.options.config.deleteButtonClass + '"><span>' + this.options.config.deleteButtonLabel + '</span></button>');
            actionsCell.append(deleteButton);
            row.append(actionsCell);
            deleteButton.on('click', function () {
                row.remove();
                this.formTable.trigger('change');
            }.bind(this));
            this.currentRowNumber++;
            return row;
        }
    };
    return $.widget('codilar.dynamicForm', widget);
});