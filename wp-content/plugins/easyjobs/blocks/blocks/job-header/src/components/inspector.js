/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { InspectorControls, MediaUpload } from "@wordpress/block-editor";
import {
    PanelBody,
    ToggleControl,
    TabPanel,
    BaseControl,
    __experimentalDivider as Divider,
    ButtonGroup,
    Button,
} from "@wordpress/components";

/**
 * Internal depencencies
 */
import {
    CONTENT_POSITION,
    INFO_WIDTH,
    INFO_MARGIN,
    INFO_PADDING,
    INFO_BOX_SHADOW,
    WEBSITE_LINK_BTN_BG,
    WEBSITE_LINK_BTN_BDR_SHADOW,
    WEBSITE_LINK_BTN_PADDING,
    DESCRIPTION_POSITION,
    INFO_BACKGROUND,
} from "./constants";

import { 
    TypoprefixCompanyName, 
    TypoprefixLocationName, 
    TypoprefixWebsiteLink, 
    TypoprefixDescription, 
} from "./typographyContants";

import objAttributes from "./attributes";
import DocLink from "../../../helpers/DocLink";

const {
    ColorControl,
    DynamicInputControl,
    ImageAvatar,
    BackgroundControl,
    ResponsiveRangeController,
    ResponsiveDimensionsControl,
    TypographyDropdown,
    BorderShadowControl,
} = window.EJControls;

