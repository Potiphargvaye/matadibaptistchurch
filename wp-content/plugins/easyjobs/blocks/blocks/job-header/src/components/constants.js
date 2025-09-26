import { __ } from "@wordpress/i18n";
import { Dashicon } from "@wordpress/components";

export const CONTENT_POSITION = [
	{ label: __(<Dashicon icon={"editor-alignleft"} />), value: "left" },
	{ label: __(<Dashicon icon={"editor-aligncenter"} />), value: "center" },
	{ label: __(<Dashicon icon={"editor-alignright"} />), value: "right" },
];

export const DESCRIPTION_POSITION = [
	{ label: __(<Dashicon icon={"editor-alignleft"} />), value: "left" },
	{ label: __(<Dashicon icon={"editor-aligncenter"} />), value: "center" },
	{ label: __(<Dashicon icon={"editor-alignright"} />), value: "right" },
	{ label: __(<Dashicon icon={"editor-justify"} />), value: "justify" },
];

export const ICON_POSITION = [
	{ label: __(<Dashicon icon={"editor-alignleft"} />), value: "left" },
	{ label: __(<Dashicon icon={"editor-alignright"} />), value: "right" },
];

export const BUTTON_WIDTH = [
	{ label: __("Fixed", "easyjobs"), value: "fixed" },
];

// responsive range control
export const BLOCK_WIDTH = "blockWidth";
export const INFO_WIDTH = "infoWidth";
export const ICON_SIZE = "iconSize";
export const ICON_SPACE = "iconSpace";
// dimension control
export const BLOCK_MARGIN = "blockMargin";
export const INFO_MARGIN = "infoMargin";
export const BLOCK_PADDING = "blockPadding";
export const INFO_PADDING = "infoPadding";
// background
export const INFO_BACKGROUND = "infoBg";
export const WEBSITE_LINK_BTN_PADDING = "websiteLinkBtnPadding";
// background
export const WEBSITE_LINK_BTN_BG = "websiteLinkBtnBg";
// border
export const BLOCK_BOX_SHADOW = "blockBoxShw";
export const INFO_BOX_SHADOW = "infoBoxShw";
export const WEBSITE_LINK_BTN_BDR_SHADOW = "websiteLinkBtnBDRShw";