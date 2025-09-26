const attributes = {
    resOption: {
        type: "string",
        default: "Desktop",
    },
    // blockId attribute for making unique className and other uniqueness ⬇
    blockId: {
        type: "string",
    },
    // blockRoot: {
    //     type: "string",
    //     default: "easyjobs_block_combined",
    // },
    // blockMeta is for keeping all the styles ⬇
    blockMeta: {
        type: "object",
    },
    hideJobHeader: {
        type: "boolean",
        default: false, 
    },
    hideJobList: {
        type: "boolean",
        default: false,
    },
    hideJobFooter: {
        type: "boolean",
        default: false,
    },
    cover: {
        type: "string",
        default: "",
    },
};

export default attributes;