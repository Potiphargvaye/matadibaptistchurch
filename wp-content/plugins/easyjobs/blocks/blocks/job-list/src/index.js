/**
 * WordPress dependeincies
 */
import { registerBlockType } from "@wordpress/blocks";

/**
 * Internal dependencies
 */
import Edit from './components/edit';
import attributes from './components/attributes';
import metadata from '../block.json';

import { ReactComponent as Icon } from "./icon.svg";

registerBlockType( metadata, {
    icon: Icon,
    attributes,
    edit: Edit,
    save: () => null,
    example: {
        attributes: {
            cover: `${EasyJobsLocalize?.image_url}/block-preview/list.png`,
        },
    },
} );