/**
 * @package     hubzero-cms
 * @file        modules/mod_reportproblems/mod_reportproblems.js
 * @copyright   Copyright 2005-2011 Purdue University. All rights reserved.
 * @license     http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

//----------------------------------------------------------
// Establish the namespace if it doesn't exist
//----------------------------------------------------------
if (!HUB) {
	var HUB = {
		Modules: {}
	};
} else if (!HUB.Modules) {
	HUB.Modules = {};
}

//----------------------------------------------------------
// Trouble Report form
//----------------------------------------------------------
HUB.Modules.ReportProblems = {

	jQuery: $,

	settings: { 
		toggle:  '#tab',
		pane:    '#help-pane',
		form:    '#troublereport',
		fields:  {
			name:    '#trName',
			email:   '#trEmail',
			login:   '#trLogin',
			problem: '#trProblem',
			captcha: '#trAnswer',
			upload:  '#trUpload'
		},
		loader:  '#trSending',
		success: '#trSuccess',
		send:    '#send-form'
	},

	initialize: function() {
		var ticket = this,
			$ = this.jQuery,
			settings = this.settings;

		if (!$(settings.pane)) {
			return;
		}

		$('<a href="#" id="help-btn-close" alt="Close">Close</a>').bind('mousedown', function (e) {
			e.preventDefault();
			if ($(settings.toggle).hasClass('active')) {
				$(settings.toggle).removeClass('active');
			} else {
				$(settings.toggle).addClass('active');
			}
			$(settings.pane).slideToggle();
			return false;
		}).appendTo($(settings.pane));

		if ($(settings.toggle)) {
			$(settings.toggle).click(function (e) {
				e.preventDefault();
				if ($(this).hasClass('active')) {
					$(this).removeClass('active');
				} else {
					$(this).addClass('active');
				}
				$(settings.pane).slideToggle();
				return false;
			});
			
			if ($(settings.form)) {
				$('<p>Sending report ...</p>').appendTo($(settings.loader));
				
				$('<iframe src="about:blank" id="upload_target" name="upload_target" style="width:0px;height:0px;border:0px solid #fff;"></iframe>').appendTo($(settings.pane));

				$(settings.form).attr('target', 'upload_target');
				
				$(settings.form).submit(function () {
					return ticket.validateFields();
				});
			}
		}
	},

	hideTimer: function() {
		var ticket = this,
            $ = this.jQuery,
            settings = this.settings;

		$(settings.loader).hide();
		$(settings.success).html(document.getElementById('upload_target').contentWindow.document.getElementById('report-response').innerHTML);
		$(settings.success).show();
	},

	resetForm: function() {
		var ticket = this,
            $ = this.jQuery,
            settings = this.settings;

		$(settings.fields.problem).val('');
		$(settings.fields.upload).parent().html($(settings.fields.upload).parent().html());
		$(settings.success).hide();
		$(settings.form).show();
	},

	reshowForm: function() {
		var ticket = this,
            $ = this.jQuery,
            settings = this.settings;

		$(settings.success).hide();
		$(settings.form).show();
	},

	/*sendReport: function() {
		var ticket = this,
            $ = this.jQuery,
            settings = this.settings;

		var h = $(settings.form).height();
		$(settings.form).hide();
		$(settings.loader).show();
		$(settings.loader).height(h);
		$(settings.form).submit();
	},*/

	validateFields: function() {
		var ticket = this,
            $ = this.jQuery,
            settings = this.settings,
			whiteSpace = /^[\s]+$/;

		if ($(settings.fields.problem).val() == '' || whiteSpace.test($(settings.fields.problem).val()) ) {
			alert("You're trying to send an empty trouble report. Please type something and try again.");
			$(settings.fields.problem).focus();
			return false;
		}
		
		if ($(settings.fields.name).val() == '' || whiteSpace.test($(settings.fields.name).val()) ) {
			alert("The 'name' field is required. Please type something and try again.");
			$(settings.fields.name).focus();
			return false;
		}
		
		if ($(settings.fields.email).val() == '' || ticket.validateEmail($(settings.fields.email).val()) === false) {
			alert("Please provide a valid email address.");
			$(settings.fields.email).focus();
			return false;
		}
		
		//return ticket.sendReport();
		var h = $(settings.form).height();
		$(settings.form).hide();
		$(settings.loader).show();
		$(settings.loader).height(h);
		return true;
	},

	validateEmail: function(emailStr) {
		var emailReg1 = /(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/, // not valid
			emailReg2 = /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,6}|[0-9]{1,3})(\]?)$/; // valid
		if (!(!emailReg1.test(emailStr) && emailReg2.test(emailStr))) {
			return false;
		}
		return true;
	}
		
};

jQuery(document).ready(function($){
	HUB.Modules.ReportProblems.initialize();
});