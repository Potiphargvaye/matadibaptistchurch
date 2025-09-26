/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { InspectorControls } from "@wordpress/block-editor";
import {
    PanelBody,
    TabPanel,
    BaseControl,
    Card,
    CardBody,
    ExternalLink,
} from "@wordpress/components";

/**
 * Internal depencencies
 */
import { 
    TypoprefixGalleryTitle
 } from "./typographyConstants";

import objAttributes from "./attributes";
import DocLink from "../../../helpers/DocLink";

const {
    ColorControl,
    DynamicInputControl,
    TypographyDropdown,
} = window.EJControls;

const Inspector = ({ attributes, setAttributes, companyDetails }) => {
    const {
        resOption,
        lifeAtTitle,
        galleryTitleColor,
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
                                    {( !companyDetails?.show_life ) ? (
                                        <PanelBody
                                            title={__(
                                                "Company Gallery",
                                                "easyjobs"
                                            )}
                                            initialOpen={true}
                                        >
                                            <Card>
                                                <CardBody>
                                                    <p>
                                                        Please make sure to enable the "Show on Company Page" from
                                                        <ExternalLink
                                                            href={`${EasyJobsLocalize.ej_admin_url}admin.php?page=easyjobs-settings`}
                                                        >
                                                            {__(" here.")}
                                                        </ExternalLink>
                                                    </p>
                                                </CardBody>
                                            </Card>
                                        </PanelBody>
                                    ) : (
                                        <PanelBody
                                            title={__(
                                                "Text change",
                                                "easyjobs"
                                            )}
                                            initialOpen={true}
                                        >
                                            <DynamicInputControl
                                                label="Gallery Section Title"
                                                attrName="lifeAtTitle"
                                                inputValue={lifeAtTitle}
                                                setAttributes={setAttributes}
                                                onChange={(text) => setAttributes({ lifeAtTitle: text })}
                                            />
                                        </PanelBody>
                                    )}
                                </>
                            )}
                            {tab.name === "styles" && (
                                <>
                                    <PanelBody
                                        title={__("Gallery", "easyjobs")}
                                        initialOpen={true}
                                    >
                                        <BaseControl>
                                            <h3 className="eb-control-title">
                                                {__(
                                                    "Gallery Title",
                                                    "easyjobs"
                                                )}
                                            </h3>
                                        </BaseControl>
                                        <ColorControl
                                            label={__(
                                                "Color",
                                                "easyjobs"
                                            )}
                                            color={galleryTitleColor}
                                            onChange={(newTextColor) =>
                                                setAttributes({
                                                    galleryTitleColor: newTextColor,
                                                })
                                            }
                                        />
                                        <TypographyDropdown
                                            baseLabel={__(
                                                "Typography",
                                                "easyjobs"
                                            )}
                                            typographyPrefixConstant={
                                                TypoprefixGalleryTitle
                                            }
                                            resRequiredProps={
                                                resRequiredProps
                                            }
                                        />
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
            <DocLink link={'https://easy.jobs/docs/showcase-company-gallery-using-gutenberg/'} />
        </InspectorControls>
    );
};

export default Inspector;