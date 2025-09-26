<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://easy.jobs
 * @since      1.0.0
 *
 * @package    Easyjobs
 * @subpackage Easyjobs/admin/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	<hr class="wp-header-end">
	<div class="easyjobs-wrapper admin-area">
		<div class="page-header">
			<div class="logo">
				<img src="<?php echo esc_url( EASYJOBS_ADMIN_URL ); ?>assets/img/logo.svg" alt="Easyjobs">
			</div>
			<div class="tools ml-auto">
				<p class="mb-0"><?php esc_html_e( 'Version:', 'easyjobs' ); ?><?php echo esc_html( EASYJOBS_VERSION ); ?></p>
			</div>
			<!--<div class="tools">
				<a href="#" class="btn btn-primary">Add New</a>
			</div>-->
		</div>
		<!-- Start Content-->
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-tabs">
						<li class="nav-item">
							<a
								href="#published_jobs"
								data-toggle="tab"
								aria-expanded="false"
								class="nav-link <?php echo empty( $active_tab ) || $active_tab == 'published_jobs' ? ' active' : ''; ?>"
							>
								<span class="d-none d-sm-block">
									<?php esc_html_e( 'Published Jobs', 'easyjobs' ); ?>
								</span>
							</a>
						</li>
						<li class="nav-item">
							<a
								href="#draft_jobs"
								data-toggle="tab"
								aria-expanded="true"
								class="nav-link <?php echo ! empty( $active_tab ) && $active_tab == 'draft_jobs' ? 'active' : ''; ?>"
							>
								<span class="d-none d-sm-block">
									<?php esc_html_e( 'Draft Jobs', 'easyjobs' ); ?>
								</span>
							</a>
						</li>
						<li class="nav-item">
							<a
								href="#archived_jobs"
								data-toggle="tab"
								aria-expanded="false"
								class="nav-link <?php echo ! empty( $active_tab ) && $active_tab == 'archived_jobs' ? 'active' : ''; ?>"
							>
								<span class="d-none d-sm-block">
									<?php esc_html_e( 'Archived Jobs', 'easyjobs' ); ?>
								</span>
							</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-content__header">
							<div class="row justify-content-between">
								<div class="col-lg-3 col-sm-6">
									<form class="input-group mb-3 mb-sm-0 search-easyjobs">
										<input
											type="text"
											class="form-control"
											placeholder="Search..."
										/>
										<div class="input-group-append">
											<button class="btn" type="submit">
												<i class="dashicons dashicons-search"></i>
											</button>
										</div>
									</form>
								</div>
							</div>
						</div>

						<div role="tabpanel"
							 class="tab-pane fade <?php echo empty( $active_tab ) || $active_tab == 'published_jobs' ? 'show active' : ''; ?>"
							 id="published_jobs">
							<div class="bg-white mt-3">
								<table class="table table-bordered mb-0">
									<thead>
									<tr>
										<th>
											<?php esc_html_e( 'Title', 'easyjobs' ); ?>
										</th>
										<th>
											<?php esc_html_e( 'Candidates', 'easyjobs' ); ?>
										</th>
										<th>
											<?php esc_html_e( 'Department', 'easyjobs' ); ?>
										</th>
										<th>
											<?php esc_html_e( 'Expiry Date', 'easyjobs' ); ?>
										</th>
										<th>
											<?php esc_html_e( 'Action', 'easyjobs' ); ?>
										</th>
									</tr>
									</thead>
									<tbody>
									<?php if ( ! empty( $jobs ) && ! empty( $jobs->published ) ) : ?>
										<?php foreach ( $jobs->published as $job ) : ?>
											<tr>
												<td>
													<a href="<?php echo ! empty( $published_job_page_ids[ $job->id ] ) ? esc_url( get_the_permalink( $published_job_page_ids[ $job->id ] ) ) : '#'; ?>"
													   target="_blank"><?php echo esc_html( $job->title ); ?></a>
												</td>
												<td><?php echo ! empty( $job->candidate_count ) ? esc_html( $job->candidate_count ) : 0; ?></td>
												<td>
													<?php echo ! empty( $job->department ) ? esc_html( $job->department->name ) : ''; ?>
												</td>
												<td><?php echo esc_html( $job->expire_at ); ?></td>
												<td>
													<div class="dropdown">
														<a href="javascript:void;" class="dropdown-toggle "
														   data-toggle="dropdown" aria-haspopup="true"
														   aria-expanded="false"><i
																class="dashicons dashicons-admin-generic"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item"
															   href="<?php echo esc_url( get_the_permalink( $published_job_page_ids[ $job->id ] ) ); ?>"
															   target="_blank"><?php esc_html_e( 'Preview', 'easyjobs' ); ?></a>
															<a class="dropdown-item"
															   href="<?php echo esc_url( admin_url( 'admin.php?page=easyjobs-admin&job-id=' . $job->id . '&view=pipeline' ) ); ?>"><?php esc_html_e( 'Pipeline', 'easyjobs' ); ?></a>
															<a class="dropdown-item"
															   href="<?php echo esc_url( admin_url( 'admin.php?page=easyjobs-admin&job-id=' . $job->id . '&view=candidates' ) ); ?>"><?php esc_html_e( 'Candidates', 'easyjobs' ); ?></a>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<td colspan="5">
												<div class="alert alert-danger text-center m-0" role="alert">
													<?php
													if ( ! empty( $no_result_messages ) && ! empty( $no_result_messages['published'] ) ) {
														echo esc_html( $no_result_messages['published'] );
													} else {
														esc_html_e( 'No published jobs found', 'easyjobs' );
													}
													?>
												</div>
											</td>
										</tr>
									<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>

						<div role="tabpanel"
							 class="tab-pane fade <?php echo ! empty( $active_tab ) && $active_tab == 'draft_jobs' ? 'show active' : ''; ?>"
							 id="draft_jobs">
							<div class="bg-white mt-3">
								<table class="table table-bordered mb-0">
									<thead>
									<tr>
										<th>
											<?php esc_html_e( 'Title', 'easyjobs' ); ?>
										</th>
										<th>
											<?php esc_html_e( 'Candidates', 'easyjobs' ); ?>
										</th>
										<th>
											<?php esc_html_e( 'Department', 'easyjobs' ); ?>
										</th>
										<th>
											<?php esc_html_e( 'Expiry Date', 'easyjobs' ); ?>
										</th>
										<th>
											<?php esc_html_e( 'Action', 'easyjobs' ); ?>
										</th>
									</tr>
									</thead>
									<tbody>
									<?php if ( ! empty( $jobs ) && ! empty( $jobs->draft ) ) : ?>
										<?php foreach ( $jobs->draft as $job ) : ?>
											<tr>
												<td>
													<a href="#"><?php echo esc_html( $job->title ); ?></a>
												</td>
												<td><?php echo esc_html( $job->candidate_count ); ?></td>
												<td>
													<?php
													echo ! empty( $job->department->name ) ?
														esc_html( $job->department->name ) : '';
													?>
												</td>
												<td><?php echo esc_html( $job->expire_at ); ?></td>
												<td>
													<div class="dropdown">
														<a href="javascript:void;" class="dropdown-toggle "
														   data-toggle="dropdown" aria-haspopup="true"
														   aria-expanded="false"><i
																class="dashicons dashicons-admin-generic"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item"
															   href="#"
															   target="_blank"><?php esc_html_e( 'Preview', 'easyjobs' ); ?></a>
															<a class="dropdown-item"
															   href="<?php echo esc_url( admin_url( 'admin.php?page=easyjobs-all-jobs&job-id=' . $job->id . '&view=pipeline' ) ); ?>"><?php esc_html_e( 'Pipeline', 'easyjobs' ); ?></a>
															<a class="dropdown-item"
															   href="<?php echo esc_url( admin_url( 'admin.php?page=easyjobs-all-jobs&job-id=' . $job->id . '&view=candidates' ) ); ?>"><?php esc_html_e( 'Candidates', 'easyjobs' ); ?></a>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<td colspan="5">
												<div class="alert alert-danger text-center m-0" role="alert">
													<?php
													if ( ! empty( $no_result_messages ) && ! empty( $no_result_messages['draft'] ) ) {
														echo esc_html( $no_result_messages['draft'] );
													} else {
														esc_html_e( 'No Draft jobs found' );
													}
													?>
												</div>
											</td>
										</tr>
									<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>

						<div role="tabpanel"
							 class="tab-pane fade <?php echo ! empty( $active_tab ) && $active_tab == 'archived_jobs' ? 'show active' : ''; ?>"
							 id="archived_jobs">
							<div class="bg-white mt-3">
								<table class="table table-bordered mb-0">
									<thead>
									<tr>
										<th>
											<?php esc_html_e( 'Title', 'easyjobs' ); ?>
										</th>
										<th>
											<?php esc_html_e( 'Candidates', 'easyjobs' ); ?>
										</th>
										<th>
											<?php esc_html_e( 'Department', 'easyjobs' ); ?>
										</th>
										<th>
											<?php esc_html_e( 'Expiry Date', 'easyjobs' ); ?>
										</th>
										<th>
											<?php esc_html_e( 'Action', 'easyjobs' ); ?>
										</th>
									</tr>
									</thead>
									<tbody>
									<?php if ( ! empty( $jobs ) && ! empty( $jobs->archived ) ) : ?>
										<?php foreach ( $jobs->archived as $job ) : ?>
											<tr>
												<td>
													<a href="#"><?php echo esc_html( $job->title ); ?></a>
												</td>
												<td><?php echo esc_html( $job->candidate_count ); ?></td>
												<td>
													<?php
													echo ! empty( $job->department->name ) ?
														esc_html( $job->department->name ) : '';
													?>
												</td>
												<td>
													<?php echo esc_html( $job->expire_at ); ?>
												</td>
												<td>
													<div class="dropdown">
														<a href="javascript:void;" class="dropdown-toggle "
														   data-toggle="dropdown" aria-haspopup="true"
														   aria-expanded="false"><i
																class="dashicons dashicons-admin-generic"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item"
															   href="#"><?php esc_html_e( 'Preview', 'easyjobs' ); ?></a>
															<a class="dropdown-item"
															   href="<?php echo esc_url( admin_url( 'admin.php?page=easyjobs-all-jobs&job-id=' . $job->id . '&view=pipeline' ) ); ?>"><?php esc_html_e( 'Pipeline', 'easyjobs' ); ?></a>
															<a class="dropdown-item"
															   href="<?php echo esc_url( admin_url( 'admin.php?page=easyjobs-all-jobs&job-id=' . $job->id . '&view=candidates' ) ); ?>"><?php esc_html_e( 'Candidates', 'easyjobs' ); ?></a>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<td colspan="5">
												<div class="alert alert-danger text-center m-0" role="alert">
													<?php
													if ( ! empty( $no_result_messages ) && ! empty( $no_result_messages['archived'] ) ) {
														esc_html( $no_result_messages['archived'] );
													} else {
														esc_html_e( 'No archived jobs found', 'easyjobs' );
													}
													?>
												</div>
											</td>
										</tr>
									<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>

					</div>
				</div>
			</div>
			<!-- end row -->
		</div>
		<!-- container-fluid -->
	</div>
</div>
