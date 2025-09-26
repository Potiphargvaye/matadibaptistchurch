/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/setup-wizard.js":
/*!********************************!*\
  !*** ./src/js/setup-wizard.js ***!
  \********************************/
/***/ (() => {

eval("(function ($) {\n  \"use strict\";\n\n  $(document).on('change', '.better_payment_preferences', function (e) {\n    var $this = $(this),\n      preferences = $this.val();\n    var elements = $(\".better-payment-elements-container .better-payment-elements-info input[type=checkbox]\");\n    if (elements.length > 0) {\n      if (preferences == 'custom') {\n        elements.prop('checked', true);\n      } else {\n        elements.prop('checked', false);\n        elements.each(function (i, item) {\n          if (preferences == 'advance' && $(item).data('preferences') != '') {\n            $(item).prop('checked', true);\n          } else if ($(item).data('preferences') == preferences) {\n            $(item).prop('checked', true);\n          }\n        });\n      }\n    }\n  });\n  betterPaymentRenderTab();\n  function betterPaymentRenderTab(step = 0) {\n    var contents = document.getElementsByClassName(\"setup-content\"),\n      prev = document.getElementById(\"better-payment-prev\"),\n      nextElement = document.getElementById(\"better-payment-next\"),\n      saveElement = document.getElementById(\"better-payment-save\");\n    if (contents.length < 1) {\n      return;\n    }\n    contents[step].style.display = \"block\";\n    prev.style.display = step == 0 ? \"none\" : \"inline\";\n    if (step == contents.length - 1) {\n      saveElement.style.display = \"inline\";\n      nextElement.style.display = \"none\";\n    } else {\n      nextElement.style.display = \"inline\";\n      saveElement.style.display = \"none\";\n    }\n    betterPaymentStepIndicator(step);\n  }\n  function betterPaymentStepIndicator(stepNumber) {\n    var steps = document.getElementsByClassName(\"step\"),\n      container = document.getElementsByClassName(\"better-payment-setup-wizard\");\n    container[0].setAttribute('data-step', stepNumber);\n    for (var i = 0; i < steps.length; i++) {\n      steps[i].className = steps[i].className.replace(\" active\", \"\");\n    }\n    steps[stepNumber].className += \" active\";\n  }\n  $(document).on('click', '#better-payment-next,#better-payment-prev', function (e) {\n    var container = document.getElementsByClassName(\"better-payment-setup-wizard\"),\n      StepNumber = parseInt(container[0].getAttribute('data-step')),\n      contents = document.getElementsByClassName(\"setup-content\");\n    contents[StepNumber].style.display = \"none\";\n    StepNumber = e.target.id == 'better-payment-prev' ? StepNumber - 1 : StepNumber + 1;\n    if (StepNumber >= contents.length) {\n      return false;\n    }\n    betterPaymentRenderTab(StepNumber);\n  });\n  $('.btn-collect').on('click', function () {\n    $(\".better-payment-whatwecollecttext\").toggle();\n  });\n\n  //Stripe toggle button\n  $(document).on('change', '.quick-setup-stripe input[name=\"better_payment_settings_payment_stripe_live_mode\"]', function (e) {\n    e.preventDefault();\n    let bpAdminSettingsPaymentStripe = $(this).attr('data-targettest');\n    if ($(this).is(':checked')) {\n      bpAdminSettingsPaymentStripe = $(this).attr('data-targetlive');\n    }\n    $('.bp-stripe-key').removeClass('bp-d-block').addClass('bp-d-none');\n    $(`.${bpAdminSettingsPaymentStripe}`).removeClass('bp-d-none').addClass('bp-d-block');\n  });\n\n  //Settings Save\n  $(document).on(\"click\", \".better-payment-setup-wizard-save\", function (e) {\n    e.preventDefault();\n    let bpAdminSettingsForm = $(this).parents(\"#better-payment-admin-settings-form\");\n    bpAdminSettingsSave(this, bpAdminSettingsForm);\n  });\n  function bpAdminSettingsSave(button, form) {\n    let bpAdminSettingsSaveBtn = $(button),\n      nonce = betterPaymentObjWizard.nonce;\n    let formDataWizard = $('#better-payment-admin-settings-form').serializeArray();\n    $.ajax({\n      type: \"POST\",\n      url: ajaxurl,\n      data: {\n        action: \"save_setup_wizard_data\",\n        nonce: nonce,\n        form_data: formDataWizard\n      },\n      beforeSend: function () {\n        bpAdminSettingsSaveBtn.addClass(\"is-loading\");\n      },\n      success: function (response) {\n        bpAdminSettingsSaveBtn.removeClass(\"is-loading\");\n        if (response.success) {\n          Swal.fire({\n            timer: 3000,\n            showConfirmButton: false,\n            imageUrl: betterPaymentObjWizard.success_image\n          }).then(result => {\n            window.location = response.data.redirect_url;\n          });\n        } else {\n          Swal.fire({\n            type: \"error\",\n            title: 'Error',\n            text: 'error'\n          });\n        }\n      }\n    });\n  }\n})(jQuery);\n\n//# sourceURL=webpack://better-payment/./src/js/setup-wizard.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./src/js/setup-wizard.js"]();
/******/ 	
/******/ })()
;