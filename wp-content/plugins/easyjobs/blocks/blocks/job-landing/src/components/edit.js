
import { InnerBlocks } from '@wordpress/block-editor';
import { withSelect, dispatch, useSelect } from '@wordpress/data';
import { createBlock } from "@wordpress/blocks";
import Inspector from './inspector.js';
import { useEffect, useState } from '@wordpress/element';
const {
  	BlockProps
} = window.EJControls;

const Edit = ( props ) => {
    const { isSelected, attributes, companyInfo, clientId } = props;
    const [apiError, setApiError] = useState(false);
    const {
        hideJobHeader,
        hideJobList,
        hideJobFooter,
        cover,
    } = attributes;

    useEffect(() => {
		if((companyInfo?.length === 1)  && (companyInfo[0] === 'api-error')) {
			setApiError(true);
		}	
	}, [companyInfo]);

    let ALLOWED_TEMPLATES = [
        [ 'easyjobs/job-header', {} ], 
        [ 'easyjobs/job-list',{} ], 
        [ 'easyjobs/job-footer', {} ]
    ];

    ALLOWED_TEMPLATES = ALLOWED_TEMPLATES.filter( ( item, index ) => {
        if ( index === 0 && ! hideJobHeader ) return true;
        if ( index === 1 && ! hideJobList ) return true;
        if ( index === 2 && ! hideJobFooter ) return true;
        return false;
    } );
    // if( clientId ) {
        // const { innerBlocks } = select( "core/block-editor" )?.getBlock( clientId );
    // }
    const { innerBlocks } = useSelect( select => {
        const { getBlock } = select( 'core/block-editor' );
        const block = getBlock( clientId );
        return block || {}; // Fallback to an empty object if block is undefined
    }, [clientId]);

    const containsBlock  = (array, blockName) => {
        return array.some(element => element.name === blockName);
    }
   
    useEffect( () => {
        if( innerBlocks.length ) {
            let newBlocks = [ ...innerBlocks ];
            const attrNames = [
                { attr: 'hideJobHeader', block: "easyjobs/job-header" },
            ];
            attrNames.forEach( each => {
                if( attributes[each.attr] ) {
                    newBlocks = newBlocks.filter( blockData => blockData.name !== each.block )
                } else if ( ! containsBlock( newBlocks, "easyjobs/job-header" ) ) {
                    const newBlock = createBlock( each.block, {} );
                    newBlocks.splice( 0, 0, newBlock );
                }
            })
            dispatch( 'core/block-editor' ).replaceInnerBlocks( clientId, newBlocks );
        }
    }, [ hideJobHeader ] );

    useEffect( () => {
        if( innerBlocks.length ) {
            let newBlocks = [ ...innerBlocks ];
            const attrNames = [
                { attr: 'hideJobList', block: "easyjobs/job-list" },
            ];
            let newBlockPosition = 1;
            if ( hideJobHeader ) {
                newBlockPosition = 0;
            }
            attrNames.forEach( each => {
                if( attributes[each.attr] ) {
                    newBlocks = newBlocks.filter( blockData => blockData.name !== each.block )
                } else if ( ! containsBlock( newBlocks, "easyjobs/job-list" ) ) {
                    const newBlock = createBlock( each.block, {} );
                    newBlocks.splice( newBlockPosition, 0, newBlock );
                }
            })
            dispatch( 'core/block-editor' ).replaceInnerBlocks( clientId, newBlocks );
        }
    }, [ hideJobList ] );

    useEffect( () => {
        if( innerBlocks.length ) {
            let newBlocks = [ ...innerBlocks ];
            const attrNames = [
                { attr: 'hideJobFooter', block: "easyjobs/job-footer" },
            ];
            let newBlockPosition = 2;
            if ( hideJobHeader && hideJobList ) {
                newBlockPosition = 0;
            } else if ( hideJobHeader || hideJobList ) {
                newBlockPosition = 1;
            }
            attrNames.forEach( each => {
                if( attributes[each.attr] ) {
                    newBlocks = newBlocks.filter( blockData => blockData.name !== each.block )
                } else if ( ! containsBlock( newBlocks, "easyjobs/job-footer" ) ) {
                    const newBlock = createBlock( each.block, {} );
                    newBlocks.splice(newBlockPosition, 0, newBlock);
                }
            } )
            dispatch( 'core/block-editor' ).replaceInnerBlocks( clientId, newBlocks );
        }
    }, [ hideJobFooter ] );

    return cover.length ? (
        <div>
            <img src={cover} alt="landing page" style={{ maxWidth: "100%" }} />
        </div>
    ) : (
        <>
			{isSelected && !apiError && <Inspector {...props} />}
            <BlockProps.Edit { ...props }>
                {! apiError ? (
                    <InnerBlocks
                        template={ ALLOWED_TEMPLATES }
                        templateLock={ false }
                        allowedBlocks={ [ 
                            'easyjobs/job-header', 
                            'easyjobs/job-list', 
                            'easyjobs/job-footer' 
                        ] }
                    />
                ) : (
                    <p className='elej-error-msg-editor'>Please Connect your EasyJobs Account</p>
                )}
            </BlockProps.Edit>
        </>
    );
}

export default withSelect( ( select, props ) => {
	const companyInfo = select( 'easyjobs' ).getCompanyInfo();
	return {
		companyInfo,
	}
} )( Edit )