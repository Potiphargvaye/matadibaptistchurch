
const Classic = ({props}) => {

    const { companyDetails, attributes } = props;
    const { 
        lifeAtTitle,
    } = attributes;

    jQuery(document).ready(function($) { 
        $('.office__gallery__slider').owlCarousel({
            center: true,
            loop:true,
            margin:30,
            nav:false,
            dots: false,
            responsive:{
                0:{
                    items:1
                },
                575:{
                    items:3
                },
                992:{
                    items:4
                }
            }
        });
    });

    return (
        <>
            <div className="easyjobs-shortcode-wrapper ej-template-classic">
                <div className="easyjobs-details">
                    {( companyDetails?.show_life && companyDetails?.showcase_photo?.length > 0 ) ? (
                        <div className="ej-section ej-company-showcase-classic">
                            <div className="section__header section__header--text-center" id="open_job_position">
                                <div className="ej-section-title">
                                    <h2 className="ej-section-title-text">
                                    {lifeAtTitle !== '' ? lifeAtTitle : `Life at ${companyDetails?.name}`}
                                    </h2>
                                </div>
                            </div>
                            <div className="ej-section-content">
                                <div className="office__gallery__slider ej-company-showcase owl-carousel">
                                    {companyDetails?.showcase_photo?.map( (data, i) => {
                                        return (
                                            <div className="item">
                                                <img src={data} alt={companyDetails?.name} />
                                            </div>
                                        );
                                    })}
                                </div>
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

export default Classic;