import { __ } from "@wordpress/i18n";
import * as typoPrefixes from "./typographyConstants";

const {
    generateTypographyAttributes,
} = window.EJControls;

const attributes = {
    // the following 4 attributes is must required for responsive options and asset generation for frontend
    // responsive control attributes ⬇
    resOption: {
        type: "string",
        default: __( "Desktop", "easyjobs" ),
    },
    // blockId attribute for making unique className and other uniqueness ⬇
    blockId: {
        type: "string",
    },
    // blockRoot: {
    //     type: "string",
    //     default: "easyjobs_block_footer",
    // },
    // blockMeta is for keeping all the styles ⬇
    blockMeta: {
        type: "object",
    },
    lifeAtTitle: {
        type: "string",
        default: __( "Life at", "easyjobs" )
    },
    galleryTitleColor: {
        type: "string",
        default: "",
    },
    cover: {
        type: "string",
        default: "",
    },
    // typography attributes ⬇
    ...generateTypographyAttributes(Object.values(typoPrefixes)),
};

export default attributes;