import { __ } from '@wordpress/i18n';

import JobFilter from "./common/filter";
import Pagination from './common/pagination';

const {
    DynamicInputValueHandler
} = window.EJControls;

const Classic = ({props, jobsData}) => {

	const {setAttributes, attributes} = props;
	const {
		titleText,
		hideTitle,
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
				<div className="easyjobs-shortcode-wrapper ej-template-classic" id="easyjobs-list">
					<div className="ej-section">
						<div className="section__header section__header--flex ej-job-filter-wrap" id="open_job_position">
							{!hideTitle && 
								<div className="ej-section-title">
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
							{(filterByTitle || filterByCategory || filterByLocation) && 
								<JobFilter 
									props={props} 
									categories={jobsData?.categories} 
									locations={jobsData?.locations}
								/>
							}
						</div>
					</div>
					<div className="ej-job-list ej-job-list-classic">
						{jobsData?.jobs?.data && jobsData?.jobs?.data?.map((job, i) => {
							return (
								<div className="ej-job-list-item job__card ej-job-list-item-cat <?php if(isset($job->is_pinned) && $job->is_pinned) echo 'ej-has-badge'?>">
									<div className="job__info ej-job-list-item-col">
										<h3 className="ej-job-title">
											<a onClick={(e)=>e.preventDefault()} href="#">
												{job?.title}
											</a>
										</h3>
										<p className="meta ej-job-list-info">
											{showCompanyName && (
												<>
													<i className="easyjobs-icon easyjobs-briefcase-2"> </i>
													<a onClick={(e)=>e.preventDefault()} href="#" target="_blank" className="office__name ej-job-list-info-block">
														{job?.company_name}
													</a>
												</>
											)}
											{showLocation && (
												<span className='office__location ej-job-list-info-block'>
													{(job?.is_remote || job?.job_address?.city || job?.job_address?.country) &&
														<i className="easyjobs-icon easyjobs-map-maker"></i>
													}
													{job.is_remote ? (
															<span> Anywhere</span>
														) : (
															<span> {job?.job_address?.city && job?.job_address?.city?.name}
															{(job?.job_address?.country && job?.job_address?.city) && ", "}
															{job?.job_address?.country &&
																job?.job_address?.country?.name
															}</span>
														)
													}
												</span>
											)}
										</p>
									</div>
									{showNoOfJob && job?.vacancies &&
										<div className="job__vacancy ej-job-list-item-col">
											<h4>{job.vacancies}</h4>
											<p>{__( 'No of vacancies ', 'easyjobs' )}</p>
										</div>
									}
									<div className="job__apply ej-job-list-item-col">
										<a onClick={(e)=>e.preventDefault()} href={`${job?.apply_url ? job?.apply_url : '#'}`} className="button button__success button__radius" target="_blank">
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
										{showDateLine && (
											<span className="deadline ej-deadline">
												{!job?.is_expired ? (
													<>
														<i className="easyjobs-icon easyjobs-calender"></i>
														<span> 
															{__(' Deadline:', 'easyjobs')} {job?.expire_at}
														</span>
													</>
												) : (
													<span className="ej-expired">{__( 'Expired', 'easyjobs' )}</span>
												)}
											</span>
										)}
									</div>
								</div>
							)}
						)}
					</div>
					{jobsData?.jobs && jobsData?.jobs?.last_page > 1 &&
						<Pagination jobs={jobsData?.jobs} />
					}
				</div>
			</div>
		</>
	);
}

export default Classic;