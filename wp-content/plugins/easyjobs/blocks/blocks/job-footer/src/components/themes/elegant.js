
const Elegant = ({props}) => {

    const { companyDetails, attributes } = props;
    const { 
        lifeAtTitle,
    } = attributes;

    return (
        <>
            <div className="easyjobs-shortcode-wrapper ej-template-elegant">
                <div className="pt150 pb100">
                    {( companyDetails?.show_life && companyDetails?.showcase_photo?.length > 0 ) ? (
                        <div className="mt60">
                        <div className="ej-container">
                            <div className="ej-row">
                                <div className="ej-col">
                                    <div className="section__header">
                                        <h2>
                                            {lifeAtTitle !== '' ? lifeAtTitle : `Life at ${companyDetails?.name}`}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="image__gallery">
                            {companyDetails?.showcase_photo?.map( (data, i) => {
                                return (
                                    <div className="item">
                                        <img src={data} alt={companyDetails?.name} />
                                    </div>
                                )
                            })}
                        </div>
                        </div>
                    ) : (
                        <p className="elej-error-msg-editor">To use the Company Gallery, navigate to <strong>{`Settings > Photos & Colors,`}</strong> and enable the "Show on company page" option.</p>
                    )}
                </div>
            </div>
        </>
    );
};

export default Elegant;