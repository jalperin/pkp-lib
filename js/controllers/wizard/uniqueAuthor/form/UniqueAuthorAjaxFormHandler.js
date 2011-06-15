/**
 * @defgroup js_controllers_wizard_uniqueAuthor_form
 */
// Create the uniqueAuthor namespace
jQuery.pkp.controllers.wizard.uniqueAuthor.form =
			jQuery.pkp.controllers.wizard.uniqueAuthor.form || { };

/**
 * @file js/controllers/uniqueAuthor/form/UniqueAuthorAjaxFormHandler.js
 *
 * Copyright (c) 2000-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class GridFilterAjaxFormHandler
 * @ingroup js_controllers_form
 *
 * @brief Form handler that submits the filter form to the server via AJAX
 *  and then calls on the grid to replace itself by triggering
 *  the "formSubmitted" event.
 */
(function($) {


	/**
	 * @constructor
	 *
	 * @extends $.pkp.controllers.form.AjaxFormHandler
	 *
	 * @param {jQuery} $form the wrapped HTML form element.
	 * @param {Object} options options to be passed
	 *  into the validator plug-in.
	 */
	$.pkp.controllers.wizard.uniqueAuthor.form.UniqueAuthorAjaxFormHandler = function($form, options) {
		this.parent($form, options);
	};
	$.pkp.classes.Helper.inherits(
			$.pkp.controllers.wizard.uniqueAuthor.form.UniqueAuthorAjaxFormHandler,
			$.pkp.controllers.form.AjaxFormHandler);


	//
	// Public methods
	//
	$.pkp.controllers.wizard.uniqueAuthor.form.UniqueAuthorAjaxFormHandler.prototype.handleResponse =
			function(formElement, jsonData) {

		jsonData = this.handleJson(jsonData);
		if (jsonData !== false ) {
            if (jsonData.content === '') {
                this.trigger('formSubmitted');
            } else {
                 // Trigger the "form submitted" event.
                this.trigger('uniqueAuthorSelected', jsonData.content);
            }
		}
		return jsonData.status;
	};
/** @param {jQuery} $ jQuery closure. */
})(jQuery);
