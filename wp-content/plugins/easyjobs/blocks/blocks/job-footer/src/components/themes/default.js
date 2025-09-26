
const Default = ({props}) => {

    const { companyDetails, attributes } = props;
    const { 
        lifeAtTitle,
    } = attributes;

    return (
        <>
            <div className="easyjobs-shortcode-wrapper ej-template-default" id="easyjobs-list">
                <div className="easyjobs-details">
                    <div className="ej-job-body">
                        {( companyDetails?.show_life && companyDetails?.showcase_photo?.length > 0 ) ? (
                            <div className="ej-section">
                                {( lifeAtTitle !== '' || companyDetails?.name ) && (
                                    <div className="ej-section-title">
                                        <span className="ej-section-title-icon">
                                            <i className="easyjobs-icon easyjobs-briefcase"></i>
                                        </span>
                                        <span className="ej-section-title-text">
                                            {lifeAtTitle !== '' ? lifeAtTitle : `Life at ${companyDetails?.name}`}
                                        </span>
                                    </div>
                                )}
                                <div className="ej-section-content">
                                    <div className="ej-company-showcase">
                                        <div className="ej-showcase-inner">
                                            <div className="ej-showcase-left">
                                                <div className="ej-showcase-image">
                                                    <div className="ej-image">
                                                        <img src={companyDetails?.showcase_photo[0]} alt={companyDetails?.name} />
                                                    </div>
                                                </div>
                                            </div>
                                            {companyDetails?.showcase_photo?.length > 1 && (
                                                <div className="ej-showcase-right">
                                                    {companyDetails?.showcase_photo?.map( (data, i) => {
                                                        if (i != 0) {
                                                            return (
                                                                <div className="ej-showcase-image">
                                                                    <div className="ej-image">
                                                                        <img src={data} alt={companyDetails?.name} />
                                                                    </div>
                                                                </div>
                                                            );
                                                        }
                                                    })}
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ) : (
                            <p className="elej-error-msg-editor">To use the Company Gallery, navigate to <strong>{`Settings > Photos & Colors,`}</strong> and enable the "Show on company page" option.</p>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
};

export default Default;