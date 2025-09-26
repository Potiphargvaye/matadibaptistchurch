import {
    Button,
} from "@wordpress/components";

const DocLink = ({link}) => {
    return (
        <div className="eb-panel-control eb-support-panel">
            <div className="eb-block-support">
                <img src={`${EasyJobsLocalize?.image_url}/easyjobs.svg`} alt="Doc Icon" />
                <a href={link} target="_blank">Need Help?</a>
            </div>
            <div className="eb-block-links">
                <Button href={link} target="_blank">
                    <img src={`${EasyJobsLocalize?.image_url}/doc-icon.svg`} alt="Doc Icon" />
                    Doc
                </Button>
            </div>
        </div>
    );
}

export default DocLink;