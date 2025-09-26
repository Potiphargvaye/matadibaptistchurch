
import { withSelect } from '@wordpress/data';
import Default from './themes/default';
import Classic from './themes/classic';
import Elegant from './themes/elegant';
import Style from './style';
import Inspector from './inspector';
import { useState, useEffect } from '@wordpress/element';
const {
  	BlockProps
} = window.EJControls;

const Edit = (props) => {
    
    const { companyInfo, isSelected, attributes } = props;
	const { cover } = attributes;
	const [apiError, setApiError] = useState(false);
	
	useEffect(() => {
		if((companyInfo?.length === 1)  && (companyInfo[0] === 'api-error')) {
			setApiError(true);
		}	
	}, [companyInfo]);

	// you must declare this variable
    const enhancedProps = {
        ...props,
        style: <Style {...props} />
    };

    return cover.length ? (
        <div>
            <img src={cover} alt="job header" style={{ maxWidth: "100%" }} />
        </div>
    ) : (
        <>
			{isSelected && !apiError && <Inspector {...props} />}
            <BlockProps.Edit { ...enhancedProps }>
            	<>
					{
						! apiError ? (
							companyInfo ? (
								<>
									{companyInfo.selected_template === 'default' && 
										<Default 
											props={props}
										/>
									}
									{companyInfo.selected_template === 'classic' &&
										<Classic 
											props={props}
										/>
									}
									{companyInfo.selected_template === 'elegant' && 
										<Elegant 
											props={props}
										/>
									}
								</>
							) : (
								<p>Loading header block...</p>
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
	const companyDetails = select('easyjobs').getCompanyDetails();
	return {
		companyInfo,
		companyDetails
	}
})(Edit)