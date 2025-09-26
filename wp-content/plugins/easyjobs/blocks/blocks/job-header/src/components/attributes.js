import { __ } from "@wordpress/i18n";
import { 
    BLOCK_BOX_SHADOW, 
    BLOCK_MARGIN, 
    BLOCK_PADDING, 
    BLOCK_WIDTH, 
    WEBSITE_LINK_BTN_BDR_SHADOW, 
    WEBSITE_LINK_BTN_BG,
    WEBSITE_LINK_BTN_PADDING,
    INFO_BACKGROUND,
    INFO_WIDTH,
    INFO_MARGIN,
    INFO_PADDING,
    INFO_BOX_SHADOW,

} from "./constants";

import * as typoPrefixes from "./typographyContants";

const {
    generateResponsiveRangeAttributes,
    generateDimensionsAttributes,
    generateTypographyAttributes,
    generateBorderShadowAttributes,
    generateBackgroundAttributes,
} = window.EJControls;

const attributes = {
    // the following 4 attributes is must required for responsive options and asset generation for frontend
    // responsive control attributes ⬇
    resOption: {
        type: "string",
        default: "Desktop",
    },
    // blockId attribute for making unique className and other uniqueness ⬇
    blockId: {
        type: "string",
    },
    // blockRoot: {
    //     type: "string",
    //     default: "easyjobs_header_block",
    // },
    // blockMeta is for keeping all the styles ⬇
    blockMeta: {
        type: "object",
    },
    changeCoverImage: {
        type: "boolean",
        default: false,
    },
    changeLogoImage: {
        type: "boolean",
        default: false,
    },
    companyName: {
        type: "string",
        default: __("", "easyjobs"),
    },
    websiteLinkText: {
        type: "string",
        default: __("Explore company website", "easyjobs"),
    },
    coverImgUrl: {
        type: "string",
        default: '',
    },
    coverImgId: {
        type: "string",
    },
    coverImgAlt: {
        type: "string",
    },
    logoImgUrl: {
        type: "string",
        default: '',
    },
    logoImgId: {
        type: "string",
    },
    logoImgAlt: {
        type: "string",
    },
    blockAlign: {
        type: "string",
        default: "center",
    },
    descriptionAlign: {
        type: "string",
        default: "left",
    },
    companyNameColor: {
        type: "string",
        default: "",
    },
    locationNameColor: {
        type: "string",
        default: "",
    },
    websiteLinkBtnColor: {
        type: "string",
        default: "",
    },
    websiteLinkBtnColorHvr: {
        type: "string",
        default: "",
    },
    descriptionColor: {
        type: "string",
        default: "",
    },
    infoAlign: {
        type: "string",
        default: "center",
    },
    cover: {
        type: "string",
        default: "",
    },
    // typography attributes ⬇
    ...generateTypographyAttributes(Object.values(typoPrefixes)),
    // responsive range control
    ...generateResponsiveRangeAttributes(BLOCK_WIDTH, {
        defaultRange: 100,
        defaultUnit: '%'
    }),
    ...generateResponsiveRangeAttributes(INFO_WIDTH, {
        defaultRange: 100,
        defaultUnit: '%'
    }),
    // dimension control
    ...generateDimensionsAttributes(BLOCK_MARGIN, {
        top: '',
        right: '',
        bottom: '',
        left: '',
        isLinked: true,
    }),
    ...generateDimensionsAttributes(INFO_MARGIN, {
        top: '',
        right: '',
        bottom: '',
        left: '',
        isLinked: true,
    }),
    ...generateDimensionsAttributes(INFO_PADDING, {
        top: '',
        right: '',
        bottom: '',
        left: '',
        isLinked: true,
    }),
    ...generateDimensionsAttributes(BLOCK_PADDING, {
        top: '',
        right: '',
        bottom: '',
        left: '',
        isLinked: true,
    }),
    ...generateDimensionsAttributes(WEBSITE_LINK_BTN_PADDING, {
        top: '',
        right: '',
        bottom: '',
        left: '',
        isLinked: true,
    }),
    // background Attributes
    ...generateBackgroundAttributes(WEBSITE_LINK_BTN_BG, {
        noOverlay: true,
        noMainBgi: true,
        defaultFillColor: "#2fc1e1",
        defaultHovFillColor: "#1fb6d7",
    }),
    // box shadow
    ...generateBorderShadowAttributes(BLOCK_BOX_SHADOW),
    ...generateBorderShadowAttributes(INFO_BOX_SHADOW),
    ...generateBorderShadowAttributes(WEBSITE_LINK_BTN_BDR_SHADOW),
    // background Attributes
    ...generateBackgroundAttributes(INFO_BACKGROUND, {
        noOverlay: true,
        noMainBgi: true,
        defaultFillColor: "#f9f9f9",
        defaultHovFillColor: "#f9f9f9",
    }),
};

export default attributes;