const Inspector = ({ attributes, setAttributes }) => {
    const {
        resOption,
        changeCoverImage,
        changeLogoImage,
        companyName,
        websiteLinkText,
        coverImgUrl,
        coverImgId,
        logoImgUrl,
        logoImgId,
        infoAlign,
        descriptionAlign,
        companyNameColor,
        locationNameColor,
        websiteLinkBtnColor,
        websiteLinkBtnColorHvr,
        descriptionColor,

    } = attributes;
    
    const resRequiredProps = {
        setAttributes,
        resOption,
        attributes,
        objAttributes,
    };

    return (
        <InspectorControls key="controls">
            <div className="eb-panel-control">
                <TabPanel
                    className="eb-parent-tab-panel"
                    activeClass="active-tab"
                    tabs={[
                        {
                            name: "content",
                            title: __("Content", "easyjobs"),
                            className: "eb-tab general",
                        },
                        {
                            name: "styles",
                            title: __("Style", "easyjobs"),
                            className: "eb-tab styles",
                        },
                        {
                            name: "advance",
                            title: __("Advanced", "easyjobs"),
                            className: "eb-tab advance",
                        },
                    ]}
                >
                    {(tab) => (
                        <div className={"eb-tab-controls " + tab.name}>
                            {tab.name === "content" && (
                                <>
                                    <PanelBody
                                        title={__(
                                            "EasyJobs",
                                            "easyjobs"
                                        )}
                                    >
                                        <ToggleControl
                                            label={__(
                                                "Change Cover Image",
                                                "easyjobs"
                                            )}
                                            checked={changeCoverImage}
                                            onChange={() =>
                                                setAttributes({
                                                    changeCoverImage: !changeCoverImage,
                                                    coverImgUrl: null
                                                })
                                            }
                                        />
                                        { ! coverImgUrl && changeCoverImage && (
                                            <MediaUpload
                                                onSelect={({ id, url, alt }) =>
                                                    setAttributes({
                                                        coverImgUrl: url,
                                                        coverImgId: id,
                                                        coverImgAlt: alt,
                                                    })
                                                }
                                                type="image"
                                                value={coverImgId}
                                                render={({ open }) => {
                                                    return (
                                                        <Button
                                                            className="eb-background-control-inspector-panel-img-btn components-button"
                                                            label={__("Upload Image", "easyjobs")}
                                                            icon="format-image"
                                                            onClick={open}
                                                        />
                                                    );
                                                }}
                                            />
                                        )}
                                        {coverImgUrl && changeCoverImage && (
                                            <ImageAvatar
                                                imageUrl={coverImgUrl}
                                                onDeleteImage={() =>
                                                    setAttributes({
                                                        coverImgUrl: null,
                                                    })
                                                }
                                            />
                                        )}
										<ToggleControl
                                            label={__(
                                                "Change Logo",
                                                "easyjobs"
                                            )}
                                            checked={changeLogoImage}
                                            onChange={() =>
                                                setAttributes({
                                                    changeLogoImage: !changeLogoImage,
                                                    logoImgUrl: null,
                                                })
                                            }
                                        />
                                        { ! logoImgUrl && changeLogoImage && (
                                            <MediaUpload
                                                onSelect={({ id, url, alt }) =>
                                                    setAttributes({
                                                        logoImgUrl: url,
                                                        logoImgId: id,
                                                        logoImgAlt: alt,
                                                    })
                                                }
                                                type="image"
                                                value={logoImgId}
                                                render={({ open }) => {
                                                    return (
                                                        <Button
                                                            className="eb-background-control-inspector-panel-img-btn components-button"
                                                            label={__("Upload Image", "easyjobs")}
                                                            icon="format-image"
                                                            onClick={open}
                                                        />
                                                    );
                                                }}
                                            />
                                        )}
                                        {logoImgUrl && changeLogoImage && (
                                            <ImageAvatar
                                                imageUrl={logoImgUrl}
                                                onDeleteImage={() =>
                                                    setAttributes({
                                                        logoImgUrl: null,
                                                    })
                                                }
                                            />
                                        )}
                                    </PanelBody>
                                    <PanelBody
                                        title={__(
                                            "Text change",
                                            "easyjobs"
                                        )}
                                        initialOpen={false}
                                    >
                                        <DynamicInputControl
                                            label="Company Name"
                                            attrName="companyName"
                                            inputValue={companyName}
                                            setAttributes={setAttributes}
                                            onChange={(text) => setAttributes({ companyName: text })}
                                        />
                                        <DynamicInputControl
                                            label="Website Link Text"
                                            attrName="websiteLinkText"
                                            inputValue={websiteLinkText}
                                            setAttributes={setAttributes}
                                            onChange={(text) => setAttributes({ websiteLinkText: text })}
                                        />
                                    </PanelBody>
                                </>
                            )}
                            {tab.name === "styles" && (
                                <>
                                    <PanelBody
                                        title={__("General", "easyjobs")}
                                        initialOpen={false}
                                    >
                                        <BaseControl>
                                            <h3 className="eb-control-title">
                                                {__(
                                                    "Background",
                                                    "easyjobs"
                                                )}
                                            </h3>
                                        </BaseControl>
                                        <BackgroundControl
                                            controlName={INFO_BACKGROUND}
                                            resRequiredProps={
                                                resRequiredProps
                                            }
                                            noOverlay={true}
                                            noMainBgi={true}
                                        />
                                        <Divider />
                                        <BaseControl
                                            label={__(
                                                "Alignment",
                                                "easyjobs"
                                            )}
                                        >
                                            <ButtonGroup id="eb-button-group-alignment">
                                                {CONTENT_POSITION.map(
                                                    (item, index) => (
                                                        <Button
                                                            key={index}
                                                            isPrimary={
                                                                infoAlign ===
                                                                item.value
                                                            }
                                                            isSecondary={
                                                                infoAlign !==
                                                                item.value
                                                            }
                                                            onClick={() =>
                                                                setAttributes({
                                                                    infoAlign:
                                                                        item.value,
                                                                })
                                                            }
                                                        >
                                                            {item.label}
                                                        </Button>
                                                    )
                                                )}
                                            </ButtonGroup>
                                        </BaseControl>
                                        <Divider />
                                        <ResponsiveRangeController
                                            baseLabel={__(
                                                "Width",
                                                "easyjobs"
                                            )}
                                            controlName={INFO_WIDTH}
                                            resRequiredProps={
                                                resRequiredProps
                                            }
                                            min={0}
                                            max={100}
                                            step={1}
                                        />
                                        <ResponsiveDimensionsControl
                                            resRequiredProps={
                                                resRequiredProps
                                            }
                                            controlName={INFO_MARGIN}
                                            baseLabel={__(
                                                "Margin",
                                                "easyjobs"
                                            )}
                                        />
                                        <ResponsiveDimensionsControl
                                            resRequiredProps={
                                                resRequiredProps
                                            }
                                            controlName={INFO_PADDING}
                                            baseLabel={__(
                                                "Padding",
                                                "easyjobs"
                                            )}
                                        />
                                        <Divider />
                                        <BorderShadowControl
                                            controlName={INFO_BOX_SHADOW}
                                            resRequiredProps={
                                                resRequiredProps
                                            }
                                            noBorder={true}
                                        />
                                    </PanelBody>
                                    <PanelBody
                                        title={__("Company Info", "easyjobs")}
                                        initialOpen={false}
                                    >
                                        <BaseControl>
                                            <h3 className="eb-control-title">
                                                {__(
                                                    "Company Name",
                                                    "easyjobs"
                                                )}
                                            </h3>
                                        </BaseControl>
                                        <ColorControl
                                            label={__(
                                                "Color",
                                                "easyjobs"
                                            )}
                                            color={companyNameColor}
                                            onChange={(newTextColor) =>
                                                setAttributes({
                                                    companyNameColor: newTextColor,
                                                })
                                            }
                                        />
                                        <TypographyDropdown
                                            baseLabel={__(
                                                "Typography",
                                                "easyjobs"
                                            )}
                                            typographyPrefixConstant={
                                                TypoprefixCompanyName
                                            }
                                            resRequiredProps={
                                                resRequiredProps
                                            }
                                        />
                                        <Divider />
                                        <BaseControl>
                                            <h3 className="eb-control-title">
                                                {__(
                                                    "Location",
                                                    "easyjobs"
                                                )}
                                            </h3>
                                        </BaseControl>
                                        <ColorControl
                                            label={__(
                                                "Color",
                                                "easyjobs"
                                            )}
                                            color={locationNameColor}
                                            onChange={(newTextColor) =>
                                                setAttributes({
                                                    locationNameColor: newTextColor,
                                                })
                                            }
                                        />
                                        <TypographyDropdown
                                            baseLabel={__(
                                                "Typography",
                                                "easyjobs"
                                            )}
                                            typographyPrefixConstant={
                                                TypoprefixLocationName
                                            }
                                            resRequiredProps={
                                                resRequiredProps
                                            }
                                        />
                                        <Divider />
                                        <BaseControl>
                                            <h3 className="eb-control-title">
                                                {__(
                                                    "Website Link Button",
                                                    "easyjobs"
                                                )}
                                            </h3>
                                        </BaseControl>
                                        <ColorControl
                                            label={__(
                                                "Color",
                                                "easyjobs"
                                            )}
                                            color={websiteLinkBtnColor}
                                            onChange={(newTextColor) =>
                                                setAttributes({
                                                    websiteLinkBtnColor: newTextColor,
                                                })
                                            }
                                        />
                                        <ColorControl
                                            label={__(
                                                "Hover Color",
                                                "easyjobs"
                                            )}
                                            color={websiteLinkBtnColorHvr}
                                            onChange={(newTextColor) =>
                                                setAttributes({
                                                    websiteLinkBtnColorHvr: newTextColor,
                                                })
                                            }
                                        />
                                        <TypographyDropdown
                                            baseLabel={__(
                                                "Typography",
                                                "easyjobs"
                                            )}
                                            typographyPrefixConstant={
                                                TypoprefixWebsiteLink
                                            }
                                            resRequiredProps={
                                                resRequiredProps
                                            }
                                        />
                                        <BackgroundControl
                                            controlName={WEBSITE_LINK_BTN_BG}
                                            resRequiredProps={
                                                resRequiredProps
                                            }
                                            noOverlay={true}
                                            noMainBgi={true}
                                        />
                                        <Divider />
                                        <BorderShadowControl
                                            controlName={WEBSITE_LINK_BTN_BDR_SHADOW}
                                            resRequiredProps={
                                                resRequiredProps
                                            }
                                        />
                                        <Divider />
                                        <ResponsiveDimensionsControl
                                            resRequiredProps={
                                                resRequiredProps
                                            }
                                            controlName={WEBSITE_LINK_BTN_PADDING}
                                            baseLabel={__(
                                                "Padding",
                                                "easyjobs"
                                            )}
                                        />
                                        <Divider />
                                        <BaseControl>
                                            <h3 className="eb-control-title">
                                                {__(
                                                    "Description",
                                                    "easyjobs"
                                                )}
                                            </h3>
                                        </BaseControl>
                                        <BaseControl
                                            label={__(
                                                "Alignment",
                                                "easyjobs"
                                            )}
                                        >
                                            <ButtonGroup id="eb-button-group-alignment">
                                                {DESCRIPTION_POSITION.map(
                                                    (item, index) => (
                                                        <Button
                                                            key={index}
                                                            isPrimary={
                                                                descriptionAlign ===
                                                                item.value
                                                            }
                                                            isSecondary={
                                                                descriptionAlign !==
                                                                item.value
                                                            }
                                                            onClick={() =>
                                                                setAttributes({
                                                                    descriptionAlign:
                                                                        item.value,
                                                                })
                                                            }
                                                        >
                                                            {item.label}
                                                        </Button>
                                                    )
                                                )}
                                            </ButtonGroup>
                                        </BaseControl>
                                        <ColorControl
                                            label={__(
                                                "Color",
                                                "easyjobs"
                                            )}
                                            color={descriptionColor}
                                            onChange={(newTextColor) =>
                                                setAttributes({
                                                    descriptionColor: newTextColor,
                                                })
                                            }
                                        />
                                        <TypographyDropdown
                                            baseLabel={__(
                                                "Typography",
                                                "easyjobs"
                                            )}
                                            typographyPrefixConstant={
                                                TypoprefixDescription
                                            }
                                            resRequiredProps={
                                                resRequiredProps
                                            }
                                        />
                                        <Divider />
                                    </PanelBody>
                                </>
                            )}
                            {/* {tab.name === "advance" && (
                                <>
                                    <AdvancedControls
                                        attributes={attributes}
                                        setAttributes={setAttributes}
                                    />
                                </>
                            )} */}
                        </div>
                    )}
                </TabPanel>
            </div>
            <DocLink link={'https://easy.jobs/docs/design-company-profile-in-gutenberg-with-easy-jobs/'} />
        </InspectorControls>
    );
};

export default Inspector;