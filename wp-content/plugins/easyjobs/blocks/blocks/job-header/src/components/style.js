import {
    WEBSITE_LINK_BTN_BG,
    INFO_WIDTH,
    INFO_MARGIN,
    WEBSITE_LINK_BTN_BDR_SHADOW,
    WEBSITE_LINK_BTN_PADDING,
    INFO_BACKGROUND,
    INFO_PADDING,
    INFO_BOX_SHADOW,
} from "./constants";

import { 
    TypoprefixCompanyName, 
    TypoprefixLocationName, 
    TypoprefixWebsiteLink, 
    TypoprefixDescription, 
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
        companyNameColor,
        locationNameColor,
        websiteLinkBtnColor,
        websiteLinkBtnColorHvr,
        descriptionColor,
        infoAlign,
        descriptionAlign,
    } = attributes;

    /**
     * CSS/styling Codes Starts from Here
     */

    const {
        rangeStylesDesktop: infoWidthDesktop,
        rangeStylesTab: infoWidthTab,
        rangeStylesMobile: infoWidthMobile,
    } = generateResponsiveRangeStyles({
        controlName: INFO_WIDTH,
        property: "width",
        attributes,
    });

    // button custom padding
    const {
        dimensionStylesDesktop: websiteButtonPaddingDesktop,
        dimensionStylesTab: websiteButtonPaddingTab,
        dimensionStylesMobile: websiteButtonPaddingMobile,
    } = generateDimensionsControlStyles({
        controlName: WEBSITE_LINK_BTN_PADDING,
        styleFor: "padding",
        attributes,
    });

    const {
        dimensionStylesDesktop: infoMarginDesktop,
        dimensionStylesTab: infoMarginTab,
        dimensionStylesMobile: infoMarginMobile,
    } = generateDimensionsControlStyles({
        controlName: INFO_MARGIN,
        styleFor: "margin",
        attributes,
    });
    const {
        dimensionStylesDesktop: infoPaddingDesktop,
        dimensionStylesTab: infoPaddingTab,
        dimensionStylesMobile: infoPaddingMobile,
    } = generateDimensionsControlStyles({
        controlName: INFO_PADDING,
        styleFor: "padding",
        attributes,
    });

    // button background styles
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
        controlName: WEBSITE_LINK_BTN_BG,
    });

    // info background styles
    const {
        backgroundStylesDesktop: infoBackgroundStylesDesktop,
        hoverBackgroundStylesDesktop: infoHoverBackgroundStylesDesktop,
        backgroundStylesTab: infoBackgroundStylesTab,
        hoverBackgroundStylesTab: infoHoverBackgroundStylesTab,
        backgroundStylesMobile: infoBackgroundStylesMobile,
        hoverBackgroundStylesMobile: infoHoverBackgroundStylesMobile,
        bgTransitionStyle: infoBgTransitionStyle,
    } = generateBackgroundControlStyles({
        attributes,
        controlName: INFO_BACKGROUND,
    });

    // border
    const {
        styesDesktop: websiteBtnbdShadowStyesDesktop,
        styesTab: websiteBtnbdShadowStyesTab,
        styesMobile: websiteBtnbdShadowStyesMobile,
        stylesHoverDesktop: websiteBtnbdShadowStylesHoverDesktop,
        stylesHoverTab: websiteBtnbdShadowStylesHoverTab,
        stylesHoverMobile: websiteBtnbdShadowStylesHoverMobile,
        transitionStyle: websiteBtnbdShadowTransitionStyle,
    } = generateBorderShadowStyles({
        controlName: WEBSITE_LINK_BTN_BDR_SHADOW,
        attributes,
    });
    const {
        styesDesktop: infoSectionBdShadowStyesDesktop,
        styesTab: infoSectionBdShadowStyesTab,
        styesMobile: infoSectionBdShadowStyesMobile,
        stylesHoverDesktop: infoSectionBdShadowStylesHoverDesktop,
        stylesHoverTab: infoSectionBdShadowStylesHoverTab,
        stylesHoverMobile: infoSectionBdShadowStylesHoverMobile,
        transitionStyle: infoSectionBdShadowTransitionStyle,
    } = generateBorderShadowStyles({
        controlName: INFO_BOX_SHADOW,
        attributes,
    });

    // company name typography
    const {
        typoStylesDesktop: nameTextTypoStylesDesktop,
        typoStylesTab: nameTextTypoStylesTab,
        typoStylesMobile: nameTextTypoStylesMobile,
    } = generateTypographyStyles({
        attributes,
        prefixConstant: TypoprefixCompanyName,
    });

    // location typography
    const {
        typoStylesDesktop: locationTextTypoStylesDesktop,
        typoStylesTab: locationTextTypoStylesTab,
        typoStylesMobile: locationTextTypoStylesMobile,
    } = generateTypographyStyles({
        attributes,
        prefixConstant: TypoprefixLocationName,
    });

    // website link button typography
    const {
        typoStylesDesktop: websiteTextTypoStylesDesktop,
        typoStylesTab: websiteTextTypoStylesTab,
        typoStylesMobile: websiteTextTypoStylesMobile,
    } = generateTypographyStyles({
        attributes,
        prefixConstant: TypoprefixWebsiteLink,
    });

    // description typography
    const {
        typoStylesDesktop: descriptionTextTypoStylesDesktop,
        typoStylesTab: descriptionTextTypoStylesTab,
        typoStylesMobile: descriptionTextTypoStylesMobile,
    } = generateTypographyStyles({
        attributes,
        prefixConstant: TypoprefixDescription,
    });

    const desktopStyles = `
        .easyjobs-block-info {
            ${infoAlign === 'center' ? `display: flex; justify-content: center;` : ''}
            ${infoAlign === 'left' ? `display: flex; justify-content: flex-start;` : ''}
            ${infoAlign === 'right' ? `display: flex; justify-content: flex-end;` : ''}
            ${infoSectionBdShadowStyesDesktop}
        }
        .easyjobs-block-info:hover {
            ${infoSectionBdShadowStylesHoverDesktop}
        } 
        .easyjobs-block-info .easyjobs-shortcode-wrapper {
            ${infoBackgroundStylesDesktop};
            ${infoBgTransitionStyle};
            ${infoWidthDesktop};
            ${infoMarginDesktop}
            ${infoPaddingDesktop}
        }
        .easyjobs-block-info .easyjobs-shortcode-wrapper:hover {
            ${infoHoverBackgroundStylesDesktop}
            ${infoBgTransitionStyle}
        }   
        .ej-company-info .info .name,
        .ej-template-elegant .about__company .ej-company-info .info .name a {
            ${companyNameColor ? `color: ${companyNameColor};` : ""}
            ${nameTextTypoStylesDesktop ? nameTextTypoStylesDesktop : ''}
        }
        .ej-company-info .info .location,
        .ej-template-elegant .about__company .ej-company-info .info .location,
        .easyjobs-shortcode-wrapper.ej-template-classic .label__primary {
            ${locationNameColor ? `color: ${locationNameColor};` : ""}
            ${locationTextTypoStylesDesktop ? locationTextTypoStylesDesktop : ''}
        }
        .ej-header .ej-company-highlights .ej-header-tools .ej-info-btn,
        .ej-template-elegant.ej-template-elegant .ej-header-tools .ej-info-btn,
        .easyjobs-shortcode-wrapper.ej-template-classic .carrier__company .button {
            ${websiteLinkBtnColor ? `color: ${websiteLinkBtnColor};` : ""}
            ${websiteTextTypoStylesDesktop ? websiteTextTypoStylesDesktop : ''}
            ${btnBackgroundStylesDesktop}
            ${websiteBtnbdShadowStyesDesktop}
            ${websiteButtonPaddingDesktop}
        }
        .ej-header .ej-company-highlights .ej-header-tools .ej-info-btn:hover,
        .easyjobs-shortcode-wrapper.ej-template-elegant.ej-template-elegant .ej-header-tools .ej-btn.ej-info-btn:hover,
        .easyjobs-shortcode-wrapper.ej-template-classic .carrier__company .button:hover {
            ${websiteLinkBtnColorHvr ? `color: ${websiteLinkBtnColorHvr};` : ""}
            ${btnHoverBackgroundStylesDesktop}
            ${websiteBtnbdShadowStylesHoverDesktop}
        }
        .ej-company-description, .ej-company-description h2, .ej-company-description p, .ej-company-description ul li, .ej-company-description ol li, .ej-company-description blockquote,
        .easyjobs-shortcode-wrapper.ej-template-classic .ej-company-description p,
        .ej-template-elegant .ej-company-description p {
            ${descriptionAlign ? `text-align: ${descriptionAlign};` : ''}
            ${descriptionColor ? `color: ${descriptionColor};` : ""}
            ${descriptionTextTypoStylesDesktop ? descriptionTextTypoStylesDesktop : ''}
        }
	`;

    const tabStyles = `
		.easyjobs-block-info {
            ${infoAlign === 'center' ? `display: flex; justify-content: center;` : ''}
            ${infoAlign === 'left' ? `display: flex; justify-content: flex-start;` : ''}
            ${infoAlign === 'right' ? `display: flex; justify-content: flex-end;` : ''}
            ${infoSectionBdShadowStyesTab}
        }
        .easyjobs-block-info:hover {
            ${infoSectionBdShadowStylesHoverTab}
        } 
        .easyjobs-block-info .easyjobs-shortcode-wrapper {
            ${infoBackgroundStylesTab};
            ${infoBgTransitionStyle};
            ${infoWidthTab};
            ${infoMarginTab}
            ${infoPaddingTab}
        }
        .easyjobs-block-info .easyjobs-shortcode-wrapper:hover {
            ${infoHoverBackgroundStylesTab}
            ${infoBgTransitionStyle}
        }   
        .ej-company-info .info .name,
        .ej-template-elegant .about__company .ej-company-info .info .name a {
            ${companyNameColor ? `color: ${companyNameColor};` : ""}
            ${nameTextTypoStylesTab ? nameTextTypoStylesTab : ''}
        }
        .ej-company-info .info .location,
        .ej-template-elegant .about__company .ej-company-info .info .location,
        .easyjobs-shortcode-wrapper.ej-template-classic .label__primary {
            ${locationNameColor ? `color: ${locationNameColor};` : ""}
            ${locationTextTypoStylesTab ? locationTextTypoStylesTab : ''}
        }
        .ej-header .ej-company-highlights .ej-header-tools .ej-info-btn,
        .ej-template-elegant.ej-template-elegant .ej-header-tools .ej-info-btn,
        .easyjobs-shortcode-wrapper.ej-template-classic .carrier__company .button {
            ${websiteLinkBtnColor ? `color: ${websiteLinkBtnColor};` : ""}
            ${websiteTextTypoStylesTab ? websiteTextTypoStylesTab : ''}
            ${btnBackgroundStylesTab}
            ${websiteBtnbdShadowStyesTab}
            ${websiteButtonPaddingTab}
        }
        .ej-header .ej-company-highlights .ej-header-tools .ej-info-btn:hover,
        .easyjobs-shortcode-wrapper.ej-template-elegant.ej-template-elegant .ej-header-tools .ej-btn.ej-info-btn:hover,
        .easyjobs-shortcode-wrapper.ej-template-classic .carrier__company .button:hover {
            ${websiteLinkBtnColorHvr ? `color: ${websiteLinkBtnColorHvr};` : ""}
            ${btnHoverBackgroundStylesTab}
            ${websiteBtnbdShadowStylesHoverTab}
        }
        .ej-company-description, .ej-company-description h2, .ej-company-description p, .ej-company-description ul li, .ej-company-description ol li, .ej-company-description blockquote,
        .easyjobs-shortcode-wrapper.ej-template-classic .ej-company-description p,
        .ej-template-elegant .ej-company-description p {
            ${descriptionAlign ? `text-align: ${descriptionAlign};` : ''}
            ${descriptionColor ? `color: ${descriptionColor};` : ""}
            ${descriptionTextTypoStylesTab ? descriptionTextTypoStylesTab : ''}
        }
	`;

    const mobileStyles = `
		.easyjobs-block-info {
            ${infoAlign === 'center' ? `display: flex; justify-content: center;` : ''}
            ${infoAlign === 'left' ? `display: flex; justify-content: flex-start;` : ''}
            ${infoAlign === 'right' ? `display: flex; justify-content: flex-end;` : ''}
            ${infoSectionBdShadowStyesMobile}
        }
        .easyjobs-block-info:hover {
            ${infoSectionBdShadowStylesHoverMobile}
        } 
        .easyjobs-block-info .easyjobs-shortcode-wrapper {
            ${infoBackgroundStylesMobile};
            ${infoBgTransitionStyle};
            ${infoWidthMobile};
            ${infoMarginMobile}
            ${infoPaddingMobile}
        }
        .easyjobs-block-info .easyjobs-shortcode-wrapper:hover {
            ${infoHoverBackgroundStylesMobile}
            ${infoBgTransitionStyle}
        }   
        .ej-company-info .info .name,
        .ej-template-elegant .about__company .ej-company-info .info .name a {
            ${companyNameColor ? `color: ${companyNameColor};` : ""}
            ${nameTextTypoStylesMobile ? nameTextTypoStylesMobile : ''}
        }
        .ej-company-info .info .location,
        .ej-template-elegant .about__company .ej-company-info .info .location,
        .easyjobs-shortcode-wrapper.ej-template-classic .label__primary {
            ${locationNameColor ? `color: ${locationNameColor};` : ""}
            ${locationTextTypoStylesMobile ? locationTextTypoStylesMobile : ''}
        }
        .ej-header .ej-company-highlights .ej-header-tools .ej-info-btn,
        .ej-template-elegant.ej-template-elegant .ej-header-tools .ej-info-btn,
        .easyjobs-shortcode-wrapper.ej-template-classic .carrier__company .button {
            ${websiteLinkBtnColor ? `color: ${websiteLinkBtnColor};` : ""}
            ${websiteTextTypoStylesMobile ? websiteTextTypoStylesMobile : ''}
            ${btnBackgroundStylesMobile}
            ${websiteBtnbdShadowStyesMobile}
            ${websiteButtonPaddingMobile}
        }
        .ej-header .ej-company-highlights .ej-header-tools .ej-info-btn:hover,
        .easyjobs-shortcode-wrapper.ej-template-elegant.ej-template-elegant .ej-header-tools .ej-btn.ej-info-btn:hover,
        .easyjobs-shortcode-wrapper.ej-template-classic .carrier__company .button:hover {
            ${websiteLinkBtnColorHvr ? `color: ${websiteLinkBtnColorHvr};` : ""}
            ${btnHoverBackgroundStylesMobile}
            ${websiteBtnbdShadowStylesHoverMobile}
        }
        .ej-company-description, .ej-company-description h2, .ej-company-description p, .ej-company-description ul li, .ej-company-description ol li, .ej-company-description blockquote,
        .easyjobs-shortcode-wrapper.ej-template-classic .ej-company-description p,
        .ej-template-elegant .ej-company-description p {
            ${descriptionAlign ? `text-align: ${descriptionAlign};` : ''}
            ${descriptionColor ? `color: ${descriptionColor};` : ""}
            ${descriptionTextTypoStylesMobile ? descriptionTextTypoStylesMobile : ''}
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