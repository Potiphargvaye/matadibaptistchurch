import { __ } from "@wordpress/i18n";

import {
    BLOCK_WIDTH,
    WRAPPER_PADDING,
    JOB_LIST_PADDING,
    ICON_SIZE,
    ICON_SPACE,
    BLOCK_BACKGROUND,
    JOB_LIST_BACKGROUND,
    WRAPPER_BORDER,
    JOB_LIST_BORDER,
    WRAPPER_MARGIN,
    JOB_LIST_TITLE_MARGIN,
    JOB_LIST_TITLE_PADDING,
    JOB_LIST_TITLE_ICON_WIDTH_FIXED,
    JOB_LIST_TITLE_ICON_HEIGHT_FIXED,
    JOB_LIST_TITLE_ICON_FIXED_SIZE,
    JOB_LIST_MARGIN,
    SPACE_BETWEEN_TITLE_COMPANY,
    APPLY_BTN_BORDER,
    APPLY_BTN_PADDING,
} from "./constants";
import * as typoPrefixes from "./typographyContants";

const {
    generateResponsiveRangeAttributes,
    generateTypographyAttributes,
    generateBorderShadowAttributes,
    generateDimensionsAttributes,
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
    // blockMeta is for keeping all the styles ⬇
    blockMeta: {
        type: "object",
    },
    // blockRoot: {
    //     type: "string",
    //     default: "easyjobs_list_block",
    // },
	hideTitle: {
        type: "boolean",
        default: false,
    },
    titleText: {
        type: "string",
        default: __("Open Job Positions", "easyjobs"),
    },
    hideIcon: {
        type: "boolean",
        default: false,
    },
    icon: {
        type: "string",
        default: "fas fa-briefcase",
    },
    applyBtnText: {
        type: "string",
        default: "Apply",
    },
	filterByTitle: {
		type: "boolean",
        default: true,
	},
	filterByCategory: {
		type: "boolean",
        default: true,
	},
	filterByLocation: {
		type: "boolean",
        default: true,
	},
    orderBy: {
        type: "string",
        default: "title",
    },
    sortBy: {
        type: "string",
        default: "asc",
    },
	noOfJob: {
		type: "number",
		default: 2,
	},
	activeOnly: {
		type: "boolean",
        default: true,
	},
	showCompanyName: {
		type: "boolean",
        default: true,
	},
	showLocation: {
		type: "boolean",
        default: true,
	},
	showDateLine: {
		type: "boolean",
        default: true,
	},
	showNoOfJob: {
		type: "boolean",
        default: true,
	},
    contentAlign: {
        type: "string",
        default: "center",
    },

    addIcon: {
        type: "boolean",
        default: false,
    },
    iconPosition: {
        type: "string",
        default: "left",
    },
    iconSize: {
        type: "string",
    },
    iconSpace: {
        type: "string",
        default: "5px",
    },
    textColor: {
        type: "string",
        default: "var(--eb-global-button-text-color)",
    },
    jobListTitleColor: {
        type: "string",
        default: "#2f323e",
    },
    jobListTitleIconBgColor: {
        type: "string",
        default: "rgba(89,125,252,.1)",
    },
    jobListTitleIconColor: {
        type: "string",
        default: "#597dfc",
    },
    applyBtnTextColor: {
        type: "string",
        default: "",
    },
    applyBtnBgColor: {
        type: "string",
        default: "",
    },
    applyBtnTextColorH: {
        type: "string",
        default: "",
    },
    applyBtnBgColorH: {
        type: "string",
        default: "",
    },
    submitTextColor: {
        type: "string",
        default: "#ff9635",
    },
    submitBgColor: {
        type: "string",
        default: "rgba(255,150,53,.1)",
    },
    submitTextColorH: {
        type: "string",
        default: "#ff9635",
    },
    submitBgColorH: {
        type: "string",
        default: "rgba(255,150,53,.1)",
    },
    jobTitleColor: {
        type: "string",
        default: "",
    },
    resetTextColor: {
        type: "string",
        default: "#ff5f74",
    },
    resetBgColor: {
        type: "string",
        default: "rgba(255,95,116,.1)",
    },
    resetTextColorH: {
        type: "string",
        default: "#ff5f74",
    },
    resetBgColorH: {
        type: "string",
        default: "rgba(255,95,116,.1)",
    },
    buttonURL: {
        type: "string",
        default: "Apply",
    },
    titleSearchValue: {
        type: "string",
        default: "",
    },
    categorySearchValue: {
        type: "string",
        default: "Select Category",
    },
    locationSearchValue: {
        type: "string",
        default: "Select Location",
    },
    newWindow: {
        type: "boolean",
        default: false,
    },
    addNofollow: {
        type: "boolean",
        default: false,
    },
    applyButtonAlign: {
        type: "string",
        default: "center",
    },
    buttonAlign: {
        type: "string",
        default: "center",
    },
    jobListTitleIconSize: {
        type: "string",
        default: "fixed",
    },
    buttonWidth: {
        type: "string",
        default: "auto",
    },
    jobListTitleIconWidth: {
        type: "string",
        default: "fixed",
    },
    jobListTitleIconHeight: {
        type: "string",
        default: "fixed",
    },
    hoverEffect: {
        type: "string",
    },
    hoverTextColor: {
        type: "string",
        default: "var(--eb-global-button-text-color)",
    },
    separatorColor: {
        type: "string",
        default: "#f5f7fd",
    },
    companyNameColor: {
        type: "string",
        default: "",
    },
    jobLocationColor: {
        type: "string",
        default: "",
    },
    jobDeadlineColor: {
        type: "string",
        default: "",
    },
    jobVacancyColor: {
        type: "string",
        default: "",
    },
    hoverTransition: {
        type: "number",
        default: 0.3,
    },
    submitStyle: {
        type: "string",
        default: 'normal',
    },
    applyBtnStyle: {
        type: "string",
        default: 'normal',
    },
    resetStyle: {
        type: "string",
        default: 'normal',
    },
    selectedTemplate: {
        type: "string",
        default: 'default',
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
    ...generateResponsiveRangeAttributes(JOB_LIST_TITLE_ICON_FIXED_SIZE, {
        defaultRange: 20,
        defaultUnit: 'px'
    }),
    ...generateResponsiveRangeAttributes(JOB_LIST_TITLE_ICON_WIDTH_FIXED, {
        defaultRange: 50,
        defaultUnit: 'px'
    }),
    ...generateResponsiveRangeAttributes(JOB_LIST_TITLE_ICON_WIDTH_FIXED, {
        defaultRange: 50,
        defaultUnit: 'px'
    }),
    ...generateResponsiveRangeAttributes(JOB_LIST_TITLE_ICON_HEIGHT_FIXED, {
        defaultRange: 50,
        defaultUnit: 'px'
    }),
    ...generateResponsiveRangeAttributes(ICON_SIZE, {
        noUnits: true,
    }),
    ...generateResponsiveRangeAttributes(ICON_SPACE, {
        defaultRange: 8,
        noUnits: true,
    }),
    // dimension control
    ...generateDimensionsAttributes(WRAPPER_PADDING, {
        top: '',
        right: '',
        bottom: '',
        left: '',
        isLinked: true,
    }),
    ...generateDimensionsAttributes(APPLY_BTN_PADDING, {
        top: '',
        right: '',
        bottom: '',
        left: '',
        isLinked: true,
    }),
    ...generateDimensionsAttributes(JOB_LIST_PADDING, {
        top: '',
        right: '',
        bottom: '',
        left: '',
        isLinked: true,
    }),
    ...generateDimensionsAttributes(JOB_LIST_MARGIN, {
        top: '',
        right: '',
        bottom: '',
        left: '',
        isLinked: true,
    }),
    ...generateDimensionsAttributes(SPACE_BETWEEN_TITLE_COMPANY, {
        top: '',
        right: '',
        bottom: '',
        left: '',
        isLinked: true,
    }),
    ...generateDimensionsAttributes(JOB_LIST_TITLE_MARGIN, {
        top: '',
        right: '',
        bottom: '',
        left: '',
        isLinked: true,
    }),
    ...generateDimensionsAttributes(JOB_LIST_TITLE_PADDING, {
        top: '',
        right: '',
        bottom: '',
        left: '',
        isLinked: true,
    }),
    ...generateDimensionsAttributes(WRAPPER_MARGIN, {
        top: '',
        right: '',
        bottom: '',
        left: '',
        isLinked: true,
    }),
    // background Attributes
    ...generateBackgroundAttributes(BLOCK_BACKGROUND, {
        noOverlay: true,
        noMainBgi: true,
        defaultFillColor: "#f9f9f9",
        defaultHovFillColor: "#f9f9f9",
    }),
    // job list background attributes
    ...generateBackgroundAttributes(JOB_LIST_BACKGROUND, {
        noOverlay: true,
        noMainBgi: true,
        defaultFillColor: "#fff",
        defaultHovFillColor: "#fff",
    }),
    // border shadow
    ...generateBorderShadowAttributes(WRAPPER_BORDER),
    ...generateBorderShadowAttributes(JOB_LIST_BORDER),
    ...generateBorderShadowAttributes(APPLY_BTN_BORDER),
};

export default attributes;