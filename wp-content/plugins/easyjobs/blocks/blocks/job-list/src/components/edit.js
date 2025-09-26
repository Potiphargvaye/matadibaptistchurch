/**
 * WordPress dependencies
 */
import { withSelect } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies
 */
const {
	BlockProps
} = window.EJControls;
import Default from './themes/default';
import Classic from './themes/classic';
import Elegant from './themes/elegant';
import Inspector from "./inspector";
import { useEffect, useState } from '@wordpress/element';
import Style from "./style";

const Edit = (props) => {
	const [jobsData, setJobsData] = useState({jobs: {}, categories: {}, locations: {}});
	const [apiError, setApiError] = useState(false);
	const {attributes, companyInfo, isSelected} = props;
  	const { orderBy, sortBy, activeOnly, noOfJob, cover } = attributes;
	  
	useEffect(() => {
		if((companyInfo?.length === 1)  && (companyInfo[0] === 'api-error')) {
			setApiError(true);
		}	
	}, [companyInfo]);

	const getInitialData = () => {
		const getUrl = () => {
			const params = new URLSearchParams({
				action: 'easyjobs_get_jobs_for_block',
				blocks_nonce: EasyJobsBlocksJs.blocks_nonce,
				orderby: orderBy,
				order: sortBy,
				status: activeOnly,
				row: noOfJob
			});
			return EasyJobsBlocksJs.ajax_url + '?' + params.toString();
		}
		apiFetch({
			url: getUrl()
		}).then(response =>{
			if(response.status === 'success'){
				setJobsData({
					jobs: response.data.jobs,
					categories: response.data.categories,
					locations: response.data.locations
				})
			} else{
				console.error('Fetch error:', response?.message || response?.status);
			}
			
		}).catch(error => console.error(error))
		
	};

	useEffect(getInitialData, [orderBy, sortBy, activeOnly, noOfJob]);

	// you must declare this variable
    const enhancedProps = {
        ...props,
        style: <Style {...props} />
    };
  	return cover.length ? (
        <div>
            <img src={cover} alt="job list" style={{ maxWidth: "100%" }} />
        </div>
    ) : (
		<>
            {isSelected && !apiError && <Inspector {...props} />}
			<BlockProps.Edit { ...enhancedProps }>
				<>
					{
						! apiError ? (
							jobsData.jobs && jobsData.jobs.data?.length ? (
								<>
									{companyInfo.selected_template === 'default' && 
										<Default 
											props={props} 
											jobsData={jobsData} 
										/>
									}
									{companyInfo.selected_template === 'classic' &&
										<Classic 
											props={props} 
											jobsData={jobsData}
										/>
									}
									{companyInfo.selected_template === 'elegant' && 
										<Elegant 
											props={props}
											jobsData={jobsData} 
										/>
									}
								</>
							) : (
								jobsData?.jobs?.data?.length === 0 ? (
									(<h3>No jobs found.</h3>)
								) : (
									<p>Loading jobs...</p>
								)
							)
						) : (
							<p className='elej-error-msg-editor'>Please Connect your EasyJobs Account</p>
						)
					}
				</>
			</BlockProps.Edit>
    	</>
	);
}

export default withSelect((select, props) => {
	const companyInfo = select('easyjobs').getCompanyInfo();
	return {
		companyInfo
	}
})(Edit)