/*!
 * remark (http://getbootstrapadmin.com/remark)
 * Copyright 2016 amazingsurge
 * Licensed under the Themeforest Standard Licenses
 */
(function (document, window, $) {
    'use strict';

    var Site = window.Site;

    $(document).ready(function ($) {
        Site.run();
    });

    // Example Wizard Form
    // -------------------
    // Example Wizard Form Container
    // -----------------------------
    // http://formvalidation.io/api/#is-valid-container
    var defaults = $.components.getDefaults("wizard");
    var options = $.extend(true, {}, defaults, {
        buttonsAppendTo: '.panel-body'
    });

    var wizard = $("#exampleWizardForm").wizard(options).data('wizard');
    (function () {
        var defaults = $.components.getDefaults("wizard");
        var options = $.extend(true, {}, defaults, {

            onFinish: function () {
                // $('#exampleFormContainer').submit();
            },
            buttonsAppendTo: '.panel-body'
        });

        $("#exampleWizardFormContainer").wizard(options);
    })();

    // Example Wizard Pager
    // --------------------------
    (function () {
        var defaults = $.components.getDefaults("wizard");

        var options = $.extend(true, {}, defaults, {
            step: '.wizard-pane',
            templates: {
                buttons: function () {
                    var options = this.options;
                    var html = '<div class="btn-group btn-group-sm btn-group-flat">' +
                            '<a class="btn btn-default" href="#' + this.id + '" data-wizard="back" role="button">' + options.buttonLabels.back + '</a>' +
                            '<a class="btn btn-success pull-right" href="#' + this.id + '" data-wizard="finish" role="button">' + options.buttonLabels.finish + '</a>' +
                            '<a class="btn btn-default pull-right" href="#' + this.id + '" data-wizard="next" role="button">' + options.buttonLabels.next + '</a>' +
                            '</div>';
                    return html;
                }
            },
            buttonLabels: {
                next: '<i class="icon md-chevron-right" aria-hidden="true"></i>',
                back: '<i class="icon md-chevron-left" aria-hidden="true"></i>',
                finish: '<i class="icon md-check" aria-hidden="true"></i>'
            },

            buttonsAppendTo: '.panel-actions'
        });

        $("#exampleWizardPager").wizard(options);
    })();

    // Example Wizard Progressbar
    // --------------------------
    (function () {
        var defaults = $.components.getDefaults("wizard");

        var options = $.extend(true, {}, defaults, {
            step: '.wizard-pane',
            onInit: function () {
                this.$progressbar = this.$element.find('.progress-bar').addClass('progress-bar-striped');
            },
            onBeforeShow: function (step) {
                step.$element.tab('show');
            },
            onFinish: function () {
                this.$progressbar.removeClass('progress-bar-striped').addClass('progress-bar-success');
            },
            onAfterChange: function (prev, step) {
                var total = this.length();
                var current = step.index + 1;
                var percent = (current / total) * 100;

                this.$progressbar.css({
                    width: percent + '%'
                }).find('.sr-only').text(current + '/' + total);
            },
            buttonsAppendTo: '.panel-body'
        });

        $("#exampleWizardProgressbar").wizard(options);
    })();

    // Example Wizard Tabs
    // -------------------
    (function () {
        var defaults = $.components.getDefaults("wizard");
        var options = $.extend(true, {}, defaults, {
            step: '> .nav > li > a',
            onBeforeShow: function (step) {
                step.$element.tab('show');
            },
            classes: {
                step: {
                    //done: 'color-done',
                    error: 'color-error'
                }
            },
            onFinish: function () {
                alert('finish');
            },
            buttonsAppendTo: '.tab-content'
        });

        $("#exampleWizardTabs").wizard(options);
    })();

    // Example Wizard Accordion
    // ------------------------
    (function () {
        var defaults = $.components.getDefaults("wizard");
        var options = $.extend(true, {}, defaults, {
            step: '.panel-title[data-toggle="collapse"]',
            classes: {
                step: {
                    //done: 'color-done',
                    error: 'color-error'
                }
            },
            templates: {
                buttons: function () {
                    return '<div class="panel-footer">' + defaults.templates.buttons.call(this) + '</div>';
                }
            },
            onBeforeShow: function (step) {
                step.$pane.collapse('show');
            },

            onBeforeHide: function (step) {
                step.$pane.collapse('hide');
            },

            onFinish: function () {
                alert('finish');
            },

            buttonsAppendTo: '.panel-collapse'
        });

        $("#exampleWizardAccordion").wizard(options);
    })();

})(document, window, jQuery);
