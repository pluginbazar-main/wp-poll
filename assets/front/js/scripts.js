/**
 * Front Script
 */

(function ($, window, document, pluginObject) {
    "use strict";

    $(document).on('ready', function () {

        let optionSingle = $('.liquidpoll-option-single');

        optionSingle.on('click', function () {
            optionSingle.removeClass('active');
            $(this).addClass('active');
        });

        $('.theme-6, .theme-7').on('click', function () {
            $(this).find('.liquidpoll-option-single input[name="submit_poll_option"]').not(':checked').prop("checked", true);
        });
    });

    $(document).on('click', '.liquidpoll-get-poll-results', function () {

        let resultButton = $(this),
            pollID = resultButton.data('poll-id'),
            marginLeft = 60;

        if (typeof pollID === 'undefined') {
            return;
        }

        let singlePoll = $('#poll-' + pollID);

        singlePoll.find('.liquidpoll-responses').slideUp();

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'liquidpoll_get_poll_results',
                'poll_id': pollID,
            },
            success: function (response) {

                if (!response.success) {
                    singlePoll.find('.liquidpoll-responses').addClass('liquidpoll-error').find('span.message').html(response.data).parent().slideDown();
                    return;
                }

                singlePoll.addClass('rendered-results').find('.liquidpoll-options .liquidpoll-option-single').each(function () {

                    let optionID = $(this).data('option-id'),
                        percentageValue = response.data.percentages[optionID],
                        singleVoteCount = response.data.singles[optionID], optionResultsText = '';

                    if (typeof percentageValue === 'undefined') {
                        percentageValue = 0;
                    }

                    if (typeof singleVoteCount === 'undefined' || singleVoteCount.length === 0) {
                        singleVoteCount = 0;
                    }

                    optionResultsText = singleVoteCount + ' ' + pluginObject.voteText;
                    if (singlePoll.hasClass('results-type-percentage')) {
                        optionResultsText = percentageValue + '%';
                    }

                    if ($.inArray(optionID, response.data.percentages)) {
                        $(this).addClass('has-result').find('.liquidpoll-option-result-bar').css('width', percentageValue + '%');
                        // $(this).append('<span class="percentage-bar" style="width: ' + percentageValue + '% ;"></span>');
                        $(this).find('.percentage-bar').css('width', percentageValue + '%');

                        let pollSIngle = $(this).parent().parent();

                        if (
                            pollSIngle.hasClass('theme-4') ||
                            pollSIngle.hasClass('theme-6') ||
                            pollSIngle.hasClass('theme-7')
                        ) {
                            let progressBar = $(this).addClass('has-result').find('.liquidpoll-votes-count'),
                                radius = progressBar.find('circle.complete').attr('r'),
                                circumference = 2 * Math.PI * radius,
                                strokeDashOffset = circumference - ((percentageValue * circumference) / 100);

                            progressBar.find('.percentage').html(percentageValue + '%');
                            progressBar.find('circle.complete').removeAttr('style');
                            progressBar.find('circle.complete').animate({'stroke-dashoffset': strokeDashOffset}, 1250);
                        } else {
                            $(this).addClass('has-result').find('.liquidpoll-votes-count').html(optionResultsText);
                        }

                        if (percentageValue === 0) {
                            marginLeft = 0;
                        }

                        $(this).find('.liquidpoll-option-result').html(optionResultsText).css('left', 'calc(' + percentageValue + '% - ' + marginLeft + 'px)');
                    }
                });

                resultButton.parent().fadeOut('slow', 'linear');
            }
        });
    });


    $(document).on('click', '.liquidpoll-submit-poll', function () {

        let pollID = $(this).data('poll-id');

        if (typeof pollID === 'undefined') {
            return;
        }

        let singlePoll = $('#poll-' + pollID), checkedData = [];

        singlePoll.find('.liquidpoll-options .liquidpoll-option-single').each(function () {
            if ($(this).hasClass('active')) {
                checkedData.push($(this).find('input[name="submit_poll_option"]').attr('value'));
            }
        });

        singlePoll.find('.liquidpoll-responses').slideUp();

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'liquidpoll_submit_poll',
                'poll_id': pollID,
                'checked_data': checkedData,
            },
            success: function (response) {
                if (!response.success) {
                    singlePoll.find('.liquidpoll-responses').addClass('liquidpoll-error').find('span.message').html(response.data).parent().slideDown();
                } else {
                    /**
                     * Trigger to enhance on Success of Poll Submission
                     *
                     * @trigger liquidpoll_poll_submission_success
                     */
                    $(document.body).trigger('liquidpoll_poll_submission_success', response);

                    singlePoll.find('.liquidpoll-responses').addClass('liquidpoll-success').find('span.message').html(response.data).parent().slideDown();
                }
            }
        });
    });


    $(document).on('click', 'p.liquidpoll-responses .close', function () {
        $(this).parent().slideUp();
    });

    // Round Slider js
    $(document).ready(function () {
        $.fn.roundSlider.prototype._invertRange = true;
        var roundHandle = $('#handle1');

        // this is core functionality to generate the numbers
        $.fn.roundSlider.prototype.defaults.create = function () {
            var o = this.options, tickInterval = 1;

            for (var i = o.min; i <= o.max; i += tickInterval) {
                var angle = this._valueToAngle(i);
                var numberTag = this._addSeperator(angle, "rs-custom");
                var number = numberTag.children();
                number.clone().css({
                    "width": o.width + this._border(),
                    "margin-top": this._border(true) / -2
                });
                number.removeClass().addClass("rs-number").html(i).rsRotate(-angle);
            }
        }
        roundHandle.roundSlider({
            sliderType: "min-range",
            editableTooltip: false,
            showTooltip: false,
            radius: 300,
            width: 30,
            value: 7,
            handleShape: "square",
            handleSize: 20,
            circleShape: "half-top",
            startAngle: 0,
            min: 0,
            max: 10,
            step: 1,
            change: "onValueChange",
        });
        roundHandle.on("change", function (e) {
            var colors = ['#6265ea', '#6866e8', '#6766e9', '#6d68e8', '#7369e6', '#7a6be4', '#7f6be2', '#866de1', '#876ddf', '#8a6ddf', '#8b6edf'];
            document.documentElement.style.setProperty('--bgcolor', colors[e.value]);
        });

        //Range Slider
        var $r = $('input[type="range"]');
        var $ruler = $('<div class="rangeslider__ruler" />');

        // Initialize
        $r.rangeslider({
            polyfill: false,
            onInit: function () {
                $ruler[0].innerHTML = getRulerRange(this.min, this.max, this.step);
                this.$range.prepend($ruler);
            }
        });

        function getRulerRange(min, max, step) {
            var range = '';
            var i = 0;

            while (i <= max) {
                range += i + ' ';
                i = i + step;
            }
            return range;
        }

    });

    $(document).on('click', '.liquidpoll-new-option > button', function () {

        let popupBoxContainer = $(this).parent().parent().parent(),
            pollID = $(this).data('pollid'),
            optionField = $(this).parent().find('input[type="text"]'),
            optionValue = optionField.val();

        if (typeof pollID === "undefined" || pollID.length === 0 ||
            typeof optionValue === "undefined" || optionValue.length === 0) {

            $(this).parent().find('span').fadeIn(100);
            return;
        }

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'liquidpoll_front_new_option',
                'poll_id': pollID,
                'opt_val': optionValue,
            },
            success: function (response) {

                if (response.success) {

                    popupBoxContainer.parent().find('.liquidpoll-options').append(response.data);
                    popupBoxContainer.fadeOut().find('input[type="text"]').val('');
                }
            }
        });

    });


    $(document).on('keyup', '.liquidpoll-new-option input[type="text"]', function (e) {
        if (e.which === 13) {
            $(this).parent().find('.liquidpoll-button').trigger('click');
        }

        if ($(this).val().length > 0) {
            $(this).parent().find('span').hide();
        }
    });


    $(document).on('click', '.liquidpoll-button-new-option', function () {
        $(this).parent().parent().find('.liquidpoll-popup-container').fadeIn().find('input[type="text"]').focus();
    });


    $(document).on('click', '.liquidpoll-popup-container .box-close', function () {
        $(this).parent().parent().fadeOut();
    });


    $(document).on('click', '.liquidpoll-options .liquidpoll-option-single', function (e) {

        let outsideInputArea = $(this).find('.liquidpoll-option-input');

        if (!outsideInputArea.is(e.target) && outsideInputArea.has(e.target).length === 0) {
            $(this).find('label').trigger('click');
        }
    });


    $(document).on('change', '.nps-single input[name="nps_score"]', function () {

        let npsSelectionField = $(this),
            npsSelectionFieldVal = npsSelectionField.val(),
            npsSelectionLI = npsSelectionField.parent(),
            npsSelectionUL = npsSelectionLI.parent(),
            npsSingle = npsSelectionUL.parent().parent(),
            npsCommentBox = npsSingle.find('.liquidpoll-comment-box'),
            npsSubmitButton = npsSingle.find('.nps-button-wrap > .liquidpoll-submit-poll');

        npsSelectionUL.find('> li').removeClass('active');
        npsSelectionLI.toggleClass('active');

        if (npsSelectionFieldVal.length > 0) {
            npsCommentBox.fadeIn('300', function () {
                npsSubmitButton.fadeIn('300');
            });
        }
    });

    $(document).on('click', '.nps-single ul.liquidpoll-nps-options li', function (e) {

        // let outsideInputArea = $(this).find('.liquidpoll-option-input');
        //
        // if (!outsideInputArea.is(e.target) && outsideInputArea.has(e.target).length === 0) {
        //     $(this).find('label').trigger('click');
        // }
    });


})(jQuery, window, document, liquidpoll_object);







