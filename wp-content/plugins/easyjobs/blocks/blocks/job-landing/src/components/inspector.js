/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { InspectorControls} from "@wordpress/block-editor";
import {
    PanelBody,
    ToggleControl,
    TabPanel,
    Card,
    CardBody,
} from "@wordpress/components";

/**
 * Internal depencencies
 */
import DocLink from "../../../helpers/DocLink";

const Inspector = ({ attributes, setAttributes, clientId }) => {
    const {
        hideJobHeader,
        hideJobList,
        hideJobFooter
    } = attributes;

    const handleHideJobHeader = () => {
        setAttributes({
            hideJobHeader: !hideJobHeader,
        });
        if (hideJobHeader) {

        }
    }

    const handleHideJobList = () => {
        setAttributes({
            hideJobList: !hideJobList,
        });
    }

    const handleHideJobFooter = () => {
        setAttributes({
            hideJobFooter: !hideJobFooter,
        });
    }

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
                                                "Hide Job Header",
                                                "easyjobs"
                                            )}
                                            checked={hideJobHeader}
                                            onChange={handleHideJobHeader}
                                        />
										<ToggleControl
                                            label={__(
                                                "Hide Job List",
                                                "easyjobs"
                                            )}
                                            checked={hideJobList}
                                            onChange={handleHideJobList}
                                        />
                                        <ToggleControl
                                            label={__(
                                                "Hide Job Footer",
                                                "easyjobs"
                                            )}
                                            checked={hideJobFooter}
                                            onChange={handleHideJobFooter}
                                        />
                                    </PanelBody>
                                </>
                            )}
                            {tab.name === "styles" && (
                                <>
                                   <PanelBody
                                        title={__(
                                            "Cobined Block",
                                            "easyjobs"
                                        )}
                                        initialOpen={true}
                                    >
                                        <Card>
                                            <CardBody>
                                                <p>
                                                Please select the Specific Block for Style Customization.
                                                </p>
                                            </CardBody>
                                        </Card>
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
            <DocLink link={'https://easy.jobs/docs/how-to-design-company-career-page-with-gutenberg/'} />
        </InspectorControls>
    );
};

export default Inspector;