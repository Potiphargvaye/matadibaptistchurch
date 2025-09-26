import { 
    TypoprefixGalleryTitle 
} from "./typographyConstants";

const {
    softMinifyCssStrings,
    generateTypographyStyles,
    StyleComponent
} = window.EJControls;

export default function Style(props) {
    const { attributes, setAttributes, name } = props;
    const {
        galleryTitleColor
    } = attributes;

    /**
     * CSS/styling Codes Starts from Here
     */

    // company name typography
    const {
        typoStylesDesktop: galleryTitleTypoStylesDesktop,
        typoStylesTab: galleryTitleTypoStylesTab,
        typoStylesMobile: galleryTitleTypoStylesMobile,
    } = generateTypographyStyles({
        attributes,
        prefixConstant: TypoprefixGalleryTitle,
    });

    const desktopStyles = `
        .easyjobs-shortcode-wrapper .ej-section-title .ej-section-title-text,
        .easyjobs-shortcode-wrapper .ej-section-title h2.ej-section-title-text,
        .easyjobs-shortcode-wrapper.ej-template-elegant .section__header h2 {
            ${galleryTitleColor ? `color: ${galleryTitleColor};` : ""}
            ${galleryTitleTypoStylesDesktop ? galleryTitleTypoStylesDesktop : ''}
        }
	`;

    const tabStyles = `
		.easyjobs-shortcode-wrapper .ej-section-title .ej-section-title-text,
        .easyjobs-shortcode-wrapper .ej-section-title h2.ej-section-title-text,
        .easyjobs-shortcode-wrapper.ej-template-elegant .section__header h2 {
            ${galleryTitleTypoStylesTab ? galleryTitleTypoStylesTab : ''}
        }
	`;

    const mobileStyles = `
		.easyjobs-shortcode-wrapper .ej-section-title .ej-section-title-text,
        .easyjobs-shortcode-wrapper .ej-section-title h2.ej-section-title-text,
        .easyjobs-shortcode-wrapper.ej-template-elegant .section__header h2 {
            ${galleryTitleTypoStylesMobile ? galleryTitleTypoStylesMobile : ''}
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