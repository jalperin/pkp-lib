/**
 * @defgroup js_controllers_wizard_uniqueAuthor
 */
// Create the uniqueAuthor namespace
jQuery.pkp.controllers.wizard.uniqueAuthor =
			jQuery.pkp.controllers.wizard.uniqueAuthor || { };

/**
 * @file js/controllers/wizard/uniqueAuthor/UniqueAuthorWizardHandler.js
 *
 * Copyright (c) 2000-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UniqueAuthorWizardHandler
 * @ingroup js_controllers_wizard_uniqueAuthor
 *
 * @brief Special instance of the wizard handler.
 */
(function($) {


	/**
	 * @constructor
	 *
	 * @extends $.pkp.controllers.WizardHandler
	 *
	 * @param {jQuery} $wizard A wrapped HTML element that
	 *  represents the wizard.
	 * @param {Object} options Wizard options.
	 */
	$.pkp.controllers.wizard.uniqueAuthor.UniqueAuthorWizardHandler = function($wizard, options) {
		this.parent($wizard, options);

        this.bind('uniqueAuthorSelected', this.uniqueAuthorSelected);

        // Assign the url options.
        this.editAuthorUrl_ = options.editAuthorUrl;
        this.uniqueAuthorGridUrl_ = options.uniqueAuthorGridUrl;
        this.potentialAuthorGridUrl_ = options.potentialAuthorGridUrl;
	};
	$.pkp.classes.Helper.inherits(
			$.pkp.controllers.wizard.uniqueAuthor.UniqueAuthorWizardHandler,
            $.pkp.controllers.wizard.WizardHandler);


    //
    // Private properties
    //
	/**
	 * The url for the second tab of the wizard.
	 * @private
	 * @type {?string}
	 */
	$.pkp.controllers.wizard.uniqueAuthor.UniqueAuthorWizardHandler.prototype.editAuthorUrl_ = null;

	/**
	 * The url to the unique author grid.
	 * @private
	 * @type {?string}
	 */
	$.pkp.controllers.wizard.uniqueAuthor.UniqueAuthorWizardHandler.prototype.uniqueAuthorGridUrl_ = null;


	/**
	 * The url to the potential author grid.
	 * @private
	 * @type {?string}
	 */
	$.pkp.controllers.wizard.uniqueAuthor.UniqueAuthorWizardHandler.prototype.potentialAuthorGridUrl_ = null;

   	/**
	 * The unique author id
	 * @private
	 * @type {?int}
	 */
	$.pkp.controllers.wizard.uniqueAuthor.UniqueAuthorWizardHandler.prototype.uniqueAuthorId_ = null;

    //
    // Private methods
	//
	/**
	 * Return the current form (if any).
	 *
	 * @private
	 * @return {?jQuery} The form (if any).
	 */
	$.pkp.controllers.wizard.uniqueAuthor.UniqueAuthorWizardHandler.prototype.getForm_ = function() {
        var currentStep = this.getCurrentStep();
        if ( currentStep === 0 ) {
            // Find the form that holds the grids.
            var $selectUniqueAuthorForm = $('#selectUniqueAuthorForm');
            return $selectUniqueAuthorForm;
        } else {
            return this.parent('getForm_');
        }
	};

	/**
	 * Handle "form submitted" events that may be triggered by forms in the
	 * wizard tab.
	 *
	 * @param {HTMLElement} tabElement The tab that contains the form that triggered the event.
	 * @param {Event} event The triggered event.
	 */
	$.pkp.controllers.wizard.uniqueAuthor.UniqueAuthorWizardHandler.prototype.formSubmitted =
			function(tabElement, event) {
        var currentStep = this.getCurrentStep();

        // Do special handling of the first tab.
        if ( currentStep == 0 ) {
            // Figure out which of the two tabs was submitted.
            switch (event.target.id) {
                case 'uniqueAuthorSearchFormContainer':
                    // FIXME: add a throbber with loading in these two divs.
                    // var $uniqueAuthorGrid = $('#uniqueAuthorGridContainer');
                    // var $potentialAuthorGrid = $('#potentialAuthorGridContainer');

                    var searchTerm = this.getHtmlElement().find(':input[type=text]')[0].value;
                    $.get(this.uniqueAuthorGridUrl_, {searchTerm: searchTerm, grid: 'uniqueAuthor'},
                            this.callbackWrapper(this.refreshGrids_), 'json');
                    $.get(this.potentialAuthorGridUrl_, {searchTerm: searchTerm, grid: 'potentialAuthor'},
                            this.callbackWrapper(this.refreshGrids_), 'json');
                    break;
            }
        } else {
            // Fallback on the default implementation
            this.parent('formSubmitted', tabElement, event);
        }
	};

	/**
	 * Handle "unique author selected" events that may be triggered by forms in the
	 * wizard tab.
	 *
	 * @param {HTMLElement} tabElement The tab that contains the form that triggered the event.
	 * @param {Event} event The triggered event.
     * @param {int} data The unique author information.
	 */
	$.pkp.controllers.wizard.uniqueAuthor.UniqueAuthorWizardHandler.prototype.uniqueAuthorSelected =
			function(tabElement, event, data) {

        this.uniqueAuthorId_ = data;
        this.advanceOrClose_();
    }

	/**
	 * @inheritDoc
	 */
	$.pkp.controllers.wizard.uniqueAuthor.UniqueAuthorWizardHandler.
			prototype.tabsSelect = function(tabsElement, event, ui) {

		// The last two tabs require a file to be uploaded.
		if (ui.index === 1) {
			var $wizard = this.getHtmlElement(), newUrl = '';
			newUrl = this.editAuthorUrl_ + '&uniqueAuthorId=' + this.uniqueAuthorId_;
			$wizard.tabs('url', ui.index, newUrl);
		}

		return this.parent('tabsSelect', tabsElement, event, ui);
	};

	/**
	 * Refresh the grid after its filter has changed.
	 *
	 * @private
	 *
	 * @param {Object} ajaxContext The context that the calling request was made with.
	 * @param {JSON} jsonData Json with contents.
	 */
	$.pkp.controllers.wizard.uniqueAuthor.UniqueAuthorWizardHandler.prototype.refreshGrids_ =
            function(ajaxContext, jsonData) {

        jsonData = this.handleJson(jsonData);
        if (jsonData !== false) {
            // get the url parameter that tells us which grid.
            // do not need to bother decoding, since we have a closed set
            // and are using the switch to ensure no funny stuff.
            var gridName = RegExp('&?grid=([^&]*)').exec(ajaxContext.data)[1];
            switch ( gridName ) {
                case 'uniqueAuthor':
                    // Get the grid that we're updating
                    var $grid = $('#uniqueAuthorGridContainer');

                    // Replace the grid content
                    $grid.html(jsonData.content).fadeIn(400);
                    break;
                case 'potentialAuthor':
                    // Get the grid that we're updating
                    var $grid = $('#potentialAuthorGridContainer');

                    // Replace the grid content
                    $grid.html(jsonData.content).fadeIn(400);
                    break;
            }
        }
	};

/** @param {jQuery} $ jQuery closure. */
})(jQuery);
