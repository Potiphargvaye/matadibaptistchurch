/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { InspectorControls } from "@wordpress/block-editor";
import {
    PanelBody,
    Button,
    ButtonGroup,
    BaseControl,
    ToggleControl,
    SelectControl,
    TabPanel,
    RangeControl,
    __experimentalDivider as Divider,
} from "@wordpress/components";

/**
 * Internal depencencies
 */
import {
    ORDER_BY,
    SORT_BY,
    BLOCK_BACKGROUND,
    CONTENT_POSITION,
    BLOCK_WIDTH,
    WRAPPER_MARGIN,
    WRAPPER_PADDING,
    WRAPPER_BORDER,
    JOB_LIST_BACKGROUND,
    JOB_LIST_BORDER,
    APPLY_BTN_BORDER,
    APPLY_BTN_PADDING,
    APPLY_BUTTON_POSITION,
    JOB_LIST_PADDING,
    SPACE_BETWEEN_TITLE_COMPANY,
    JOB_LIST_MARGIN,
    JOB_LIST_TITLE_MARGIN,
    JOB_LIST_TITLE_PADDING,
    JOB_LIST_TITLE_ICON_WIDTH_FIXED,
    JOB_LIST_TITLE_ICON_HEIGHT_FIXED,
    JOB_LIST_TITLE_ICON_FIXED_SIZE
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

import objAttributes from "./attributes";
import DocLink from "../../../helpers/DocLink";

const {
    ColorControl,
    ResponsiveRangeController,
    ResponsiveDimensionsControl,
    TypographyDropdown,
    BackgroundControl,
    BorderShadowControl,
    DynamicInputControl,
    EBIconPicker
} = window.EJControls;

const Inspector = ({ attributes, setAttributes, makeAjaxCall, setJobsData }) => {
    const {
        resOption,
		hideTitle,
        titleText,
        hideIcon,
        icon,
        applyBtnText,
		filterByTitle,
		filterByCategory,
		filterByLocation,
        orderBy,
        sortBy,
		noOfJob,
		activeOnly,
		showCompanyName,
		showLocation,
		showDateLine,
		showNoOfJob,
        contentAlign,
        applyButtonAlign,
        jobListTitleIconWidth,
        jobListTitleIconHeight,
        jobListTitleIconSize,
        separatorColor,
        submitStyle,
        applyBtnStyle,
        applyBtnTextColor,
        applyBtnBgColor,
        applyBtnTextColorH,
        applyBtnBgColorH,
        resetStyle,
        jobListTitleColor,
        jobListTitleIconBgColor,
        jobListTitleIconColor,
        submitTextColor,
        submitBgColor,
        submitTextColorH,
        submitBgColorH,
        jobTitleColor,
        resetTextColor,
        resetBgColor,
        resetTextColorH,
        resetBgColorH,
        companyNameColor,
        jobLocationColor,
        jobDeadlineColor,
        jobVacancyColor,
    } = attributes;

    const resRequiredProps = {
        setAttributes,
        resOption,
        attributes,
        objAttributes,
    };

    const changeOrderBy = (data) => {
        setAttributes({ orderBy: data });
    };

    const changeSortBy = (data) => {
        setAttributes({ sortBy: data });
    };

	const changeNumberOfJob = (data) => {
		setAttributes({noOfJob: data});
    };

    const changeActiveJob = (data) => {
		setAttributes({activeOnly: !data});
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
                            name: "advanced",
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
                                            "General",
                                            "easyjobs"
                                        )}
                                        initialOpen={false}
                                    >
                                        <ToggleControl
                                            label={__(
                                                "Hide title",
                                                "easyjobs"
                                            )}
                                            checked={hideTitle}
                                            onChange={() =>
                                                setAttributes({
                                                    hideTitle: !hideTitle,
                                                })
                                            }
                                        />
										{!hideTitle && 
											<DynamicInputControl
												label="Title"
												attrName="titleText"
												inputValue={titleText}
												setAttributes={setAttributes}
												onChange={(text) => setAttributes({ titleText: text })}
											/>
										}
										{!hideTitle && 
											<>
												<ToggleControl
													label={__(
														"Hide icon",
														"easyjobs"
													)}
													checked={hideIcon}
													onChange={() =>
														setAttributes({
															hideIcon: !hideIcon,
														})
													}
												/>
												{!hideIcon && 
													<EBIconPicker
														value={icon}
														onChange={(icon) => {
															setAttributes({
																icon,
															});
														}}
													/>
												}
											</>
										}
                                        <DynamicInputControl
                                            label="Apply button text"
                                            attrName="applyBtnText"
                                            inputValue={applyBtnText}
                                            setAttributes={setAttributes}
                                            onChange={(text) => setAttributes({ applyBtnText: text })}
                                        />
										
                                    </PanelBody>
                                    <PanelBody
                                        title={__("Job Filter", "easyjobs")}
                                        initialOpen={false}
                                    >
                                        <ToggleControl
                                            label={__(
                                                "Show search by title",
                                                "easyjobs"
                                            )}
                                            checked={filterByTitle}
                                            onChange={() =>
                                                setAttributes({
                                                    filterByTitle: !filterByTitle,
                                                })
                                            }
                                        />
										<ToggleControl
                                            label={__(
                                                "Show search by category",
                                                "easyjobs"
                                            )}
                                            checked={filterByCategory}
                                            onChange={() =>
                                                setAttributes({
                                                    filterByCategory: !filterByCategory,
                                                })
                                            }
                                        />
										<ToggleControl
                                            label={__(
                                                "Show search by location",
                                                "easyjobs"
                                            )}
                                            checked={filterByLocation}
                                            onChange={() =>
                                                setAttributes({
                                                    filterByLocation: !filterByLocation,
                                                })
                                            }
                                        />
                                    </PanelBody>
									<PanelBody
                                        title={__("Job List", "easyjobs")}
                                        initialOpen={false}
                                    >
										<SelectControl
                                            label={__(
                                                "Order by",
                                                "easyjobs"
                                            )}
                                            value={orderBy}
                                            options={ORDER_BY}
                                            onChange={(type) =>
                                                changeOrderBy(type)
                                            }
                                        />
										<SelectControl
                                            label={__(
                                                "Sort by",
                                                "easyjobs"
                                            )}
                                            value={sortBy}
                                            options={SORT_BY}
                                            onChange={(type) =>
                                                changeSortBy(type)
                                            }
                                        />
                                        <RangeControl
											label={__(
												"Show jobs",
												"easyjobs"
											)}
											value={noOfJob}
											onChange={(noOfJob) =>
												changeNumberOfJob(
													noOfJob
												)
											}
											step={1}
											min={1}
											max={10000}
										/>
										<ToggleControl
                                            label={__(
                                                "Show open jobs only",
                                                "easyjobs"
                                            )}
                                            checked={activeOnly}
                                            onChange={() =>
                                                changeActiveJob(
                                                    activeOnly,
                                                )
                                            }
                                        />
										<ToggleControl
                                            label={__(
                                                "Show company name",
                                                "easyjobs"
                                            )}
                                            checked={showCompanyName}
                                            onChange={() =>
                                                setAttributes({
                                                    showCompanyName: !showCompanyName,
                                                })
                                            }
                                        />
										<ToggleControl
                                            label={__(
                                                "Show company address",
                                                "easyjobs"
                                            )}
                                            checked={showLocation}
                                            onChange={() =>
                                                setAttributes({
                                                    showLocation: !showLocation,
                                                })
                                            }
                                        />
										<ToggleControl
                                            label={__(
                                                "Show Deadline",
                                                "easyjobs"
                                            )}
                                            checked={showDateLine}
                                            onChange={() =>
                                                setAttributes({
                                                    showDateLine: !showDateLine,
                                                })
                                            }
                                        />
										<ToggleControl
                                            label={__(
                                                "Show No of Vacancies",
                                                "easyjobs"
                                            )}
                                            checked={showNoOfJob}
                                            onChange={() =>
                                                setAttributes({
                                                    showNoOfJob: !showNoOfJob,
                                                })
                                            }
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
                                        <>
                                            <BaseControl>
                                                <h3 className="eb-control-title">
                                                    {__(
                                                        "Background",
                                                        "easyjobs"
                                                    )}
                                                </h3>
                                            </BaseControl>
                                            <BackgroundControl
                                                controlName={BLOCK_BACKGROUND}
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
                                                                    contentAlign ===
                                                                    item.value
                                                                }
                                                                isSecondary={
                                                                    contentAlign !==
                                                                    item.value
                                                                }
                                                                onClick={() =>
                                                                    setAttributes({
                                                                        contentAlign:
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

                                            <ResponsiveRangeController
                                                baseLabel={__(
                                                    "Width",
                                                    "easyjobs"
                                                )}
                                                controlName={BLOCK_WIDTH}
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                                min={0}
                                                max={100}
                                                step={1}
                                            />

                                            {/* <BaseControl>
                                                <h3 className="eb-control-title">
                                                    {__(
                                                        "Border",
                                                        "easyjobs"
                                                    )}
                                                </h3>
                                            </BaseControl> */}
                                            
                                            <ResponsiveDimensionsControl
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                                controlName={WRAPPER_MARGIN}
                                                baseLabel={__(
                                                    "Margin",
                                                    "easyjobs"
                                                )}
                                            />
                                            <ResponsiveDimensionsControl
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                                controlName={WRAPPER_PADDING}
                                                baseLabel={__(
                                                    "Form Padding",
                                                    "easyjobs"
                                                )}
                                            />
                                            <BorderShadowControl
                                                controlName={WRAPPER_BORDER}
                                                noBorder={false}
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                            />
                                        </>
                                    </PanelBody>
                                    <PanelBody
                                        title={__("Section", "easyjobs")}
                                        initialOpen={false}
                                    >
                                        <>
                                            <ResponsiveDimensionsControl
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                                controlName={JOB_LIST_TITLE_MARGIN}
                                                baseLabel={__(
                                                    "Margin",
                                                    "easyjobs"
                                                )}
                                            />
                                            <ResponsiveDimensionsControl
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                                controlName={JOB_LIST_TITLE_PADDING}
                                                baseLabel={__(
                                                    "Padding",
                                                    "easyjobs"
                                                )}
                                            />
                                            <Divider />
                                            <BaseControl>
                                                <h3 className="eb-control-title">
                                                    {__(
                                                        "Section Heading",
                                                        "easyjobs"
                                                    )}
                                                </h3>
                                            </BaseControl>
                                            <ColorControl
                                                label={__(
                                                    "Color",
                                                    "easyjobs"
                                                )}
                                                color={jobListTitleColor}
                                                onChange={(newTextColor) =>
                                                    setAttributes({
                                                        jobListTitleColor: newTextColor,
                                                    })
                                                }
                                            />
                                            <TypographyDropdown
                                                baseLabel={__(
                                                    "Typography",
                                                    "easyjobs"
                                                )}
                                                typographyPrefixConstant={
                                                    TypoprefixTextJobListTitle
                                                }
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                            />
                                            <Divider />
                                            <BaseControl>
                                                <h3 className="eb-control-title">
                                                    {__(
                                                        "Section Icon",
                                                        "easyjobs"
                                                    )}
                                                </h3>
                                            </BaseControl>
                                            <BaseControl
                                                label={__(
                                                    "Width",
                                                    "easyjobs"
                                                )}
                                            >
                                                {/* <ButtonGroup id="eb-button-group-alignment">
                                                    {JOB_LIST_TITLE_ICON_WIDTH.map(
                                                        (item, index) => (
                                                            <Button
                                                                key={index}
                                                                isPrimary={
                                                                    jobListTitleIconWidth ===
                                                                    item.value
                                                                }
                                                                isSecondary={
                                                                    jobListTitleIconWidth !==
                                                                    item.value
                                                                }
                                                                onClick={() =>
                                                                    setAttributes({
                                                                        jobListTitleIconWidth:
                                                                            item.value,
                                                                    })
                                                                }
                                                            >
                                                                {item.label}
                                                            </Button>
                                                        )
                                                    )}
                                                </ButtonGroup> */}
                                            </BaseControl>
                                            {jobListTitleIconWidth === "fixed" && (
                                                <ResponsiveRangeController
                                                    baseLabel={__(
                                                        "Width",
                                                        "easyjobs"
                                                    )}
                                                    controlName={JOB_LIST_TITLE_ICON_WIDTH_FIXED}
                                                    resRequiredProps={
                                                        resRequiredProps
                                                    }
                                                    min={50}
                                                    max={100}
                                                    step={5}
                                                />
                                            )}
                                            <BaseControl
                                                label={__(
                                                    "Height",
                                                    "easyjobs"
                                                )}
                                            >
                                                {/* <ButtonGroup id="eb-button-group-alignment">
                                                    {JOB_LIST_TITLE_ICON_HEIGHT.map(
                                                        (item, index) => (
                                                            <Button
                                                                key={index}
                                                                isPrimary={
                                                                    jobListTitleIconHeight ===
                                                                    item.value
                                                                }
                                                                isSecondary={
                                                                    jobListTitleIconHeight !==
                                                                    item.value
                                                                }
                                                                onClick={() =>
                                                                    setAttributes({
                                                                        jobListTitleIconHeight:
                                                                            item.value,
                                                                    })
                                                                }
                                                            >
                                                                {item.label}
                                                            </Button>
                                                        )
                                                    )}
                                                </ButtonGroup> */}
                                            </BaseControl>
                                            {jobListTitleIconHeight === "fixed" && (
                                                <ResponsiveRangeController
                                                    baseLabel={__(
                                                        "Height",
                                                        "easyjobs"
                                                    )}
                                                    controlName={JOB_LIST_TITLE_ICON_HEIGHT_FIXED}
                                                    resRequiredProps={
                                                        resRequiredProps
                                                    }
                                                    min={50}
                                                    max={100}
                                                    step={5}
                                                />
                                            )}
                                            <ColorControl
                                                label={__(
                                                    "Background",
                                                    "easyjobs"
                                                )}
                                                color={jobListTitleIconBgColor}
                                                onChange={(newTextColor) =>
                                                    setAttributes({
                                                        jobListTitleIconBgColor: newTextColor,
                                                    })
                                                }
                                            />
                                            <ColorControl
                                                label={__(
                                                    "Color",
                                                    "easyjobs"
                                                )}
                                                color={jobListTitleIconColor}
                                                onChange={(newTextColor) =>
                                                    setAttributes({
                                                        jobListTitleIconColor: newTextColor,
                                                    })
                                                }
                                            />
                                            <BaseControl
                                                label={__(
                                                    "Icon Size",
                                                    "easyjobs"
                                                )}
                                            >
                                                {/* <ButtonGroup id="eb-button-group-alignment">
                                                    {JOB_LIST_TITLE_ICON_SIZE.map(
                                                        (item, index) => (
                                                            <Button
                                                                key={index}
                                                                isPrimary={
                                                                    jobListTitleIconSize ===
                                                                    item.value
                                                                }
                                                                isSecondary={
                                                                    jobListTitleIconSize !==
                                                                    item.value
                                                                }
                                                                onClick={() =>
                                                                    setAttributes({
                                                                        jobListTitleIconSize:
                                                                            item.value,
                                                                    })
                                                                }
                                                            >
                                                                {item.label}
                                                            </Button>
                                                        )
                                                    )}
                                                </ButtonGroup> */}
                                            </BaseControl>
                                            {jobListTitleIconSize === "fixed" && (
                                                <ResponsiveRangeController
                                                    baseLabel={__(
                                                        "Size",
                                                        "easyjobs"
                                                    )}
                                                    controlName={JOB_LIST_TITLE_ICON_FIXED_SIZE}
                                                    resRequiredProps={
                                                        resRequiredProps
                                                    }
                                                    min={10}
                                                    max={50}
                                                    step={5}
                                                />
                                            )}
                                        </>
                                    </PanelBody>
                                    <PanelBody
                                        title={__("Job Filter", "easyjobs")}
                                        initialOpen={false}
                                    >
                                        <>
                                            <BaseControl>
                                                <h3 className="eb-control-title">
                                                    {__(
                                                        "Submit Button",
                                                        "easyjobs"
                                                    )}
                                                </h3>
                                            </BaseControl>
                                            <TypographyDropdown
                                                baseLabel={__(
                                                    "Typography",
                                                    "easyjobs"
                                                )}
                                                typographyPrefixConstant={
                                                    TypoprefixTextSubmitBtn
                                                }
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                            />
                                            <BaseControl>
                                                <ButtonGroup>
                                                    {[
                                                        {
                                                            label: __("Normal", "easyjobs"),
                                                            value: "normal",
                                                        },
                                                        {
                                                            label: __("Hover", "easyjobs"),
                                                            value: "hover",
                                                        },
                                                    ].map(({ value, label }, index) => (
                                                        <Button
                                                            key={'submitBtn' + index}
                                                            isPrimary={submitStyle === value}
                                                            isSecondary={submitStyle !== value}
                                                            onClick={() =>
                                                                setAttributes({
                                                                    submitStyle: value,
                                                                })
                                                            }>
                                                            {label}
                                                        </Button>
                                                    ))}
                                                </ButtonGroup>
                                            </BaseControl>
                                            {submitStyle === "normal" && (
				                                <>
					                                <BaseControl>
                                                        <ColorControl
                                                            label={__(
                                                                "Text Color",
                                                                "easyjobs"
                                                            )}
                                                            color={submitTextColor}
                                                            onChange={(newTextColor) =>
                                                                setAttributes({
                                                                    submitTextColor: newTextColor,
                                                                })
                                                            }
                                                        />
                                                        <ColorControl
                                                            label={__(
                                                                "Background Color",
                                                                "easyjobs"
                                                            )}
                                                            color={submitBgColor}
                                                            onChange={(newTextColor) =>
                                                                setAttributes({
                                                                    submitBgColor: newTextColor,
                                                                })
                                                            }
                                                        />
                                                    </BaseControl>
                                                </>
                                            )}
                                            {submitStyle === "hover" && (
				                                <>
					                                <BaseControl>
                                                        <ColorControl
                                                            label={__(
                                                                "Text Color",
                                                                "easyjobs"
                                                            )}
                                                            color={submitTextColorH}
                                                            onChange={(newTextColor) =>
                                                                setAttributes({
                                                                    submitTextColorH: newTextColor,
                                                                })
                                                            }
                                                        />
                                                        <ColorControl
                                                            label={__(
                                                                "Background Color",
                                                                "easyjobs"
                                                            )}
                                                            color={submitBgColorH}
                                                            onChange={(newTextColor) =>
                                                                setAttributes({
                                                                    submitBgColorH: newTextColor,
                                                                })
                                                            }
                                                        />
                                                    </BaseControl>
                                                </>
                                            )}
                                        </>
                                        <Divider />
                                        <>
                                            <BaseControl>
                                                <h3 className="eb-control-title">
                                                    {__(
                                                        "Reset Button",
                                                        "easyjobs"
                                                    )}
                                                </h3>
                                            </BaseControl>
                                            <TypographyDropdown
                                                baseLabel={__(
                                                    "Typography",
                                                    "easyjobs"
                                                )}
                                                typographyPrefixConstant={
                                                    TypoprefixTextResetBtn
                                                }
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                            />
                                            <BaseControl>
                                                <ButtonGroup>
                                                    {[
                                                        {
                                                            label: __("Normal", "easyjobs"),
                                                            value: "normal",
                                                        },
                                                        {
                                                            label: __("Hover", "easyjobs"),
                                                            value: "hover",
                                                        },
                                                    ].map(({ value, label }, index) => (
                                                        <Button
                                                            key={index}
                                                            isPrimary={resetStyle === value}
                                                            isSecondary={resetStyle !== value}
                                                            onClick={() =>
                                                                setAttributes({
                                                                    resetStyle: value,
                                                                })
                                                            }>
                                                            {label}
                                                        </Button>
                                                    ))}
                                                </ButtonGroup>
                                            </BaseControl>
                                            {resetStyle === "normal" && (
				                                <>
					                                <BaseControl>
                                                        <ColorControl
                                                            label={__(
                                                                "Text Color",
                                                                "easyjobs"
                                                            )}
                                                            color={resetTextColor}
                                                            onChange={(newTextColor) =>
                                                                setAttributes({
                                                                    resetTextColor: newTextColor,
                                                                })
                                                            }
                                                        />
                                                        <ColorControl
                                                            label={__(
                                                                "Background Color",
                                                                "easyjobs"
                                                            )}
                                                            color={resetBgColor}
                                                            onChange={(newTextColor) =>
                                                                setAttributes({
                                                                    resetBgColor: newTextColor,
                                                                })
                                                            }
                                                        />
                                                    </BaseControl>
                                                </>
                                            )}
                                            {resetStyle === "hover" && (
				                                <>
					                                <BaseControl>
                                                        <ColorControl
                                                            label={__(
                                                                "Text Color h",
                                                                "easyjobs"
                                                            )}
                                                            color={resetTextColorH}
                                                            onChange={(newTextColor) =>
                                                                setAttributes({
                                                                    resetTextColorH: newTextColor,
                                                                })
                                                            }
                                                        />
                                                        <ColorControl
                                                            label={__(
                                                                "Background Color",
                                                                "easyjobs"
                                                            )}
                                                            color={resetBgColorH}
                                                            onChange={(newTextColor) =>
                                                                setAttributes({
                                                                    resetBgColorH: newTextColor,
                                                                })
                                                            }
                                                        />
                                                    </BaseControl>
                                                </>
                                            )}
                                        </>
                                    </PanelBody>
                                    <PanelBody
                                        title={__("Job List", "easyjobs")}
                                        initialOpen={false}
                                    >
                                        <>
                                            <BaseControl>
                                                <h3 className="eb-control-title">
                                                    {__(
                                                        "Background",
                                                        "easyjobs"
                                                    )}
                                                </h3>
                                            </BaseControl>
                                            <BackgroundControl
                                                controlName={JOB_LIST_BACKGROUND}
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                                noOverlay={true}
                                                noMainBgi={true}
                                            />
                                            <ColorControl
                                                label={__(
                                                    "Separator Color",
                                                    "easyjobs"
                                                )}
                                                color={separatorColor}
                                                onChange={(newTextColor) =>
                                                    setAttributes({
                                                        separatorColor: newTextColor,
                                                    })
                                                }
                                            />
                                            <ResponsiveDimensionsControl
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                                controlName={JOB_LIST_PADDING}
                                                baseLabel={__(
                                                    "Padding",
                                                    "easyjobs"
                                                )}
                                            />
                                            <ResponsiveDimensionsControl
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                                controlName={JOB_LIST_MARGIN}
                                                baseLabel={__(
                                                    "Margin",
                                                    "easyjobs"
                                                )}
                                            />
                                            <BorderShadowControl
                                                controlName={JOB_LIST_BORDER}
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                            />
                                            <Divider />
                                            <BaseControl>
                                                <h3 className="eb-control-title">
                                                    {__(
                                                        "Job Title",
                                                        "easyjobs"
                                                    )}
                                                </h3>
                                            </BaseControl>
                                            <ColorControl
                                                label={__(
                                                    "Color",
                                                    "easyjobs"
                                                )}
                                                color={jobTitleColor}
                                                onChange={(newTextColor) =>
                                                    setAttributes({
                                                        jobTitleColor: newTextColor,
                                                    })
                                                }
                                            />
                                            <TypographyDropdown
                                                baseLabel={__(
                                                    "Typography",
                                                    "easyjobs"
                                                )}
                                                typographyPrefixConstant={
                                                    TypoprefixTextJobListJobTitle
                                                }
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                            />
                                            <ResponsiveDimensionsControl
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                                controlName={SPACE_BETWEEN_TITLE_COMPANY}
                                                baseLabel={__(
                                                    "Space",
                                                    "easyjobs"
                                                )}
                                            />
                                            <Divider />
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
                                                    TypoprefixTextJobListCompanyName
                                                }
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                            />
                                            <Divider />
                                            <BaseControl>
                                                <h3 className="eb-control-title">
                                                    {__(
                                                        "Job Location",
                                                        "easyjobs"
                                                    )}
                                                </h3>
                                            </BaseControl>
                                            <ColorControl
                                                label={__(
                                                    "Color",
                                                    "easyjobs"
                                                )}
                                                color={jobLocationColor}
                                                onChange={(newTextColor) =>
                                                    setAttributes({
                                                        jobLocationColor: newTextColor,
                                                    })
                                                }
                                            />
                                            <TypographyDropdown
                                                baseLabel={__(
                                                    "Typography",
                                                    "easyjobs"
                                                )}
                                                typographyPrefixConstant={
                                                    TypoprefixTextJobListJobLocation
                                                }
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                            />
                                            <Divider />
                                            <BaseControl>
                                                <h3 className="eb-control-title">
                                                    {__(
                                                        "Job Deadline",
                                                        "easyjobs"
                                                    )}
                                                </h3>
                                            </BaseControl>
                                            <ColorControl
                                                label={__(
                                                    "Color",
                                                    "easyjobs"
                                                )}
                                                color={jobDeadlineColor}
                                                onChange={(newTextColor) =>
                                                    setAttributes({
                                                        jobDeadlineColor: newTextColor,
                                                    })
                                                }
                                            />
                                            <TypographyDropdown
                                                baseLabel={__(
                                                    "Typography",
                                                    "easyjobs"
                                                )}
                                                typographyPrefixConstant={
                                                    TypoprefixTextJobListDeadline
                                                }
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                            />
                                            <Divider />
                                            <BaseControl>
                                                <h3 className="eb-control-title">
                                                    {__(
                                                        "Job Vacancies",
                                                        "easyjobs"
                                                    )}
                                                </h3>
                                            </BaseControl>
                                            <ColorControl
                                                label={__(
                                                    "Color",
                                                    "easyjobs"
                                                )}
                                                color={jobVacancyColor}
                                                onChange={(newTextColor) =>
                                                    setAttributes({
                                                        jobVacancyColor: newTextColor,
                                                    })
                                                }
                                            />
                                            <TypographyDropdown
                                                baseLabel={__(
                                                    "Typography",
                                                    "easyjobs"
                                                )}
                                                typographyPrefixConstant={
                                                    TypoprefixTextJobListNoOfJobs
                                                }
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                            />
                                            <Divider />
                                            <BaseControl>
                                                <h3 className="eb-control-title">
                                                    {__(
                                                        "Job Apply Button",
                                                        "easyjobs"
                                                    )}
                                                </h3>
                                            </BaseControl>
                                            <TypographyDropdown
                                                baseLabel={__(
                                                    "Typography",
                                                    "easyjobs"
                                                )}
                                                typographyPrefixConstant={
                                                    TypoprefixTextJobListJobApplyBtn
                                                }
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                            />
                                            <BaseControl
                                                label={__(
                                                    "Alignment",
                                                    "easyjobs"
                                                )}
                                            >
                                                <ButtonGroup id="eb-button-group-alignment">
                                                    {APPLY_BUTTON_POSITION.map(
                                                        (item, index) => (
                                                            <Button
                                                                key={index}
                                                                isPrimary={
                                                                    applyButtonAlign ===
                                                                    item.value
                                                                }
                                                                isSecondary={
                                                                    applyButtonAlign !==
                                                                    item.value
                                                                }
                                                                onClick={() =>
                                                                    setAttributes({
                                                                        applyButtonAlign:
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
                                            <BaseControl>
                                                <ButtonGroup>
                                                    {[
                                                        {
                                                            label: __("Normal", "easyjobs"),
                                                            value: "normal",
                                                        },
                                                        {
                                                            label: __("Hover", "easyjobs"),
                                                            value: "hover",
                                                        },
                                                    ].map(({ value, label }, index) => (
                                                        <Button
                                                            key={'submitBtn' + index}
                                                            isPrimary={applyBtnStyle === value}
                                                            isSecondary={applyBtnStyle !== value}
                                                            onClick={() =>
                                                                setAttributes({
                                                                    applyBtnStyle: value,
                                                                })
                                                            }>
                                                            {label}
                                                        </Button>
                                                    ))}
                                                </ButtonGroup>
                                            </BaseControl>
                                            {applyBtnStyle === "normal" && (
				                                <>
					                                <BaseControl>
                                                        <ColorControl
                                                            label={__(
                                                                "Text Color",
                                                                "easyjobs"
                                                            )}
                                                            color={applyBtnTextColor}
                                                            onChange={(newTextColor) =>
                                                                setAttributes({
                                                                    applyBtnTextColor: newTextColor,
                                                                })
                                                            }
                                                        />
                                                        <ColorControl
                                                            label={__(
                                                                "Background Color",
                                                                "easyjobs"
                                                            )}
                                                            color={applyBtnBgColor}
                                                            onChange={(newTextColor) =>
                                                                setAttributes({
                                                                    applyBtnBgColor: newTextColor,
                                                                })
                                                            }
                                                        />
                                                    </BaseControl>
                                                </>
                                            )}
                                            {applyBtnStyle === "hover" && (
				                                <>
					                                <BaseControl>
                                                        <ColorControl
                                                            label={__(
                                                                "Text Color",
                                                                "easyjobs"
                                                            )}
                                                            color={applyBtnTextColorH}
                                                            onChange={(newTextColor) =>
                                                                setAttributes({
                                                                    applyBtnTextColorH: newTextColor,
                                                                })
                                                            }
                                                        />
                                                        <ColorControl
                                                            label={__(
                                                                "Background Color",
                                                                "easyjobs"
                                                            )}
                                                            color={applyBtnBgColorH}
                                                            onChange={(newTextColor) =>
                                                                setAttributes({
                                                                    applyBtnBgColorH: newTextColor,
                                                                })
                                                            }
                                                        />
                                                    </BaseControl>
                                                </>
                                            )}
                                            <BorderShadowControl
                                                controlName={APPLY_BTN_BORDER}
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                            />
                                            <ResponsiveDimensionsControl
                                                resRequiredProps={
                                                    resRequiredProps
                                                }
                                                controlName={APPLY_BTN_PADDING}
                                                baseLabel={__(
                                                    "Padding",
                                                    "easyjobs"
                                                )}
                                            />
                                        </>
                                    </PanelBody>
                                </>
                            )}
                            {/* {tab.name === "advanced" && (
                                <>
                                    <PanelBody>
                                        <ResponsiveDimensionsControl
                                            resRequiredProps={resRequiredProps}
                                            controlName={WRAPPER_MARGIN}
                                            baseLabel={__(
                                                "Margin",
                                                "easyjobs"
                                            )}
                                        />
                                        <SelectControl
                                            label={__(
                                                "Hover Effect",
                                                "easyjobs"
                                            )}
                                            value={hoverEffect}
                                            options={HOVER_EFFECT}
                                            onChange={(newHoverEffect) =>
                                                setAttributes({
                                                    hoverEffect: newHoverEffect,
                                                })
                                            }
                                        />
                                        {hoverEffect && (
                                            <RangeControl
                                                label={__(
                                                    "Hover Transition",
                                                    "easyjobs"
                                                )}
                                                value={hoverTransition}
                                                onChange={(hoverTransition) =>
                                                    setAttributes({
                                                        hoverTransition,
                                                    })
                                                }
                                                step={0.01}
                                                min={0}
                                                max={5}
                                            />
                                        )}
                                    </PanelBody>

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
            <DocLink link={'https://easy.jobs/docs/display-job-listings-on-your-career-page/'} />
        </InspectorControls>
    );
};

export default Inspector;