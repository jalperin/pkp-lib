/**
 * @file js/controllers/form/GridFilterAjaxFormHandler.js
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
	$.pkp.controllers.form.GridFilterAjaxFormHandler = function($form, options) {
		this.parent($form, options);
	};
	$.pkp.classes.Helper.inherits(
			$.pkp.controllers.form.GridFilterAjaxFormHandler,
			$.pkp.controllers.form.AjaxFormHandler);


	//
	// Public methods
	//
	/**
	 * Internal callback called after form validation to handle form
	 * submission.
	 *
	 * @param {Object} validator The validator plug-in.
	 * @param {HTMLElement} formElement The wrapped HTML form.
	 */
	$.pkp.controllers.form.GridFilterAjaxFormHandler.prototype.submitForm =
			function(validator, formElement) {

        // This form implementation will trigger an event
        // with the form json and the form data.
        var $form = this.getHtmlElement();

        // Retrieve form data.
        var formData = $form.serializeArray();

        // Inform the server that the form has been submitted.
        formData.push({name:'clientSubmit', value:true});

        // Trigger the "form submitted" event.
        this.trigger('formSubmitted', [$.param(formData)]);
	};
/** @param {jQuery} $ jQuery closure. */
})(jQuery);
