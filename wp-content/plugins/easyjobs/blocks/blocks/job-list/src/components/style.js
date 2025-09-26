import {
    BLOCK_BACKGROUND,
    BLOCK_WIDTH,
    WRAPPER_MARGIN,
    WRAPPER_PADDING,
    WRAPPER_BORDER,
    JOB_LIST_BACKGROUND,
    JOB_LIST_BORDER,
    JOB_LIST_PADDING,
    JOB_LIST_MARGIN,
    JOB_LIST_TITLE_MARGIN,
    SPACE_BETWEEN_TITLE_COMPANY,
    JOB_LIST_TITLE_PADDING,
    JOB_LIST_TITLE_ICON_WIDTH_FIXED,
    JOB_LIST_TITLE_ICON_HEIGHT_FIXED,
    JOB_LIST_TITLE_ICON_FIXED_SIZE,
    APPLY_BTN_BORDER,
    APPLY_BTN_PADDING,
} from "./constants";

import {
    TypoprefixTextJobListTitle,
    TypoprefixTextSubmitBtn,
    TypoprefixTextResetBtn,
    TypoprefixTextJobListJobTitle,
    TypoprefixTextJobListCompanyName,
    TypoprefixTextJobListJobLocation,
    TypoprefixTextJobListNoOfJobs,
    TypoprefixTextJobListDeadline,
    TypoprefixTextJobListJobApplyBtn,
} from "./typographyContants";

const {
    softMinifyCssStrings,
    generateDimensionsControlStyles,
    generateBorderShadowStyles,
    generateTypographyStyles,
    generateBackgroundControlStyles,
    generateResponsiveRangeStyles,
    StyleComponent
} = window.EJControls;

