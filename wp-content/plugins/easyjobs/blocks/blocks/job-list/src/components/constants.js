import { __ } from "@wordpress/i18n";
import { Dashicon } from "@wordpress/components";

export const ORDER_BY = [
	{ label: __("Title", "easyjobs"), value: "title" },
	{ label: __("Published date", "easyjobs"), value: "published_at" },
	{ label: __("Expired date", "easyjobs"), value: "expire_at" },
	{ label: __("Created date", "easyjobs"), value: "created_at" }
];
export const SORT_BY = [
	{ label: __("ASC", "easyjobs"), value: "asc" },
	{ label: __("DESC", "easyjobs"), value: "desc" }
];
// background
export const BLOCK_BACKGROUND = "blockBg";
export const CONTENT_POSITION = [
	{ label: __(<Dashicon icon={"editor-alignleft"} />), value: "left" },
	{ label: __(<Dashicon icon={"editor-aligncenter"} />), value: "center" },
	{ label: __(<Dashicon icon={"editor-alignright"} />), value: "right" },
];
// responsive range control
export const BLOCK_WIDTH = "blockWidth";
// dimension control
export const WRAPPER_MARGIN = "wrpMargin";
export const WRAPPER_PADDING = "wrpPadding";
export const APPLY_BTN_PADDING = "applyBtnPadding";
// border
export const WRAPPER_BORDER = "wrpBrdShw";
export const JOB_LIST_BACKGROUND = "jobListBg";
export const JOB_LIST_BORDER = "jobListBrdShw";
export const APPLY_BTN_BORDER = "applyBtnBrdShw";
export const APPLY_BUTTON_POSITION = [
	{ label: __(<Dashicon icon={"editor-alignleft"} />), value: "left" },
	{ label: __(<Dashicon icon={"editor-aligncenter"} />), value: "center" },
	{ label: __(<Dashicon icon={"editor-alignright"} />), value: "right" },
];

export const ICON_POSITION = [
	{ label: __(<Dashicon icon={"editor-alignleft"} />), value: "left" },
	{ label: __(<Dashicon icon={"editor-alignright"} />), value: "right" },
];

export const BUTTON_WIDTH = [
	{ label: __("Fixed", "easyjobs"), value: "fixed" },
];

export const JOB_LIST_TITLE_ICON_WIDTH = [
	{ label: __("Fixed", "easyjobs"), value: "fixed" },
];

export const JOB_LIST_TITLE_ICON_HEIGHT = [
	{ label: __("Fixed", "easyjobs"), value: "fixed" },
];

export const JOB_LIST_TITLE_ICON_SIZE = [
	{ label: __("Fixed", "easyjobs"), value: "fixed" },
];


export const JOB_LIST_TITLE_ICON_WIDTH_FIXED = "jobListTitleIconWidth";
export const JOB_LIST_TITLE_ICON_HEIGHT_FIXED = "jobListTitleIconHeight";
export const JOB_LIST_TITLE_ICON_FIXED_SIZE = "jobListTitleIconSize";
export const ICON_SIZE = "iconSize";
export const ICON_SPACE = "iconSpace";

export const JOB_LIST_PADDING = "jobListPadding";
export const JOB_LIST_MARGIN = "jobListMargin";
export const SPACE_BETWEEN_TITLE_COMPANY = "spaceBetweenTitleCompany";
export const JOB_LIST_TITLE_MARGIN = "jobListTitleMargin";
export const JOB_LIST_TITLE_PADDING = "jobListTitlePadding";
