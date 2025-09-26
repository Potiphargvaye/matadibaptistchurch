const JobFilter = ({props, categories, locations}) => {

	const {attributes, setAttributes} = props;
	const {
		filterByTitle,
		filterByCategory,
		filterByLocation,
		titleSearchValue,
	} = attributes;
	
	const handleSubmitClick = ( e ) => {
		e.preventDefault();
	}
	return (
		<form id="ejJobFilterForm" className="ej-job-filter-form job-filter d-flex" action="" method="get">
			{filterByTitle && 
				<div className="search-bar">
					<input type="text" id="job_title" name="job_title" value={titleSearchValue} className="form-control" placeholder="Job Title" onChange={e => setAttributes({titleSearchValue: e?.value})} />
				</div>
			}
			{filterByCategory &&
				<div className="select-option category">
					<select name="job_category" id="job_category" onChange={e => setAttributes({categorySearchValue: e?.value})}>
						<option value="">Select Category</option>
						{categories?.map((cat, i)=>
							<option value={cat?.id}>{cat?.name}</option>
						)}
					</select>
				</div>
			}
			{filterByLocation &&
				<div className="select-option locations">
					<select name="job_location" id="job_location" onChange={e => setAttributes({locationSearchValue: e.value})}>
						<option value="">Select Location</option>
						{locations?.map((location, i)=>
							<option value={location?.id}>{location?.name}</option>
						)}
					</select>
				</div>
			}
			<div className="d-flex">
				<button onClick={ ( e ) => handleSubmitClick( e ) } className="ej-btn ej-info-btn-light mr15" type="submit">Submit</button>
				<button id="ej-reset-form" className="ej-btn ej-danger-btn" type="reset">Reset</button>
			</div>
		</form>
	);
}

export default JobFilter;