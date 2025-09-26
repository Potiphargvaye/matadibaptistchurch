/**
 * WordPress dependeincies
 */
import { registerBlockType } from "@wordpress/blocks";
//import './store'

/**
 * Internal dependencies
 */
import Edit from './components/edit';
import Save from './components/save';
import attributes from './components/attributes';
import metadata from '../block.json';
import { ReactComponent as Icon } from "./icon.svg";

registerBlockType( metadata, {
    icon: Icon,
    attributes,
    edit: Edit,
    save: Save,
    example: {
        attributes: {
            cover: `${EasyJobsLocalize?.image_url}/block-preview/landing.png`,
        },
    },
} );