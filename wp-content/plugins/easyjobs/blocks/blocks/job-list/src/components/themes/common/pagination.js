const Pagination = ( { jobs } ) => {
    const paginate = (current, max) => {
        if ( !current || !max ) {
            return null;
        }
        const items = [1];

        if (current === 1 && max === 1) {
            return items;
        }
        if (current > 4) {
            items.push("...");
        }
        const r  = 2;
        const r1 = current - r;
        const r2 = current + r;
        const min = Math.min(max, r2);

        for (let i = r1 > 2 ? r1 : 2; i <= min; i++) {
            items.push(i);
        }

        if (r2 + 1 < max) {
            items.push("...");
        }
        if (r2 < max) {
            items.push(max);
        }

        return items;
    }

    const renderList = () => {
		const listItems = [];
        const pages = paginate(jobs?.current_page, jobs?.last_page);
		for ( let i = 0; i < pages?.length; i++ ) {
		  listItems.push(<li className={`page-item ${jobs?.current_page == pages[i] ? 'active' : ''}`} aria-current="page"><a onClick={(e)=>e?.preventDefault()} href="#" className="page-link">{pages[i]}</a></li>);
		}
		return listItems;
	};
    return (
        <div className="custom-job-pagination">
            <nav>
                <ul className="pagination">
                    <li className="page-item" aria-disabled="true" aria-label="« Previous">
                        <a onClick={(e)=>e.preventDefault()} href="#" className="page-link" aria-hidden="true">
                            <i className="easyjobs-icon easyjobs-arrow-left"></i><span className="pagination-text">Prev</span>
                        </a>
                    </li>
                    {renderList()}
                    <li className="page-item">
                        <a onClick={(e)=>e.preventDefault()} className="page-link" href="#" rel="next" aria-label="Next »"><span className="pagination-text">Next</span>
                            <i className="easyjobs-icon easyjobs-arrow-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    );
}

export default Pagination;