export default function Style(props) {
    const { attributes, setAttributes, name } = props;
    const {
        contentAlign,
        jobListTitleIconWidth,
        jobListTitleIconHeight,
        jobListTitleIconSize,
        jobTitleColor,
        applyButtonAlign,
        separatorColor,
        hoverTransition,
        jobListTitleColor,
        jobListTitleIconBgColor,
        jobListTitleIconColor,
        submitTextColor,
        submitBgColor,
        submitTextColorH,
        submitBgColorH,
        resetTextColor,
        resetBgColor,
        resetTextColorH,
        resetBgColorH,
        companyNameColor,
        jobLocationColor,
        jobDeadlineColor,
        jobVacancyColor,
        applyBtnTextColor,
        applyBtnBgColor,
        applyBtnTextColorH,
        applyBtnBgColorH,
    } = attributes;

    /**
     * CSS/styling Codes Starts from Here
     */

    // button custom padding
    const {
        dimensionStylesDesktop: buttonPaddingDesktop,
        dimensionStylesTab: buttonPaddingTab,
        dimensionStylesMobile: buttonPaddingMobile,
    } = generateDimensionsControlStyles({
        controlName: WRAPPER_PADDING,
        styleFor: "padding",
        attributes,
    });
    // apply button custom padding
    const {
        dimensionStylesDesktop: applyBtnPaddingDesktop,
        dimensionStylesTab: applyBtnPaddingTab,
        dimensionStylesMobile: applyBtnPaddingMobile,
    } = generateDimensionsControlStyles({
        controlName: APPLY_BTN_PADDING,
        styleFor: "padding",
        attributes,
    });
    const {
        dimensionStylesDesktop: jobListPaddingDesktop,
        dimensionStylesTab: jobListPaddingTab,
        dimensionStylesMobile: jobListPaddingMobile,
    } = generateDimensionsControlStyles({
        controlName: JOB_LIST_PADDING,
        styleFor: "padding",
        attributes,
    });
    const {
        dimensionStylesDesktop: jobListMarginDesktop,
        dimensionStylesTab: jobListMarginTab,
        dimensionStylesMobile: jobListMarginMobile,
    } = generateDimensionsControlStyles({
        controlName: JOB_LIST_MARGIN,
        styleFor: "margin",
        attributes,
    });

    // job list title margin
    const {
        dimensionStylesDesktop: jobListTitleMarginDesktop,
        dimensionStylesTab: jobListTitleMarginTab,
        dimensionStylesMobile: jobListTitleMarginMobile,
    } = generateDimensionsControlStyles({
        controlName: JOB_LIST_TITLE_MARGIN,
        styleFor: "margin",
        attributes,
    });
    const {
        dimensionStylesDesktop: spaceBetweenTitleCompanyinDesktop,
        dimensionStylesTab: spaceBetweenTitleCompanyinTab,
        dimensionStylesMobile: spaceBetweenTitleCompanyinMobile,
    } = generateDimensionsControlStyles({
        controlName: SPACE_BETWEEN_TITLE_COMPANY,
        styleFor: "padding-bottom",
        attributes,
    });

    // job list title padding
    const {
        dimensionStylesDesktop: jobListTitlePaddingDesktop,
        dimensionStylesTab: jobListTitlePaddingTab,
        dimensionStylesMobile: jobListTitlePaddingMobile,
    } = generateDimensionsControlStyles({
        controlName: JOB_LIST_TITLE_PADDING,
        styleFor: "padding",
        attributes,
    });

    // button custom width
    const {
        rangeStylesDesktop: buttonWidthDesktop,
        rangeStylesTab: buttonWidthTab,
        rangeStylesMobile: buttonWidthMobile,
    } = generateResponsiveRangeStyles({
        controlName: BLOCK_WIDTH,
        property: "width",
        attributes,
    });
    const {
        rangeStylesDesktop: jobListTitleIconWidthDesktop,
        rangeStylesTab: jobListTitleIconWidthTab,
        rangeStylesMobile: jobListTitleIconWidthMobile,
    } = generateResponsiveRangeStyles({
        controlName: JOB_LIST_TITLE_ICON_WIDTH_FIXED,
        property: "width",
        attributes,
    });
    const {
        rangeStylesDesktop: jobListTitleIconHeightDesktop,
        rangeStylesTab: jobListTitleIconHeightTab,
        rangeStylesMobile: jobListTitleIconHeightMobile,
    } = generateResponsiveRangeStyles({
        controlName: JOB_LIST_TITLE_ICON_HEIGHT_FIXED,
        property: "height",
        attributes,
    });
    const {
        rangeStylesDesktop: jobListTitleIconSizetDesktop,
        rangeStylesTab: jobListTitleIconSizetTab,
        rangeStylesMobile: jobListTitleIconSizetMobile,
    } = generateResponsiveRangeStyles({
        controlName: JOB_LIST_TITLE_ICON_FIXED_SIZE,
        property: "font-size",
        attributes,
        customUnit: "px",
    });

    // job list background styles
    const {
        backgroundStylesDesktop: jobListBackgroundStylesDesktop,
        hoverBackgroundStylesDesktop: jobListHoverBackgroundStylesDesktop,
        backgroundStylesTab: jobListBackgroundStylesTab,
        hoverBackgroundStylesTab: jobListHoverBackgroundStylesTab,
        backgroundStylesMobile: jobListBackgroundStylesMobile,
        hoverBackgroundStylesMobile: jobListHoverBackgroundStylesMobile,
        bgTransitionStyle: jobListBgTransitionStyle,
    } = generateBackgroundControlStyles({
        attributes,
        controlName: JOB_LIST_BACKGROUND,
    });
    // block background styles
    const {
        backgroundStylesDesktop: btnBackgroundStylesDesktop,
        hoverBackgroundStylesDesktop: btnHoverBackgroundStylesDesktop,
        backgroundStylesTab: btnBackgroundStylesTab,
        hoverBackgroundStylesTab: btnHoverBackgroundStylesTab,
        backgroundStylesMobile: btnBackgroundStylesMobile,
        hoverBackgroundStylesMobile: btnHoverBackgroundStylesMobile,
        bgTransitionStyle: btnBgTransitionStyle,
    } = generateBackgroundControlStyles({
        attributes,
        controlName: BLOCK_BACKGROUND,
    });

    // block border
    const {
        styesDesktop: bdShadowStyesDesktop,
        styesTab: bdShadowStyesTab,
        styesMobile: bdShadowStyesMobile,
        stylesHoverDesktop: bdShadowStylesHoverDesktop,
        stylesHoverTab: bdShadowStylesHoverTab,
        stylesHoverMobile: bdShadowStylesHoverMobile,
        transitionStyle: bdShadowTransitionStyle,
    } = generateBorderShadowStyles({
        controlName: WRAPPER_BORDER,
        attributes,
    });
    // job list border
    const {
        styesDesktop: jobListBdShadowStyesDesktop,
        styesTab: jobListBdShadowStyesTab,
        styesMobile: jobListBdShadowStyesMobile,
        stylesHoverDesktop: jobListBdShadowStylesHoverDesktop,
        stylesHoverTab: jobListBdShadowStylesHoverTab,
        stylesHoverMobile: jobListBdShadowStylesHoverMobile,
        transitionStyle: jobListBdShadowTransitionStyle,
    } = generateBorderShadowStyles({
        controlName: JOB_LIST_BORDER,
        attributes,
    });
    // apply btn border
    const {
        styesDesktop: applyBtnBdShadowStyesDesktop,
        styesTab: applyBtnBdShadowStyesTab,
        styesMobile: applyBtnBdShadowStyesMobile,
        stylesHoverDesktop: applyBtnBdShadowStylesHoverDesktop,
        stylesHoverTab: applyBtnBdShadowStylesHoverTab,
        stylesHoverMobile: applyBtnBdShadowStylesHoverMobile,
        transitionStyle: applyBtnBdShadowTransitionStyle,
    } = generateBorderShadowStyles({
        controlName: APPLY_BTN_BORDER,
        attributes,
    });

    // typography job list title
    const {
        typoStylesDesktop: textTypoStylesDesktop,
        typoStylesTab: textTypoStylesTab,
        typoStylesMobile: textTypoStylesMobile,
    } = generateTypographyStyles({
        attributes,
        prefixConstant: TypoprefixTextJobListTitle,
    });

    // typography submit btn
    const {
        typoStylesDesktop: submitBtnTypoStylesDesktop,
        typoStylesTab: submitBtnTypoStylesTab,
        typoStylesMobile: submitBtnTypoStylesMobile,
    } = generateTypographyStyles({
        attributes,
        prefixConstant: TypoprefixTextSubmitBtn,
    });

    // typography reset btn
    const {
        typoStylesDesktop: resetBtnTypoStylesDesktop,
        typoStylesTab: resetBtnTypoStylesTab,
        typoStylesMobile: resetBtnTypoStylesMobile,
    } = generateTypographyStyles({
        attributes,
        prefixConstant: TypoprefixTextResetBtn,
    });
    // typography job title
    const {
        typoStylesDesktop: jobTitleTypoStylesDesktop,
        typoStylesTab: jobTitleTypoStylesTab,
        typoStylesMobile: jobTitleTypoStylesMobile,
    } = generateTypographyStyles({
        attributes,
        prefixConstant: TypoprefixTextJobListJobTitle,
    });
    // typography company name
    const {
        typoStylesDesktop: companyNameTypoStylesDesktop,
        typoStylesTab: companyNameTypoStylesTab,
        typoStylesMobile: companyNameTypoStylesMobile,
    } = generateTypographyStyles({
        attributes,
        prefixConstant: TypoprefixTextJobListCompanyName,
    });
    // typography company name
    const {
        typoStylesDesktop: jobLocationTypoStylesDesktop,
        typoStylesTab: jobLocationTypoStylesTab,
        typoStylesMobile: jobLocationTypoStylesMobile,
    } = generateTypographyStyles({
        attributes,
        prefixConstant: TypoprefixTextJobListJobLocation,
    });
    // typography company name
    const {
        typoStylesDesktop: jobDesdlineTypoStylesDesktop,
        typoStylesTab: jobDesdlineTypoStylesTab,
        typoStylesMobile: jobDesdlineTypoStylesMobile,
    } = generateTypographyStyles({
        attributes,
        prefixConstant: TypoprefixTextJobListDeadline,
    });
    // typography company name
    const {
        typoStylesDesktop: noOfJobsTypoStylesDesktop,
        typoStylesTab: noOfJobsTypoStylesTab,
        typoStylesMobile: noOfJobsTypoStylesMobile,
    } = generateTypographyStyles({
        attributes,
        prefixConstant: TypoprefixTextJobListNoOfJobs,
    });
    // typography company name
    const {
        typoStylesDesktop: applyBtnTypoStylesDesktop,
        typoStylesTab: applyBtnTypoStylesTab,
        typoStylesMobile: applyBtnTypoStylesMobile,
    } = generateTypographyStyles({
        attributes,
        prefixConstant: TypoprefixTextJobListJobApplyBtn,
    });

    // wrapper margin
    const {
        dimensionStylesDesktop: wrapperMarginStylesDesktop,
        dimensionStylesTab: wrapperMarginStylesTab,
        dimensionStylesMobile: wrapperMarginStylesMobile,
    } = generateDimensionsControlStyles({
        controlName: WRAPPER_MARGIN,
        styleFor: "margin",
        attributes,
    });

    const desktopStyles = `
        .easyjobs-blocks {
            ${buttonPaddingDesktop};
            ${wrapperMarginStylesDesktop};
            ${bdShadowStyesDesktop};
            transition: ${hoverTransition ? `all ${hoverTransition}s,` : ""
            } 
            ${btnBgTransitionStyle}, ${bdShadowTransitionStyle}; 
            ${btnBackgroundStylesDesktop};
            ${contentAlign === 'center' ? `display: flex; justify-content: center;` : ''}
            ${contentAlign === 'left' ? `display: flex; justify-content: flex-start;` : ''}
            ${contentAlign === 'right' ? `display: flex; justify-content: flex-end;` : ''}
        }
        .easyjobs-shortcode-wrapper {
            ${buttonWidthDesktop};
        }
        .easyjobs-blocks:hover {
            ${btnHoverBackgroundStylesDesktop}
        }


        .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-section-title {
            ${jobListTitleMarginDesktop};
            ${jobListTitlePaddingDesktop};
        }
        .ej-section .ej-section-title .ej-section-title-text {
            ${jobListTitleColor ? `color: ${jobListTitleColor} !important;` : ""}
            ${textTypoStylesDesktop ? textTypoStylesDesktop : ''}
        }
		${jobListTitleIconWidth == "fixed"
            ? `.ej-section .ej-section-title .ej-section-title-icon {
                ${jobListTitleIconWidthDesktop} !important
            }`
            : ""
        }
        ${jobListTitleIconHeight == "fixed"
            ? `.ej-section .ej-section-title .ej-section-title-icon {
                ${jobListTitleIconHeightDesktop} !important
            }`
            : ""
        }
        ${jobListTitleIconSize == "fixed"
            ? `.ej-section .ej-section-title .ej-section-title-icon {
                ${jobListTitleIconSizetDesktop} !important
            }`
            : ""
        }
        .ej-section-title .ej-section-title-icon {
            ${jobListTitleIconBgColor ? `background-color: ${jobListTitleIconBgColor} !important;` : ""}
            ${jobListTitleIconColor ? `color: ${jobListTitleIconColor} !important;` : ""}
        }


        .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light {
            ${submitBgColor ? `background-color: ${submitBgColor} !important;` : ""}
            ${submitTextColor ? `color: ${submitTextColor} !important;` : ""}
            ${submitBtnTypoStylesDesktop ? submitBtnTypoStylesDesktop : ""}
        }
        .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light:hover {
            ${submitBgColorH ? `background-color: ${submitBgColorH} !important;` : ""}
            ${submitTextColorH ? `color: ${submitTextColorH} !important;` : ""}
        }
        .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn {
            ${resetBgColor ? `background-color: ${resetBgColor} !important;` : ""}
            ${resetTextColor ? `color: ${resetTextColor} !important;` : ""}
            ${resetBtnTypoStylesDesktop ? resetBtnTypoStylesDesktop : ""}
        }
        .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn:hover {
            ${resetBgColorH ? `background-color: ${resetBgColorH} !important;` : ""}
            ${resetTextColorH ? `color: ${resetTextColorH} !important;` : ""}
        }


        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item,
        .ej-template-elegant .ej-job-list-elegant .job__card {
            ${jobListBackgroundStylesDesktop};
            ${jobListBdShadowStyesDesktop};
            ${jobListPaddingDesktop ? jobListPaddingDesktop : ""}
            ${jobListMarginDesktop ? jobListMarginDesktop : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item:hover,
        .ej-template-elegant .ej-job-list-elegant .job__card:hover    {
            ${jobListHoverBackgroundStylesDesktop}
            ${jobListBdShadowStylesHoverDesktop};
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col {
            ${separatorColor ? `border-color: ${separatorColor} !important;` : ""}
        }
        .ej-section-content .ej-job-list .ej-job-title a,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__info h3 a,
        .ej-template-elegant .job__card h3 a {
            ${jobTitleTypoStylesDesktop ? jobTitleTypoStylesDesktop : ""}
            ${jobTitleColor ? `color: ${jobTitleColor} !important;` : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-title,
        .ej-template-elegant .job__card .ej-job-title {
            ${spaceBetweenTitleCompanyinDesktop ? spaceBetweenTitleCompanyinDesktop : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-info-block.ej-job-list-company-name a,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__info .meta a,
        .ej-template-elegant .job__card .meta a {
            ${companyNameColor ? `color: ${companyNameColor} !important;` : ""}
            ${companyNameTypoStylesDesktop ? companyNameTypoStylesDesktop : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-info-block.ej-job-list-location span,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__info .meta span span,
        .ej-template-elegant .job__card .meta span span {
            ${jobLocationColor ? `color: ${jobLocationColor} !important;` : ""}
            ${jobLocationTypoStylesDesktop ? jobLocationTypoStylesDesktop : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-deadline,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__apply .deadline span,
        .ej-template-elegant .job__card .deadline {
            ${jobDeadlineColor ? `color: ${jobDeadlineColor} !important;` : ""}
            ${jobDesdlineTypoStylesDesktop ? jobDesdlineTypoStylesDesktop : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-list-sub,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__vacancy h4,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__vacancy p,
        .ej-template-elegant .job__card .job__bottom .job__vacancy h4,
        .ej-template-elegant .job__card .job__bottom .job__vacancy p {
            ${jobVacancyColor ? `color: ${jobVacancyColor} !important;` : ""}
            ${noOfJobsTypoStylesDesktop ? noOfJobsTypoStylesDesktop : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-btn.ej-info-btn-light,
        .easyjobs-shortcode-wrapper.ej-template-classic .button__success,
        .ej-template-elegant .button.button__primary.radius-15 {
            ${applyBtnTypoStylesDesktop ? applyBtnTypoStylesDesktop : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-apply-btn {
            text-align: ${applyButtonAlign} !important
        }
        .ej-job-list-item-inner .ej-job-apply-btn .ej-btn.ej-info-btn-light {
            ${applyBtnBgColor ? `background-color: ${applyBtnBgColor} !important;` : ""}
            ${applyBtnTextColor ? `color: ${applyBtnTextColor} !important;` : ""}
            ${applyBtnBdShadowStyesDesktop ? applyBtnBdShadowStyesDesktop : ""}
            ${applyBtnPaddingDesktop ? applyBtnPaddingDesktop : ""}
        }
        .ej-job-list-item-inner .ej-job-apply-btn .ej-btn.ej-info-btn-light:hover {
            ${applyBtnBgColorH ? `background-color: ${applyBtnBgColorH} !important;` : ""}
            ${applyBtnTextColorH ? `color: ${applyBtnTextColorH} !important;` : ""}
            ${applyBtnBdShadowStylesHoverDesktop ? applyBtnBdShadowStylesHoverDesktop : ""}
        }
        .ej-template-classic .ej-job-list-classic .ej-job-list-item .job__apply .button__success.button__radius {
            ${applyBtnBgColor ? `background-color: ${applyBtnBgColor} !important;` : ""}
            ${applyBtnTextColor ? `color: ${applyBtnTextColor} !important;` : ""}
            ${applyBtnBdShadowStyesDesktop ? applyBtnBdShadowStyesDesktop : ""}
            ${applyBtnPaddingDesktop ? applyBtnPaddingDesktop : ""}
        }
        .ej-template-classic .ej-job-list-classic .ej-job-list-item .job__apply .button__success.button__radius:hover {
            ${applyBtnBgColorH ? `background-color: ${applyBtnBgColorH} !important;` : ""}
            ${applyBtnTextColorH ? `color: ${applyBtnTextColorH} !important;` : ""}
            ${applyBtnBdShadowStyesDesktop ? applyBtnBdShadowStyesDesktop : ""}
        }
        .ej-template-elegant .ej-job-list-elegant .ej-job-list-item-cat .job__apply .button.button__primary.radius-15 {
            ${applyBtnBgColor ? `background-color: ${applyBtnBgColor} !important;` : ""}
            ${applyBtnTextColor ? `color: ${applyBtnTextColor} !important;` : ""}
            ${applyBtnBdShadowStyesDesktop ? applyBtnBdShadowStyesDesktop : ""}
            ${applyBtnPaddingDesktop ? applyBtnPaddingDesktop : ""}
        }
        .ej-template-elegant .ej-job-list-elegant .ej-job-list-item-cat .job__apply .button.button__primary.radius-15:hover {
            ${applyBtnBgColorH ? `background-color: ${applyBtnBgColorH} !important;` : ""}
            ${applyBtnTextColorH ? `color: ${applyBtnTextColorH} !important;` : ""}
            ${applyBtnBdShadowStyesDesktop ? applyBtnBdShadowStyesDesktop : ""}
        }
	`;

    const tabStyles = `
		.easyjobs-blocks {
            ${buttonPaddingTab};
            ${wrapperMarginStylesTab};
            ${bdShadowStyesTab};
            transition: ${hoverTransition ? `all ${hoverTransition}s,` : ""
            } 
            ${btnBgTransitionStyle}, ${bdShadowTransitionStyle}; 
            ${btnBackgroundStylesTab};
            ${contentAlign === 'center' ? `display: flex; justify-content: center;` : ''}
            ${contentAlign === 'left' ? `display: flex; justify-content: flex-start;` : ''}
            ${contentAlign === 'right' ? `display: flex; justify-content: flex-end;` : ''}
        }
        .easyjobs-shortcode-wrapper {
            ${buttonWidthTab};
        }
        .easyjobs-blocks:hover {
            ${btnHoverBackgroundStylesTab}
        }

        
        .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-section-title {
            ${jobListTitleMarginTab};
            ${jobListTitlePaddingTab};
        }
        .ej-section .ej-section-title .ej-section-title-text {
            ${jobListTitleColor ? `color: ${jobListTitleColor} !important;` : ""}
            ${textTypoStylesTab ? textTypoStylesTab : ''}
        }
		${jobListTitleIconWidth == "fixed"
            ? `.ej-section .ej-section-title .ej-section-title-icon {
                ${jobListTitleIconWidthTab} !important
            }`
            : ""
        }
        ${jobListTitleIconHeight == "fixed"
            ? `.ej-section .ej-section-title .ej-section-title-icon {
                ${jobListTitleIconHeightTab} !important
            }`
            : ""
        }
        ${jobListTitleIconSize == "fixed"
            ? `.ej-section .ej-section-title i.ej-section-title-icon {
                ${jobListTitleIconSizetTab} !important
            }`
            : ""
        }
        .ej-section-title .ej-section-title-icon {
            ${jobListTitleIconBgColor ? `background-color: ${jobListTitleIconBgColor} !important;` : ""}
            ${jobListTitleIconColor ? `color: ${jobListTitleIconColor} !important;` : ""}
        }


        .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light {
            ${submitBgColor ? `background-color: ${submitBgColor} !important;` : ""}
            ${submitTextColor ? `color: ${submitTextColor} !important;` : ""}
            ${submitBtnTypoStylesTab ? submitBtnTypoStylesTab : ""}
        }
        .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light:hover {
            ${submitBgColorH ? `background-color: ${submitBgColorH} !important;` : ""}
            ${submitTextColorH ? `color: ${submitTextColorH} !important;` : ""}
        }
        .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn {
            ${resetBgColor ? `background-color: ${resetBgColor} !important;` : ""}
            ${resetTextColor ? `color: ${resetTextColor} !important;` : ""}
            ${resetBtnTypoStylesTab ? resetBtnTypoStylesTab : ""}
        }
        .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn:hover {
            ${resetBgColorH ? `background-color: ${resetBgColorH} !important;` : ""}
            ${resetTextColorH ? `color: ${resetTextColorH} !important;` : ""}
        }


        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item,
        .ej-template-elegant .ej-job-list-elegant .job__card {
            ${jobListBackgroundStylesTab};
            ${jobListBdShadowStyesTab};
            ${jobListPaddingTab ? jobListPaddingTab : ""}
            ${jobListMarginTab ? jobListMarginTab : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item:hover,
        .ej-template-elegant .ej-job-list-elegant .job__card:hover    {
            ${jobListHoverBackgroundStylesTab}
            ${jobListBdShadowStylesHoverTab};
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col {
            ${separatorColor ? `border-color: ${separatorColor} !important;` : ""}
        }
        .ej-section-content .ej-job-list .ej-job-title a,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__info h3 a,
        .ej-template-elegant .job__card h3 a {
            ${jobTitleTypoStylesTab ? jobTitleTypoStylesTab : ""}
            ${jobTitleColor ? `color: ${jobTitleColor} !important;` : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-title,
        .ej-template-elegant .job__card .ej-job-title {
            ${spaceBetweenTitleCompanyinTab ? spaceBetweenTitleCompanyinTab : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-info-block.ej-job-list-company-name a,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__info .meta a,
        .ej-template-elegant .job__card .meta a {
            ${companyNameColor ? `color: ${companyNameColor} !important;` : ""}
            ${companyNameTypoStylesTab ? companyNameTypoStylesTab : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-info-block.ej-job-list-location span,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__info .meta span span,
        .ej-template-elegant .job__card .meta span span {
            ${jobLocationColor ? `color: ${jobLocationColor} !important;` : ""}
            ${jobLocationTypoStylesTab ? jobLocationTypoStylesTab : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-deadline,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__apply .deadline span,
        .ej-template-elegant .job__card .deadline {
            ${jobDeadlineColor ? `color: ${jobDeadlineColor} !important;` : ""}
            ${jobDesdlineTypoStylesTab ? jobDesdlineTypoStylesTab : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-list-sub,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__vacancy h4,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__vacancy p,
        .ej-template-elegant .job__card .job__bottom .job__vacancy h4,
        .ej-template-elegant .job__card .job__bottom .job__vacancy p {
            ${jobVacancyColor ? `color: ${jobVacancyColor} !important;` : ""}
            ${noOfJobsTypoStylesTab ? noOfJobsTypoStylesTab : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-btn.ej-info-btn-light,
        .easyjobs-shortcode-wrapper.ej-template-classic .button__success,
        .ej-template-elegant .button.button__primary.radius-15 {
            ${applyBtnTypoStylesTab ? applyBtnTypoStylesTab : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-apply-btn {
            text-align: ${applyButtonAlign} !important
        }
        .ej-job-list-item-inner .ej-job-apply-btn .ej-btn.ej-info-btn-light {
            ${applyBtnBgColor ? `background-color: ${applyBtnBgColor} !important;` : ""}
            ${applyBtnTextColor ? `color: ${applyBtnTextColor} !important;` : ""}
            ${applyBtnBdShadowStyesTab ? applyBtnBdShadowStyesTab : ""}
            ${applyBtnPaddingTab ? applyBtnPaddingTab : ""}
        }
        .ej-job-list-item-inner .ej-job-apply-btn .ej-btn.ej-info-btn-light:hover {
            ${applyBtnBgColorH ? `background-color: ${applyBtnBgColorH} !important;` : ""}
            ${applyBtnTextColorH ? `color: ${applyBtnTextColorH} !important;` : ""}
            ${applyBtnBdShadowStylesHoverTab ? applyBtnBdShadowStylesHoverTab : ""}
        }
        .ej-template-classic .ej-job-list-classic .ej-job-list-item .job__apply .button__success.button__radius {
            ${applyBtnBgColor ? `background-color: ${applyBtnBgColor} !important;` : ""}
            ${applyBtnTextColor ? `color: ${applyBtnTextColor} !important;` : ""}
            ${applyBtnBdShadowStyesTab ? applyBtnBdShadowStyesTab : ""}
            ${applyBtnPaddingTab ? applyBtnPaddingTab : ""}
        }
        .ej-template-classic .ej-job-list-classic .ej-job-list-item .job__apply .button__success.button__radius:hover {
            ${applyBtnBgColorH ? `background-color: ${applyBtnBgColorH} !important;` : ""}
            ${applyBtnTextColorH ? `color: ${applyBtnTextColorH} !important;` : ""}
            ${applyBtnBdShadowStyesTab ? applyBtnBdShadowStyesTab : ""}
        }
        .ej-template-elegant .ej-job-list-elegant .ej-job-list-item-cat .job__apply .button.button__primary.radius-15 {
            ${applyBtnBgColor ? `background-color: ${applyBtnBgColor} !important;` : ""}
            ${applyBtnTextColor ? `color: ${applyBtnTextColor} !important;` : ""}
            ${applyBtnBdShadowStyesTab ? applyBtnBdShadowStyesTab : ""}
            ${applyBtnPaddingTab ? applyBtnPaddingTab : ""}
        }
        .ej-template-elegant .ej-job-list-elegant .ej-job-list-item-cat .job__apply .button.button__primary.radius-15:hover {
            ${applyBtnBgColorH ? `background-color: ${applyBtnBgColorH} !important;` : ""}
            ${applyBtnTextColorH ? `color: ${applyBtnTextColorH} !important;` : ""}
            ${applyBtnBdShadowStyesTab ? applyBtnBdShadowStyesTab : ""}
        }
	`;

    const mobileStyles = `
		.easyjobs-blocks {
            ${buttonPaddingMobile};
            ${wrapperMarginStylesMobile};
            ${bdShadowStyesMobile};
            transition: ${hoverTransition ? `all ${hoverTransition}s,` : ""
            } 
            ${btnBgTransitionStyle}, ${bdShadowTransitionStyle}; 
            ${btnBackgroundStylesMobile};
            ${contentAlign === 'center' ? `display: flex; justify-content: center;` : ''}
            ${contentAlign === 'left' ? `display: flex; justify-content: flex-start;` : ''}
            ${contentAlign === 'right' ? `display: flex; justify-content: flex-end;` : ''}
        }
        .easyjobs-shortcode-wrapper {
            ${buttonWidthMobile};
        }
        .easyjobs-blocks:hover {
            ${btnHoverBackgroundStylesMobile}
        }

        
        .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-section-title {
            ${jobListTitleMarginMobile};
            ${jobListTitlePaddingMobile};
        }
        .ej-section .ej-section-title .ej-section-title-text {
            ${jobListTitleColor ? `color: ${jobListTitleColor} !important;` : ""}
            ${textTypoStylesMobile ? textTypoStylesMobile : ''}
        }
		${jobListTitleIconWidth == "fixed"
            ? `.ej-section .ej-section-title .ej-section-title-icon {
                ${jobListTitleIconWidthMobile} !important
            }`
            : ""
        }
        ${jobListTitleIconHeight == "fixed"
            ? `.ej-section .ej-section-title .ej-section-title-icon {
                ${jobListTitleIconHeightMobile} !important
            }`
            : ""
        }
        ${jobListTitleIconSize == "fixed"
            ? `.ej-section .ej-section-title i.ej-section-title-icon {
                ${jobListTitleIconSizetMobile} !important
            }`
            : ""
        }
        .ej-section-title .ej-section-title-icon {
            ${jobListTitleIconBgColor ? `background-color: ${jobListTitleIconBgColor} !important;` : ""}
            ${jobListTitleIconColor ? `color: ${jobListTitleIconColor} !important;` : ""}
        }


        .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light {
            ${submitBgColor ? `background-color: ${submitBgColor} !important;` : ""}
            ${submitTextColor ? `color: ${submitTextColor} !important;` : ""}
            ${submitBtnTypoStylesMobile ? submitBtnTypoStylesMobile : ""}
        }
        .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light:hover {
            ${submitBgColorH ? `background-color: ${submitBgColorH} !important;` : ""}
            ${submitTextColorH ? `color: ${submitTextColorH} !important;` : ""}
        }
        .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn {
            ${resetBgColor ? `background-color: ${resetBgColor} !important;` : ""}
            ${resetTextColor ? `color: ${resetTextColor} !important;` : ""}
            ${resetBtnTypoStylesMobile ? resetBtnTypoStylesMobile : ""}
        }
        .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn:hover {
            ${resetBgColorH ? `background-color: ${resetBgColorH} !important;` : ""}
            ${resetTextColorH ? `color: ${resetTextColorH} !important;` : ""}
        }


        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item,
        .ej-template-elegant .ej-job-list-elegant .job__card {
            ${jobListBackgroundStylesMobile};
            ${jobListBdShadowStyesMobile};
            ${jobListPaddingMobile ? jobListPaddingMobile : ""}
            ${jobListMarginMobile ? jobListMarginMobile : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item:hover,
        .ej-template-elegant .ej-job-list-elegant .job__card:hover    {
            ${jobListHoverBackgroundStylesMobile}
            ${jobListBdShadowStylesHoverMobile};
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col {
            ${separatorColor ? `border-color: ${separatorColor} !important;` : ""}
        }
        .ej-section-content .ej-job-list .ej-job-title a,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__info h3 a,
        .ej-template-elegant .job__card h3 a {
            ${jobTitleTypoStylesMobile ? jobTitleTypoStylesMobile : ""}
            ${jobTitleColor ? `color: ${jobTitleColor} !important;` : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-title,
        .ej-template-elegant .job__card .ej-job-title {
            ${spaceBetweenTitleCompanyinMobile ? spaceBetweenTitleCompanyinMobile : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-info-block.ej-job-list-company-name a,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__info .meta a,
        .ej-template-elegant .job__card .meta a {
            ${companyNameColor ? `color: ${companyNameColor} !important;` : ""}
            ${companyNameTypoStylesMobile ? companyNameTypoStylesMobile : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-info-block.ej-job-list-location span,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__info .meta span span,
        .ej-template-elegant .job__card .meta span span {
            ${jobLocationColor ? `color: ${jobLocationColor} !important;` : ""}
            ${jobLocationTypoStylesMobile ? jobLocationTypoStylesMobile : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-deadline,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__apply .deadline span,
        .ej-template-elegant .job__card .deadline {
            ${jobDeadlineColor ? `color: ${jobDeadlineColor} !important;` : ""}
            ${jobDesdlineTypoStylesMobile ? jobDesdlineTypoStylesMobile : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-list-sub,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__vacancy h4,
        .easyjobs-shortcode-wrapper.ej-template-classic .job__card .job__vacancy p,
        .ej-template-elegant .job__card .job__bottom .job__vacancy h4,
        .ej-template-elegant .job__card .job__bottom .job__vacancy p {
            ${jobVacancyColor ? `color: ${jobVacancyColor} !important;` : ""}
            ${noOfJobsTypoStylesMobile ? noOfJobsTypoStylesMobile : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-list .ej-btn.ej-info-btn-light,
        .easyjobs-shortcode-wrapper.ej-template-classic .button__success,
        .ej-template-elegant .button.button__primary.radius-15 {
            ${applyBtnTypoStylesMobile ? applyBtnTypoStylesMobile : ""}
        }
        .easyjobs-shortcode-wrapper .ej-job-apply-btn {
            text-align: ${applyButtonAlign} !important
        }
        .ej-job-list-item-inner .ej-job-apply-btn .ej-btn.ej-info-btn-light {
            ${applyBtnBgColor ? `background-color: ${applyBtnBgColor} !important;` : ""}
            ${applyBtnTextColor ? `color: ${applyBtnTextColor} !important;` : ""}
            ${applyBtnBdShadowStyesMobile ? applyBtnBdShadowStyesMobile : ""}
            ${applyBtnPaddingMobile ? applyBtnPaddingMobile : ""}
        }
        .ej-job-list-item-inner .ej-job-apply-btn .ej-btn.ej-info-btn-light:hover {
            ${applyBtnBgColorH ? `background-color: ${applyBtnBgColorH} !important;` : ""}
            ${applyBtnTextColorH ? `color: ${applyBtnTextColorH} !important;` : ""}
            ${applyBtnBdShadowStylesHoverMobile ? applyBtnBdShadowStylesHoverMobile : ""}
        }
        .ej-template-classic .ej-job-list-classic .ej-job-list-item .job__apply .button__success.button__radius {
            ${applyBtnBgColor ? `background-color: ${applyBtnBgColor} !important;` : ""}
            ${applyBtnTextColor ? `color: ${applyBtnTextColor} !important;` : ""}
            ${applyBtnBdShadowStyesMobile ? applyBtnBdShadowStyesMobile : ""}
            ${applyBtnPaddingMobile ? applyBtnPaddingMobile : ""}
        }
        .ej-template-classic .ej-job-list-classic .ej-job-list-item .job__apply .button__success.button__radius:hover {
            ${applyBtnBgColorH ? `background-color: ${applyBtnBgColorH} !important;` : ""}
            ${applyBtnTextColorH ? `color: ${applyBtnTextColorH} !important;` : ""}
            ${applyBtnBdShadowStyesMobile ? applyBtnBdShadowStyesMobile : ""}
        }
        .ej-template-elegant .ej-job-list-elegant .ej-job-list-item-cat .job__apply .button.button__primary.radius-15 {
            ${applyBtnBgColor ? `background-color: ${applyBtnBgColor} !important;` : ""}
            ${applyBtnTextColor ? `color: ${applyBtnTextColor} !important;` : ""}
            ${applyBtnBdShadowStyesMobile ? applyBtnBdShadowStyesMobile : ""}
            ${applyBtnPaddingMobile ? applyBtnPaddingMobile : ""}
        }
        .ej-template-elegant .ej-job-list-elegant .ej-job-list-item-cat .job__apply .button.button__primary.radius-15:hover {
            ${applyBtnBgColorH ? `background-color: ${applyBtnBgColorH} !important;` : ""}
            ${applyBtnTextColorH ? `color: ${applyBtnTextColorH} !important;` : ""}
            ${applyBtnBdShadowStyesMobile ? applyBtnBdShadowStyesMobile : ""}
        }
	`;

    // all css styles for large screen width (desktop/laptop) in strings ⬇
    const desktopAllStyles = softMinifyCssStrings(`
			${desktopStyles}
		`);

    // all css styles for Tab in strings ⬇
    const tabAllStyles = softMinifyCssStrings(`
			${tabStyles}
		`);

    // all css styles for Mobile in strings ⬇
    const mobileAllStyles = softMinifyCssStrings(`
			${mobileStyles}
		`);

    return (
        <>
            <StyleComponent
                attributes={attributes}
                setAttributes={setAttributes}
                desktopAllStyles={desktopAllStyles}
                tabAllStyles={tabAllStyles}
                mobileAllStyles={mobileAllStyles}
                blockName={name}
            />
        </>
    );
}