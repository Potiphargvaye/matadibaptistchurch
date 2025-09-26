/**
 * WordPress dependencies
 */
import { InnerBlocks } from "@wordpress/block-editor";
const { BlockProps } = window.EJControls;
const Save = (props) => {
    return (
        <>
            <BlockProps.Save {...props}>
                <div className="ej-career-page">
                    <InnerBlocks.Content />
                </div>
            </BlockProps.Save>
        </>
    );
};

export default Save;