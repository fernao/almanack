/*
 * Localized default methods for the jQuery validation plugin.
 * Localex: PT_BR
 */
jQuery.extend(jQuery.validator.methods, {
	date: function(value, element) {
		return this.optional(element) || /^\d\d?\/\d\d?\/\d\d\d?\d?$/.test(value);
	}
});