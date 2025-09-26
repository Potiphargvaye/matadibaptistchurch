
import { __ } from '@wordpress/i18n';

import JobFilter from "./common/filter";
import Pagination from './common/pagination';

const {
    DynamicInputValueHandler,
    EBDisplayIcon
} = window.EJControls;

const Default = ({props, jobsData}) => {
	
	const {setAttributes, attributes, companyInfo} = props;
	const {
		titleText, 
		hideTitle, 
		hideIcon, 
		icon, 
		applyBtnText, 
		filterByTitle, 
		filterByCategory, 
		filterByLocation,
		showCompanyName,
		showLocation,
		showDateLine,
		showNoOfJob,
	} = attributes;
	return (
		<>
			<div className='ej-job-body easyjobs-blocks easyjobs-blocks-job-list'>
				<div className="easyjobs-shortcode-wrapper ej-template-default" id="easyjobs-list">
					<div className="ej-section">
						<div className="d-flex ej-job-filter-wrap">
							{ ! hideTitle && 
								<div className="ej-section-title">
									{!hideIcon && (
										<EBDisplayIcon
											icon={icon}
											className={`ej-section-title-icon`}
										/>
									)}
									<span className="ej-section-title-text">
										<DynamicInputValueHandler
											placeholder={__("Add title..", "essential-blocks")}
											className="eb-button-text"
											value={titleText}
											onChange={(newText) => setAttributes({ titleText: newText })}
											allowedFormats={[
												"core/bold",
												"core/italic",
												"core/strikethrough",
											]}
										/>
									</span>
								</div>
							}
							{( companyInfo?.show_job_filter && ( filterByTitle || filterByCategory || filterByLocation ) ) && 
								<JobFilter props={props} categories={jobsData?.categories} locations={jobsData?.locations} />
							}
						</div>

						<div className="ej-section-content">
							<div className="ej-job-list">
								{jobsData?.jobs?.data && jobsData?.jobs?.data?.map((job, i) => {
									return (
										<div className="ej-job-list-item ej-job-list-item-cat">
											<div className="ej-job-list-item-inner">
												<div className={`ej-job-list-item-col ${job?.is_pinned ? 'ej-has-badge' : ''}`}>
													<h2 className="ej-job-title">
														<a href="#" onClick={(e)=>e.preventDefault()}>{job?.title}</a>
													</h2>
													{(showCompanyName || showLocation) &&
														<div className="ej-job-list-info">
															{showCompanyName && 
																<div className="ej-job-list-info-block ej-job-list-company-name">
																	<i className="easyjobs-icon easyjobs-briefcase-2"></i>
																	<a onClick={(e)=>e.preventDefault()} href="#" target="_blank">
																		{job?.company_name}
																	</a>
																</div>
															}
															{showLocation &&
																<div className="ej-job-list-info-block ej-job-list-location">
																	{(job?.is_remote || job?.job_address?.city || job?.job_address?.country) &&
																		<i className="easyjobs-icon easyjobs-map-maker"></i>
																	}
																	{job?.is_remote ? (
																		<span>Anywhere</span>
																	) : (
																		<span>
																			{job.job_address?.city && job?.job_address?.city?.name}
																			{(job.job_address?.country && job.job_address?.city) && ", "}
																			{job?.job_address?.country &&
																				job?.job_address?.country?.name
																			}
																		</span>
																	)}
																</div>
															}
														</div>
													}
												</div>
												{(showDateLine || showNoOfJob) && 
													<div className="ej-job-list-item-col ej-job-time-col">
														{!job?.is_expired ? (
															<>
																{showDateLine && 
																	<p className="ej-deadline">{job?.expire_at}</p>
																}
																{job?.vacancies && showNoOfJob &&
																	<p className="ej-list-sub">
																		No of vacancies: {job?.vacancies}
																	</p>
																}
															</>
														) : (
															<p className="ej-list-title ej-expired">
																Expired
															</p>
														)}
													</div>
												}
												<div className="ej-job-list-item-col ej-job-apply-btn">
													<a href={`${job?.apply_url ? job?.apply_url : '#'}`} className="ej-btn ej-info-btn-light" target="_blank" onClick={e=>e.preventDefault()}>
														<DynamicInputValueHandler
															placeholder={__("Add text..", "essential-blocks")}
															className="eb-button-text"
															value={applyBtnText}
															onChange={(newText) => setAttributes({ applyBtnText: newText })}
															allowedFormats={[
																"core/bold",
																"core/italic",
																"core/strikethrough",
															]}
														/>
													</a>
												</div>
											</div>
										</div>
									);
								})}
							</div>
							{jobsData?.jobs && jobsData?.jobs?.last_page > 1 &&
								<Pagination jobs={jobsData?.jobs} />
							}
						</div>
					</div>
				</div>
			</div>
		</>
	);
}

export default Default;