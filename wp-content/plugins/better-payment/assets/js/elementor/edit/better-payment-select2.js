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

/***/ "./src/js/elementor/edit/better-payment-select2.js":
/*!*********************************************************!*\
  !*** ./src/js/elementor/edit/better-payment-select2.js ***!
  \*********************************************************/
/***/ (() => {

eval(";\n(function ($) {\n  $(document).on('better_payment_select2_init', function (event, obj) {\n    var ID = '#elementor-control-default-' + obj.data._cid;\n    setTimeout(function () {\n      var IDSelect2 = $(ID).select2({\n        minimumInputLength: 3,\n        placeholder: better_payment_select2_localize.search_text,\n        allowClear: true,\n        ajax: {\n          url: better_payment_select2_localize.ajaxurl + \"?action=better_payment_select2_search_post&post_type=\" + obj.data.source_type + '&source_name=' + obj.data.source_name,\n          dataType: 'json'\n        },\n        initSelection: function (element, callback) {\n          if (!obj.multiple) {\n            callback({\n              id: '',\n              text: better_payment_select2_localize.search_text\n            });\n          } else {\n            callback({\n              id: '',\n              text: ''\n            });\n          }\n          var ids = [];\n          if (!Array.isArray(obj.currentID) && obj.currentID != '') {\n            ids = [obj.currentID];\n          } else if (Array.isArray(obj.currentID)) {\n            ids = obj.currentID.filter(function (el) {\n              return el != null;\n            });\n          }\n          if (ids.length > 0) {\n            var label = $(\"label[for='elementor-control-default-\" + obj.data._cid + \"']\");\n            label.after('<span class=\"elementor-control-spinner\">&nbsp;<i class=\"eicon-spinner eicon-animation-spin\"></i>&nbsp;</span>');\n            $.ajax({\n              method: \"POST\",\n              url: better_payment_select2_localize.ajaxurl + \"?action=better_payment_select2_get_title\",\n              data: {\n                post_type: obj.data.source_type,\n                source_name: obj.data.source_name,\n                id: ids\n              }\n            }).done(function (response) {\n              if (response.success && typeof response.data.results != 'undefined') {\n                let eaelSelect2Options = '';\n                ids.forEach(function (item, index) {\n                  if (typeof response.data.results[item] != 'undefined') {\n                    const key = item;\n                    const value = response.data.results[item];\n                    eaelSelect2Options += `<option selected=\"selected\" value=\"${key}\">${value}</option>`;\n                  }\n                });\n                element.append(eaelSelect2Options);\n              }\n              label.siblings('.elementor-control-spinner').remove();\n            });\n          }\n        }\n      });\n\n      //Manual Sorting : Select2 drag and drop : starts\n      // #ToDo Try to use promise in future\n      setTimeout(function () {\n        IDSelect2.next().children().children().children().sortable({\n          containment: 'parent',\n          stop: function (event, ui) {\n            ui.item.parent().children('[title]').each(function () {\n              var title = $(this).attr('title');\n              var original = $('option:contains(' + title + ')', IDSelect2).first();\n              original.detach();\n              IDSelect2.append(original);\n            });\n            IDSelect2.change();\n          }\n        });\n        $(ID).on(\"select2:select\", function (evt) {\n          var element = evt.params.data.element;\n          var $element = $(element);\n          $element.detach();\n          $(this).append($element);\n          $(this).trigger(\"change\");\n        });\n      }, 200);\n      //Manual Sorting : Select2 drag and drop : ends\n    }, 100);\n  });\n})(jQuery);\n\n//# sourceURL=webpack://better-payment/./src/js/elementor/edit/better-payment-select2.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./src/js/elementor/edit/better-payment-select2.js"]();
/******/ 	
/******/ })()
;