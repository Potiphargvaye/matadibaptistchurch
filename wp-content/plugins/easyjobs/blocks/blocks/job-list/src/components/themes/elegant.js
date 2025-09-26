import { __ } from '@wordpress/i18n';

import JobFilter from "./common/filter";
import Pagination from './common/pagination';

const {
    DynamicInputValueHandler,
} = window.EJControls;

const Elegant = ({props, jobsData}) => {
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
				<div className="easyjobs-shortcode-wrapper ej-template-elegant" id="easyjobs-list">
					<div className="ej-section">
						<div className="section__header section__header--flex ej-job-filter-wrap">
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
					<div className="ej-job-list ej-job-list-elegant">
						<div className="ej-row">
							{jobsData?.jobs?.data && jobsData?.jobs?.data?.map((job, i) => {
								return (
									<div className="ej-col-lg-6 ej-job-list-item-cat">
										<div className="job__card <?php if(isset($job->is_pinned) && $job->is_pinned) echo 'ej-has-badge'?>">
											<h3 className="ej-job-title">
												<a onClick={(e)=>e.preventDefault()} href="#"
											>
													{job?.title}
												</a>
											</h3>
											<p className="meta">
												{showCompanyName && (
													<>
														<i className="easyjobs-icon easyjobs-briefcase"> </i>
														<a onClick={(e)=>e.preventDefault()} href="#" className="office__name">
															{job?.company_name}
														</a>
													</>
												)}
												{showLocation && (job?.is_remote || job?.job_address?.city || job?.job_address?.country) &&
													<span className="office__location">
														<i className="easyjobs-icon easyjobs-map-maker"></i>
														{job?.is_remote ? (
																<span>{__( ' Anywhere', 'easyjobs' )}</span>
															) : (
																<span> {job?.job_address?.city && job?.job_address?.city?.name}
																{(job?.job_address?.country && job?.job_address?.city) && ", "}
																{job?.job_address?.country &&
																	job?.job_address?.country?.name
																}</span>
															)
														}
													</span>
												}
											</p>
											<div className="job__bottom">
												<div className="job__apply">
													<a onClick={(e)=>e.preventDefault()} href={`${job?.apply_url ? job?.apply_url : '#'}`} className="button button__primary radius-15" target="_blank">
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
												{showNoOfJob && job?.vacancies &&
													<div className="job__vacancy">
														<h4>{job.vacancies}</h4>
														<p>{__( 'No of vacancies', 'easyjobs' )}</p>
													</div>
												}
											</div>
											{showDateLine && (
												<span className="deadline">
													<i className="ej-icon ej-calender"></i>
													{job?.expire_at}
												</span>
											)}
										</div>
									</div>
								)
							})}
						</div>
						{jobsData?.jobs && jobsData?.jobs?.last_page > 1 &&
							<Pagination jobs={jobsData?.jobs} />
						}
					</div>
				</div>
			</div>
        </>
    );
}

export default Elegant;